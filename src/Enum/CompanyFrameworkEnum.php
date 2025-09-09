<?php

declare(strict_types=1);

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum CompanyFrameworkEnum: string
{
    use EnumTrait;

    case PROFIT = 'Organização com fins lucarativos';
    case NO_PROFIT = 'Organização sem fins lucarativos';
}
