<?php

declare(strict_types=1);

namespace App\Resolver\ExceptionResolver;

use App\Exception\Invite\InviteIsExpiredException;
use App\Exception\Invite\InviteIsNotThisUserException;
use App\Exception\UnauthorizedException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Contracts\Translation\TranslatorInterface;

readonly class InviteExceptionResolver implements ExceptionResolverInterface
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    /**
     * @throws Exception
     */
    public function resolve(Request $request, Exception $exception): array
    {
        return match (get_class($exception)) {
            InviteIsNotThisUserException::class => [
                $this->translator->trans('invite_not_belongs_to_you'),
                'web_home_homepage',
                [],
            ],
            InviteIsExpiredException::class => [
                $this->translator->trans('invite_expired'),
                'web_home_homepage',
                [],
            ],
            UniqueConstraintViolationException::class => [
                $this->translator->trans('user_already_belongs_the_organization'),
                'web_home_homepage',
                [],
            ],
            UnauthorizedException::class => [
                $this->translator->trans('you_must_login_now'),
                'web_auth_login',
                ['target_path_invite' => $request->getUri()],
            ],
            UserNotFoundException::class => [
                $this->translator->trans('you_must_register_now'),
                'web_auth_register',
                ['target_path_invite' => $request->getUri()],
            ],
            default => throw $exception
        };
    }
}
