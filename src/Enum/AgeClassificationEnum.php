<?php

declare(strict_types=1);

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum AgeClassificationEnum: string
{
    use EnumTrait;

    case FREE = 'Free';
    case AGE_10 = '10 years';
    case AGE_12 = '12 years';
    case AGE_14 = '14 years';
    case AGE_16 = '16 years';
    case AGE_18 = '18 years';
    case NOT_RATED = 'Not rated';
}
