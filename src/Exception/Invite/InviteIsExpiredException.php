<?php

declare(strict_types=1);

namespace App\Exception\Invite;

use Exception;
use Throwable;

class InviteIsExpiredException extends Exception
{
    public function __construct(?Throwable $previous = null)
    {
        parent::__construct('The invite is expired.', 0, $previous);
    }
}
