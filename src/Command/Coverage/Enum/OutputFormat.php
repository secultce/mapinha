<?php

declare(strict_types=1);

namespace App\Command\Coverage\Enum;

enum OutputFormat: string
{
    case TABLE = 'table';
    case JSON = 'json';
    case TEXT = 'text';
}
