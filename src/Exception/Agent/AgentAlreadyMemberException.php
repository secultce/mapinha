<?php

declare(strict_types=1);

namespace App\Exception\Agent;

use RuntimeException;
use Throwable;

class AgentAlreadyMemberException extends RuntimeException
{
    public function __construct(int $code = 0, ?Throwable $previous = null)
    {
        $message = 'Agent already member';
        parent::__construct($message, $code, $previous);
    }
}
