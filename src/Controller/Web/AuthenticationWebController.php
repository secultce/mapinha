<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Entity\Invite;
use App\Exception\ValidatorException;
use App\Service\Interface\AccountEventServiceInterface;
use App\Service\Interface\InviteServiceInterface;
use App\Service\Interface\UserServiceInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use InvalidArgumentException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\Translation\TranslatorInterface;

class AuthenticationWebController extends AbstractWebController
{
    public const string REGISTER_FORM_ID = 'add-user';

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly UserServiceInterface $userService,
        private readonly Security $security,
        private readonly AccountEventServiceInterface $accountEventService,
        private readonly InviteServiceInterface $inviteService,
    ) {
    }

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if (null !== $this->getUser()) {
            return $this->redirectToRoute('admin_dashboard');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($error && 'Invalid credentials.' === $error->getMessageKey()) {
            $error = new CustomUserMessageAuthenticationException('invalid_credentials');
        }

        return $this->render('authentication/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    public function register(Request $request): Response
    {
        if (null !== $this->security->getUser()) {
            $this->addFlash('error', 'view.authentication.error.already_logged_in');

            return $this->redirectToRoute('web_home_homepage');
        }

        $error = null;

        if (false === $request->isMethod('POST')) {
            return $this->render('authentication/register.html.twig', [
                'error' => $error,
                'form_id' => self::REGISTER_FORM_ID,
            ]);
        }

        $this->validCsrfToken(self::REGISTER_FORM_ID, $request);

        $firstName = $request->request->get('first_name');
        $lastName = $request->request->get('last_name');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $cpf = str_replace(['.', '-'], '', $request->request->get('cpf'));
        $phone = str_replace(['(', ')', '-', ' '], '', $request->request->get('phone'));

        try {
            $invite = $this->validateInvite($request, $email);

            $user = $this->userService->create([
                'id' => Uuid::v4(),
                'firstname' => $firstName,
                'lastname' => $lastName,
                'email' => $email,
                'password' => $password,
                'cpf' => $cpf,
                'phone' => $phone,
            ]);
        } catch (UniqueConstraintViolationException) {
            $error = $this->translator->trans('view.authentication.error.email_in_use');
        } catch (ValidatorException $exception) {
            $violations = $exception->getConstraintViolationList();
            if ($violations->count() > 0) {
                $error = $violations->get(0)->getMessage();
            }
        } catch (InvalidArgumentException $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirect($request->request->get('_target_path'));
        } catch (Exception $exception) {
            $error = $this->translator->trans('view.authentication.error.error_message').$exception->getMessage();
        }

        if (null !== $error) {
            return $this->render('authentication/register.html.twig', [
                'error' => $error,
                'form_id' => self::REGISTER_FORM_ID,
            ]);
        }

        try {
            $this->accountEventService->sendConfirmationEmail($user);
        } catch (Exception) {
            $this->addFlash('error', 'view.authentication.error.email_not_sent');
        }

        if (null !== $invite) {
            $this->inviteService->updateGuest($invite->getId(), $user);

            return $this->redirect($request->request->get('_target_path'));
        }

        return $this->render('authentication/register_success.html.twig');
    }

    private function validateInvite(Request $request, string $email): ?Invite
    {
        $targetPath = $request->request->get('_target_path');
        if (!$targetPath) {
            return null;
        }

        $inviteId = $this->extractInviteId($targetPath);
        if (!Uuid::isValid($inviteId)) {
            throw new InvalidArgumentException($this->translator->trans('invalid_identifier'));
        }

        $invite = $this->inviteService->get(Uuid::fromString($inviteId));
        if ($invite->getEmail() !== $email) {
            throw new InvalidArgumentException($this->translator->trans('view.authentication.error.invite_is_not_for_this_email'));
        }

        return $invite;
    }

    private function extractInviteId(string $targetPath): ?string
    {
        preg_match('/\/convites\/([^\/]+)/', $targetPath, $matches);

        return $matches[1] ?? null;
    }
}
