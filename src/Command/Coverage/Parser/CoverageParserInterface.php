<?php

declare(strict_types=1);

namespace App\Command\Coverage\Parser;

use App\Command\Coverage\CoverageData;

interface CoverageParserInterface
{
    public function parse(string $filePath): CoverageData;
}
