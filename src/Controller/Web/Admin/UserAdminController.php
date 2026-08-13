<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\Document\UserTimeline;
use App\DocumentService\AuthTimelineDocumentService;
use App\DocumentService\UserTimelineDocumentService;
use App\Enum\FlashMessageTypeEnum;
use App\Enum\UserRolesEnum;
use App\Exception\User\UserResourceNotFoundException;
use App\Exception\ValidatorException;
use App\Security\PasswordHasher;
use App\Service\Interface\AccountEventServiceInterface;
use App\Service\Interface\AgentServiceInterface;
use App\Service\Interface\UserServiceInterface;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;
use TypeError;

class UserAdminController extends AbstractAdminController
{
    private const ADD = 'user/create.html.twig';

    public const CREATE_FORM_ID = 'add-user';

    public function __construct(
        private readonly UserTimelineDocumentService $documentService,
        private readonly UserServiceInterface $service,
        private readonly UserTimeline $userTimeline,
        private readonly AuthTimelineDocumentService $authDocumentService,
        private readonly AgentServiceInterface $agentService,
        private JWTTokenManagerInterface $jwtManager,
        private readonly TranslatorInterface $translator,
        private readonly Security $security,
        private readonly AccountEventServiceInterface $accountEventService,
    ) {
    }

