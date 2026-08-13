<?php

declare(strict_types=1);

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum EducationEnum: string
{
    use EnumTrait;

    case NOT_LITERATE = 'Não alfabetizado';
    case ELEMENTARY_INCOMPLETE = 'Fundamental incompleto';
    case ELEMENTARY_COMPLETE = 'Fundamental completo';
    case HIGH_SCHOOL_INCOMPLETE = 'Médio incompleto';
    case HIGH_SCHOOL_COMPLETE = 'Médio completo';
    case COLLEGE_INCOMPLETE = 'Superior incompleto';
    case COLLEGE_COMPLETE = 'Superior completo';
    case POSTGRADUATE = 'Pós-graduação (lato sensu)';
    case MASTER = 'Mestrado (stricto sensu)';
    case DOCTORATE = 'Doutorado (stricto sensu)';
    case POST_DOCTORATE = 'Pós-doutorado';
    case OTHER = 'Outro';
    case PREFER_NOT_TO_DISCLOSE = 'Prefere não informar';
}
