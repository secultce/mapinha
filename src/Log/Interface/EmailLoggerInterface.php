<?php

declare(strict_types=1);

namespace App\Log\Interface;

interface EmailLoggerInterface
{
    public function logEmailNotSent(string $email, string $messageError): void;
}
