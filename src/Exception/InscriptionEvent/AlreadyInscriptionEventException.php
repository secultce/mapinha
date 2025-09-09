<?php

declare(strict_types=1);

namespace App\Exception\InscriptionEvent;

use RuntimeException;
use Throwable;

class AlreadyInscriptionEventException extends RuntimeException
{
    public function __construct(?Throwable $previous = null)
    {
        $message = 'The agent has already signed up for this event.';

        parent::__construct($message, 0, $previous);
    }
}
