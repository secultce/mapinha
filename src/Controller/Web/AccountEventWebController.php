<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Exception\AccountEvent\ExpiredTokenException;
use App\Exception\AccountEvent\InvalidTokenException;
use App\Exception\User\UserResourceNotFoundException;
use App\Service\Interface\AccountEventServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class AccountEventWebController extends AbstractWebController
{
    public const string VIEW_FORGOT = 'account-event/forgot-password.twig';
    public const string VIEW_RESET = 'account-event/password-reset.twig';
    public const string VIEW_ACCOUNT_CONFIRMED = 'account-event/account-confirmed.twig';

    public const string FORGOT_FORM_ID = 'forgot';
    public const string RESET_FORM_ID = 'reset';

    public function __construct(
        public readonly AccountEventServiceInterface $accountEventService,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function confirmAccount(
        string $token,
    ): Response {
        try {
            $this->accountEventService->confirmAccount($token);
        } catch (InvalidTokenException|ExpiredTokenException $exception) {
            $this->addFlash('error', $exception->getMessage());

            return $this->redirectToRoute('web_home_homepage');
        }

        return $this->render(self::VIEW_ACCOUNT_CONFIRMED);
    }

    public function resetAccount(
        Request $request,
        string $token,
    ): Response {
        if (false === $request->isMethod(Request::METHOD_POST)) {
            return $this->render(self::VIEW_RESET, [
                'form_id' => self::RESET_FORM_ID,
                'token' => $token,
            ]);
        }

        $this->accountEventService->resetPassword($token, $request);

        $this->addFlash('success', $this->translator->trans('view.authentication.forgot.success'));

        return $this->redirectToRoute('web_auth_login');
    }

    public function forgotPassword(
        Request $request,
    ): Response {
        if (false === $request->isMethod(Request::METHOD_POST)) {
            return $this->render(self::VIEW_FORGOT, [
                'form_id' => self::FORGOT_FORM_ID,
            ]);
        }

        $email = $request->request->get('email');

        try {
            $this->accountEventService->sendResetPasswordEmail($email);
        } catch (UserResourceNotFoundException) {
        }

        $this->addFlash('success', $this->translator->trans('view.authentication.forgot.link_sent_to_email'));

        return $this->redirectToRoute('web_home_homepage');
    }
}
