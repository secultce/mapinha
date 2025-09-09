<?php

declare(strict_types=1);

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum RegionEnum: string
{
    use EnumTrait;

    case NORTE = 'Norte';
    case NORDESTE = 'Nordeste';
    case SUDESTE = 'Sudeste';
    case SUL = 'Sul';
    case CENTRO_OESTE = 'Centro-Oeste';
}
