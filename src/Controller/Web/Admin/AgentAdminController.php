<?php

declare(strict_types=1);

namespace App\Controller\Web\Admin;

use App\DocumentService\AgentTimelineDocumentService;
use App\Enum\FlashMessageTypeEnum;
use App\Enum\UserRolesEnum;
use App\Exception\ValidatorException;
use App\Service\Interface\AgentServiceInterface;
use Exception;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class AgentAdminController extends AbstractAdminController
{
    private const string VIEW_ADD = 'agent/create.html.twig';
    private const string VIEW_EDIT = 'agent/edit.html.twig';

    public const string CREATE_FORM_ID = 'add-agent';
    public const string EDIT_FORM_ID = 'edit-agent';

    public function __construct(
        private readonly AgentServiceInterface $service,
        private readonly AgentTimelineDocumentService $documentService,
        private readonly JWTTokenManagerInterface $jwtManager,
        private readonly TranslatorInterface $translator,
        private readonly Security $security,
    ) {
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function list(UserInterface $user): Response
    {
        $agents = $this->service->findBy();

        $token = $this->jwtManager->create($user);

        return $this->render('agent/list.html.twig', [
            'agents' => $agents,
            'token' => $token,
        ]);
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function create(Request $request): Response
    {
        if (false === $request->isMethod(Request::METHOD_POST)) {
            return $this->render(self::VIEW_ADD, [
                'form_id' => self::CREATE_FORM_ID,
            ]);
        }

        $this->validCsrfToken(self::CREATE_FORM_ID, $request);

        $errors = [];

        try {
            $this->service->create([
                'id' => Uuid::v4(),
                'name' => $request->get('name'),
                'shortBio' => $request->get('shortBio'),
                'longBio' => $request->get('shortBio'),
                'culture' => false,
                'user' => $this->security->getUser()->getId(),
            ]);

            $this->addFlash(FlashMessageTypeEnum::SUCCESS->value, $this->translator->trans('view.agent.message.created'));
        } catch (ValidatorException $exception) {
            $errors = $exception->getConstraintViolationList();
        } catch (Exception $exception) {
            $errors = [$exception->getMessage()];
        }

        if (false === empty($errors)) {
            return $this->render(self::VIEW_ADD, [
                'errors' => $errors,
                'form_id' => self::CREATE_FORM_ID,
            ]);
        }

        return $this->redirectToRoute('admin_agent_list');
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function timeline(?Uuid $id): Response
    {
        $agent = $this->service->get($id);

        $this->denyAccessUnlessGranted('get', $agent);

        $events = $this->documentService->getEventsByEntityId($id);

        return $this->render('agent/timeline.html.twig', [
            'agent' => $agent,
            'events' => $events,
        ]);
    }

    #[IsGranted(UserRolesEnum::ROLE_USER->value, statusCode: self::ACCESS_DENIED_RESPONSE_CODE)]
    public function remove(?Uuid $id): Response
    {
        $agent = $this->service->get($id);

        $this->denyAccessUnlessGranted('remove', $agent);

        $this->service->remove($id);

        $this->addFlash('success', 'view.agent.message.deleted');

        return $this->redirectToRoute('admin_agent_list');
    }

    public function edit(Uuid $id, Request $request): Response
    {
        $agent = $this->service->get($id);

        $this->denyAccessUnlessGranted('edit', $agent);

        if (false === $request->isMethod(Request::METHOD_POST)) {
            return $this->render(self::VIEW_EDIT, [
                'agent' => $agent,
                'form_id' => self::EDIT_FORM_ID,
            ]);
        }

        $this->validCsrfToken(self::EDIT_FORM_ID, $request);
        try {
            $this->service->update($id, [
                'name' => $request->get('name'),
                'shortBio' => $request->request->get('short_description'),
                'longBio' => $request->request->get('long_description'),
            ]);

            $this->addFlash(FlashMessageTypeEnum::SUCCESS->value, $this->translator->trans('view.agent.message.updated'));
        } catch (ValidatorException $exception) {
            $errors = $exception->getConstraintViolationList();
        } catch (Exception $exception) {
            $errors = [$exception->getMessage()];
        }

        if (false === empty($errors)) {
            return $this->render(self::VIEW_EDIT, [
                'agent' => $agent,
                'errors' => $errors,
                'form_id' => self::EDIT_FORM_ID,
            ]);
        }

        return $this->redirectToRoute('admin_agent_list');
    }
}
