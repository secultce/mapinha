<?php

declare(strict_types=1);

namespace App\Service\ExporterInscriptions;

use App\Enum\ExportFormatEnum;
use Symfony\Component\HttpFoundation\Response;

interface InscriptionExporterStrategyInterface
{
    public function supports(ExportFormatEnum $format): bool;

    public function export(ExportableSourceInterface $source, array $inscriptions): Response;
}
