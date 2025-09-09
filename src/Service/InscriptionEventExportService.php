<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Event;
use App\Enum\ExportFormatEnum;
use App\Repository\Interface\InscriptionEventRepositoryInterface;
use App\Service\ExporterInscriptions\InscriptionExporterStrategyInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

readonly class InscriptionEventExportService
{
    /** @param iterable<InscriptionExporterStrategyInterface> $strategies */
    public function __construct(
        private InscriptionEventRepositoryInterface $inscriptionEventRepository,
        private iterable $strategies
    ) {
    }

    public function export(Event $event, ExportFormatEnum $format): Response
    {
        $inscriptions = $this->inscriptionEventRepository->findInscriptionsByEvent($event->getId()->toString(), PHP_INT_MAX);

        foreach ($this->strategies as $strategy) {
            if ($strategy->supports($format)) {
                return $strategy->export($event, $inscriptions);
            }
        }

        throw new UnsupportedMediaTypeHttpException('Unsupported format');
    }
}
