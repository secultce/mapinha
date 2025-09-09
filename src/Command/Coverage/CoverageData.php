<?php

declare(strict_types=1);

namespace App\Command\Coverage;

use App\Command\Coverage\Enum\CoverageMetric;

readonly class CoverageData
{
    public function __construct(
        public float $lineCoverage,
        public int $coveredLines,
        public int $totalLines,
        public float $branchCoverage,
        public int $coveredBranches,
        public int $totalBranches,
        public float $pathCoverage,
        public int $coveredPaths,
        public int $totalPaths,
        public float $methodCoverage,
        public int $coveredMethods,
        public int $totalMethods,
        public float $classCoverage,
        public int $coveredClasses,
        public int $totalClasses
    ) {
    }

    public function getCoverage(CoverageMetric $metric): float
    {
        return match ($metric) {
            CoverageMetric::METRIC_LINE => $this->lineCoverage,
            CoverageMetric::METRIC_BRANCH => $this->branchCoverage,
            CoverageMetric::METRIC_PATH => $this->pathCoverage,
            CoverageMetric::METRIC_METHOD => $this->methodCoverage,
            CoverageMetric::METRIC_CLASS => $this->classCoverage
        };
    }

    public function getCovered(CoverageMetric $metric): int
    {
        return match ($metric) {
            CoverageMetric::METRIC_LINE => $this->coveredLines,
            CoverageMetric::METRIC_BRANCH => $this->coveredBranches,
            CoverageMetric::METRIC_PATH => $this->coveredPaths,
            CoverageMetric::METRIC_METHOD => $this->coveredMethods,
            CoverageMetric::METRIC_CLASS => $this->coveredClasses
        };
    }

    public function getTotal(CoverageMetric $metric): int
    {
        return match ($metric) {
            CoverageMetric::METRIC_LINE => $this->totalLines,
            CoverageMetric::METRIC_BRANCH => $this->totalBranches,
            CoverageMetric::METRIC_PATH => $this->totalPaths,
            CoverageMetric::METRIC_METHOD => $this->totalMethods,
            CoverageMetric::METRIC_CLASS => $this->totalClasses
        };
    }
}
