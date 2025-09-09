<?php

declare(strict_types=1);

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum ExportFormatEnum: string
{
    use EnumTrait;

    case PDF = 'pdf';
    case XLS = 'xls';
}
