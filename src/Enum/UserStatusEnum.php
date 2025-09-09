<?php

declare(strict_types=1);

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum UserStatusEnum: string
{
    use EnumTrait;

    case AWAITING_CONFIRMATION = 'AwaitingConfirmation';
    case ACTIVE = 'Active';
    case BLOCKED = 'Blocked';
}
