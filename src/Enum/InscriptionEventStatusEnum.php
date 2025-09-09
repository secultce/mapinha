<?php

declare(strict_types=1);

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum InscriptionEventStatusEnum: int
{
    use EnumTrait;

    case ACTIVE = 1;
    case CANCELLED = 2;
    case CONFIRMED = 3;
    case SUSPENDED = 4;
}
