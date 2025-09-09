<?php

declare(strict_types=1);

namespace App\Command\Coverage\Renderer;

use App\Command\Coverage\CoverageResult;
use App\Command\Coverage\Enum\CoverageMetric;
use App\Command\Coverage\Enum\OutputFormat;
use Symfony\Component\Console\Style\SymfonyStyle;

class CoverageResultRenderer
{
    public function render(SymfonyStyle $io, CoverageResult $result, OutputFormat $format): void
    {
        match ($format) {
            OutputFormat::JSON => $this->renderJson($io, $result),
            OutputFormat::TEXT => $this->renderText($io, $result),
            OutputFormat::TABLE => $this->renderTable($io, $result),
        };
    }

    private function renderTable(SymfonyStyle $io, CoverageResult $result): void
    {
        $io->title('Test Coverage Report');

        $rows = array_map(
            fn (CoverageMetric $metric) => $this->buildTableRow($metric, $result),
            CoverageMetric::cases()
        );

        $io->table(['Metric', 'Coverage', 'Total', 'Percentage', 'Threshold'], $rows);

        if (!$result->isPassed()) {
            $io->error('Some metrics did not meet the minimum threshold');
        }
    }

    private function buildTableRow(CoverageMetric $metric, CoverageResult $result): array
    {
        $coverage = $result->data->getCoverage($metric);
        $threshold = $result->thresholds->{$metric->value};
        $color = $result->hasPassed($metric) ? 'green' : 'red';

        return [
            CoverageMetric::getName($metric->value),
            number_format($result->data->getCovered($metric)),
            number_format($result->data->getTotal($metric)),
            sprintf('<fg=%s>%.2f%%</>', $color, $coverage),
            number_format($threshold, 2).'%',
        ];
    }

    private function renderText(SymfonyStyle $io, CoverageResult $result): void
    {
        $io->writeln('Current coverage:');
        foreach (CoverageMetric::cases() as $metric) {
            $io->writeln(sprintf(
                ' - %s: %.2f%% (%d/%d)',
                CoverageMetric::getName($metric->value),
                $result->data->getCoverage($metric),
                $result->data->getCovered($metric),
                $result->data->getTotal($metric)
            ));
        }

        $io->writeln("\nMinimum threshold:");
        foreach (CoverageMetric::cases() as $metric) {
            $io->writeln(sprintf(
                ' - %s: %.2f%%',
                CoverageMetric::getName($metric->value),
                $result->thresholds->{$metric->value}
            ));
        }

        $io->writeln(sprintf(
            "\nStatus: %s",
            $result->isPassed() ? 'APPROVED' : 'FAILED'
        ));

        if (!$result->isPassed()) {
            $io->writeln("\nDeficits:");
            foreach (CoverageMetric::cases() as $metric) {
                if (!$result->hasPassed($metric)) {
                    $io->writeln(sprintf(
                        ' - %s: %.2f%% below threshold',
                        CoverageMetric::getName($metric->value),
                        $result->getDeficit($metric)
                    ));
                }
            }
        }
    }

    private function renderJson(SymfonyStyle $io, CoverageResult $result): void
    {
        $io->writeln(json_encode($result->toArray(), JSON_PRETTY_PRINT));
    }
}
