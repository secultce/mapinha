<?php

declare(strict_types=1);

namespace App\Service\ExporterInscriptions;

interface ExportableSourceInterface
{
    public function getExportSourceName(): string;
}
