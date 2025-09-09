<?php

declare(strict_types=1);

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum EducationEnum: string
{
    use EnumTrait;

    case NOT_LITERATE = 'Not literate';
    case ELEMENTARY_INCOMPLETE = 'Incomplete Elementary School';
    case ELEMENTARY_COMPLETE = 'Complete Elementary School';
    case HIGH_SCHOOL_INCOMPLETE = 'Incomplete High School';
    case HIGH_SCHOOL_COMPLETE = 'Complete High School';
    case COLLEGE_INCOMPLETE = 'Incomplete College';
    case COLLEGE_COMPLETE = 'Complete College';
    case POSTGRADUATE = 'Postgraduate (lato sensu)';
    case MASTER = 'Master (stricto sensu)';
    case DOCTORATE = 'Doctorate (stricto sensu)';
    case POST_DOCTORATE = 'Post-doctorate';
    case OTHER = 'Other';
    case PREFER_NOT_TO_DISCLOSE = 'Prefer not to disclose';
}