    #[IsGranted(new Expression(
        'is_granted("'.UserRolesEnum::ROLE_ADMIN->value.'") or '.
        'is_granted("'.UserRolesEnum::ROLE_MANAGER->value.'")'
    ), statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function list(): Response
    {
        $users = $this->service->findAll();

        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }

    #[IsGranted(new Expression(
        'is_granted("'.UserRolesEnum::ROLE_ADMIN->value.'") or '.
        'is_granted("'.UserRolesEnum::ROLE_MANAGER->value.'")'
    ), statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function create(Request $request): Response
    {
        if (false === $request->isMethod(Request::METHOD_POST)) {
            return $this->render(self::ADD, [
                'form_id' => self::CREATE_FORM_ID,
            ]);
        }

        $this->validCsrfToken(self::CREATE_FORM_ID, $request);

        $errors = [];

        try {
            $user = $this->service->create([
                'id' => Uuid::v4(),
                'firstname' => $request->get('firstname'),
                'lastname' => $request->get('lastname'),
                'email' => $request->get('email'),
                'password' => $request->get('password'),
                'roles' => [UserRolesEnum::ROLE_USER->value],
                'extraFields' => [
                    'cpf' => $request->get('cpf'),
                    'cargo' => $request->get('position'),
                    'createdby' => $this->security->getUser()->getId(),
                ],
            ]);

            $this->addFlash(FlashMessageTypeEnum::SUCCESS->value, $this->translator->trans('view.user.message.created'));
        } catch (ValidatorException $exception) {
            foreach ($exception->getConstraintViolationList() as $violation) {
                $errors[] = [
                    'propertyPath' => $violation->getPropertyPath(),
                    'message' => $this->translator->trans($violation->getMessage()),
                ];
            }
        } catch (Exception $exception) {
            $errors[] = [
                'message' => $exception->getMessage(),
            ];
        }

        if (false === empty($errors)) {
            return $this->render(self::ADD, [
                'errors' => $errors,
                'form_id' => self::CREATE_FORM_ID,
            ]);
        }

        try {
            $this->accountEventService->sendResetPasswordEmail($user->getEmail(), isNewUser: true);
        } catch (Exception $exception) {
            $this->addFlash('error', 'view.authentication.error.email_not_sent');
        }

        return $this->redirectToRoute('admin_user_list');
    }

    public function timeline(Uuid $id): Response
    {
        $user = $this->service->get($id);

        $this->denyAccessUnlessGranted('get', $user);

        $events = $this->documentService->getEventsByEntityId($id);

        $authEvents = $this->authDocumentService->getTimelineLoginByUserId($id);

        return $this->render('user/timeline.html.twig', [
            'user' => $user,
            'events' => $events,
            'authEvents' => $authEvents,
        ]);
    }

    public function details(Uuid $id): Response
    {
        $user = $this->service->get($id);

        $this->denyAccessUnlessGranted('get', $user);

        $lastLogin = $this->documentService->getLastLoginByUserId($id);

        $agents = $this->agentService->findBy(['user' => $user]);

        return $this->render('user/details.html.twig', [
            'user' => $user,
            'lastLogin' => $lastLogin,
            'agents' => $agents,
        ]);
    }

    public function accountPrivacy(Uuid $id): Response
    {
        $user = $this->service->get($id);

        $this->denyAccessUnlessGranted('get', $user);

        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $lastLogin = $this->documentService->getLastLoginByUserId($id);

        $agents = $this->agentService->findBy(['user' => $user]);

        return $this->render('user/account-privacy.html.twig', [
            'user' => $user,
            'lastLogin' => $lastLogin,
            'agents' => $agents,
        ]);
    }

    private function updateUserData($user, Request $request): void
    {
        $userData = [
            'firstname' => $request->request->get('firstname'),
            'lastname' => $request->request->get('lastname'),
            'socialName' => $request->request->get('socialName'),
            'email' => $request->request->get('email'),
        ];

        if (true !== empty($request->request->get('password'))) {
            $userData['password'] = PasswordHasher::hash($request->request->get('password'));
        }

        $this->service->update($user->getId(), $userData, $request->server->get('HTTP_USER_AGENT'));

        if ($uploadedImage = $request->files->get('profileImage')) {
            $this->service->updateImage($user->getId(), $uploadedImage);
        }

        if ($uploadedCover = $request->files->get('coverImage')) {
            $this->service->updateCoverImage($user->getId(), $uploadedCover);
        }
    }

    private function updateAgentData(Request $request): void
    {
        if ($agentIdString = $request->request->get('agent')) {
            $agentId = Uuid::fromString($agentIdString);

            $agentData = [
                'name' => $request->request->get('name'),
                'shortBio' => $request->request->get('short_description'),
                'longBio' => $request->request->get('long_description'),
                'extraFields' => [
                    'cargo' => $request->request->get('cargo'),
                    'cpf' => $request->request->get('cpf'),
                ],
            ];

            if ($uploadedImage = $request->files->get('profileImage')) {
                $this->agentService->updateImage($agentId, $uploadedImage);
            }

            $this->agentService->update($agentId, $agentData);
        }
    }

    public function editUserProfile(Uuid $id, Request $request): Response
    {
        $user = $this->service->get($id);

        $this->denyAccessUnlessGranted('edit', $user);

        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $agents = $this->agentService->findBy(['user' => $user]);
        $token = $this->jwtManager->create($user);

        if (!$request->isMethod(Request::METHOD_POST)) {
            return $this->renderEditProfile($user, $agents, $token);
        }

        $this->validCsrfToken('edit_profile', $request);

        try {
            $this->updateUserData($user, $request);
            $this->updateAgentData($request);

            $this->addFlashSuccess($this->translator->trans('view.user.message.updated'));
        } catch (Exception|TypeError $exception) {
            $message = $exception->getMessage();

            if (str_contains($message, 'violates one or more constraints')) {
                $message = $this->translator->trans('provided_violates');
            }

            $this->addFlashError($message);

            return $this->renderEditProfile($user, $agents, $token, $message);
        }

        return $this->accountPrivacy($user->getId());
    }

    private function renderEditProfile($user, $agents, $token, ?string $error = null): Response
    {
        return $this->render('user/edit-profile.html.twig', [
            'user' => [
                'name' => $user->getName(),
                'id' => $user->getId(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'socialName' => $user->getSocialName(),
                'email' => $user->getEmail(),
                'image' => $user->getImage(),
                'coverImage' => $user->getCoverImage(),
            ],
            'form_id' => 'edit_profile',
            'agents' => $agents,
            'token' => $token,
            'error' => $error,
        ]);
    }

    #[IsGranted(UserRolesEnum::ROLE_ADMIN->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function confirmAccount(Uuid $id): Response
    {
        try {
            $this->service->confirmAccount($id);

            $this->addFlashSuccess($this->translator->trans('view.user.message.confirmed'));
        } catch (UserResourceNotFoundException $exception) {
            $this->addFlashError($this->translator->trans('view.user.message.not_confirmed'));
        }

        return $this->redirectToRoute('admin_user_list');
    }
}
