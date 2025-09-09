<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;
use Throwable;

class UnableCreateFileException extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        $message = 'Could not create file.';

        parent::__construct($message, 0, $previous);
    }
}
