<?php

declare(strict_types=1);

namespace App\Command\Coverage\Enum;

use App\Enum\Trait\EnumTrait;

enum CoverageMetric: string
{
    use EnumTrait;

    case METRIC_LINE = 'line';
    case METRIC_BRANCH = 'branch';
    case METRIC_PATH = 'path';
    case METRIC_METHOD = 'method';
    case METRIC_CLASS = 'class';
}
