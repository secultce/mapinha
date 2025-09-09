<?php

declare(strict_types=1);

namespace App\Exception\Invite;

use Exception;
use Throwable;

class InviteIsNotThisUserException extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('The invite is not for you.', 0, $previous);
    }
}
