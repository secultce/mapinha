<?php

declare(strict_types=1);

namespace App\Exception\AccountEvent;

use RuntimeException;
use Throwable;

class InvalidTokenException extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        $message = 'Invalid token.';

        parent::__construct($message, 0, $previous);
    }
}
