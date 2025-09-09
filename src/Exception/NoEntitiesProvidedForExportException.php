<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;
use Throwable;

class NoEntitiesProvidedForExportException extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        $message = 'No entity provided for export.';

        parent::__construct($message, 0, $previous);
    }
}
