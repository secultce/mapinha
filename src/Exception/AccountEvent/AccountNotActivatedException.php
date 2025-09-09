<?php

declare(strict_types=1);

namespace App\Exception\AccountEvent;

use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Throwable;

class AccountNotActivatedException extends AuthenticationException
{
    public function __construct(?Throwable $previous = null)
    {
        $message = 'Your account is not activated, please contact the administrator or verify your email.';

        parent::__construct($message, 0, $previous);
    }

    public function getMessageKey(): string
    {
        return 'view.authentication.error.account_not_confirmed';
    }
}
