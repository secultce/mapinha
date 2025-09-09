<?php

declare(strict_types=1);

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum AccountEventTypeEnum: int
{
    use EnumTrait;

    case ACTIVATION = 1;
    case RECOVERY = 2;
    case PASSWORD_CHANGED = 3;
}
