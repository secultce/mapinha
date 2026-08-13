<?php

declare(strict_types=1);

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum OrganizationTypeEnum: string
{
    use EnumTrait;

    case MUNICIPIO = 'Municipio';
    case COMUNIDADE = 'Comunidade';
    case EMPRESA = 'Empresa';
    case ENTIDADE = 'Entidade';
    case OSC = 'OSC';
    case UNDEFINED = 'Outro';
}
