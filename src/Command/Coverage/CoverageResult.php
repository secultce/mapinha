<?php

declare(strict_types=1);

namespace App\Command\Coverage;

use App\Command\Coverage\Enum\CoverageMetric;

class CoverageResult
{
    private array $passed;

    private array $deficit;

    public function __construct(
        public readonly CoverageData $data,
        public readonly CoverageThresholds $thresholds
    ) {
        $this->calculateResults();
    }

    private function calculateResults(): void
    {
        foreach (CoverageMetric::cases() as $metric) {
            $this->passed[$metric->value] = $this->data->getCoverage($metric) >= $this->thresholds->{$metric->value};
            $this->deficit[$metric->value] = max(0, $this->thresholds->{$metric->value} - $this->data->getCoverage($metric));
        }
    }

    public function isPassed(): bool
    {
        return !in_array(false, $this->passed, true);
    }

    public function hasPassed(CoverageMetric $metric): bool
    {
        return $this->passed[$metric->value];
    }

    public function getDeficit(CoverageMetric $metric): float
    {
        return $this->deficit[$metric->value];
    }

    public function toArray(): array
    {
        return [
            'current_coverage' => [
                CoverageMetric::METRIC_LINE->value => $this->data->lineCoverage,
                CoverageMetric::METRIC_BRANCH->value => $this->data->branchCoverage,
                CoverageMetric::METRIC_PATH->value => $this->data->pathCoverage,
                CoverageMetric::METRIC_METHOD->value => $this->data->methodCoverage,
                CoverageMetric::METRIC_CLASS->value => $this->data->classCoverage,
            ],
            'minimum_threshold' => [
                CoverageMetric::METRIC_LINE->value => $this->thresholds->line,
                CoverageMetric::METRIC_BRANCH->value => $this->thresholds->branch,
                CoverageMetric::METRIC_PATH->value => $this->thresholds->path,
                CoverageMetric::METRIC_METHOD->value => $this->thresholds->method,
                CoverageMetric::METRIC_CLASS->value => $this->thresholds->class,
            ],
            'passed' => $this->passed,
            'deficit' => $this->deficit,
        ];
    }
}
