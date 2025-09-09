<?php

declare(strict_types=1);

namespace App\Security;

use App\Exception\AccountEvent\AccountNotActivatedException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class LoginFailureHandler implements AuthenticationFailureHandlerInterface
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private LoggerInterface $logger,
        private TranslatorInterface $translator,
    ) {
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $errorMessage = $this->getErrorMessage($exception);

        $request->getSession()->getFlashBag()->add('error', $errorMessage);

        $this->logFailedAttempt($request);

        $parameters = [];

        if (true === $request->request->has('_target_path')) {
            $parameters['target_path_invite'] = $request->request->get('_target_path');
        }

        return new RedirectResponse($this->urlGenerator->generate('web_auth_login', $parameters));
    }

    private function getErrorMessage(AuthenticationException $exception): string
    {
        return match (get_class($exception)) {
            BadCredentialsException::class => $this->translator->trans('invalid_credentials'),
            AccountNotActivatedException::class => $this->translator->trans('view.authentication.error.account_not_confirmed'),
            default => $this->translator->trans('view.authentication.error.an_error_occurred_during_login'),
        };
    }

    private function logFailedAttempt(Request $request): void
    {
        $email = $request->request->get('email');
        $ip = $request->getClientIp();

        $this->logger->critical('Failed attempting to log login | {email} | {ip}', [
            'email' => $email,
            'ip' => $ip,
        ]);

        // TODO: Implement logic to register of auth events
    }
}
