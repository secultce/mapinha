<?php

declare(strict_types=1);

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum EventFormatEnum: int
{
    use EnumTrait;

    case IN_PERSON = 1;
    case ONLINE = 2;
    case HYBRID = 3;
}
