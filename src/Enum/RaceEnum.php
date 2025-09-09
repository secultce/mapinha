<?php

declare(strict_types=1);

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum RaceEnum: string
{
    use EnumTrait;

    case BRANCA = 'Branca';
    case PRETA = 'Preta';
    case PARDA = 'Parda';
    case AMARELA = 'Amarela';
    case INDIGENA = 'Indígena';
    case OUTRA = 'Outra';
    case PREFERE_NAO_INFORMAR = 'Prefere não informar';
}
