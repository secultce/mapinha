<?php

declare(strict_types=1);

namespace App\Command\Coverage;

use InvalidArgumentException;

class CoverageThresholds
{
    public function __construct(
        public ?float $line = null,
        public ?float $method = null,
        public ?float $branch = null,
        public ?float $class = null,
        public ?float $path = null
    ) {
        foreach (get_object_vars($this) as $metric => $threshold) {
            if (null === $threshold) {
                $this->{$metric} = (float) $_ENV[sprintf('%s_COVERAGE', strtoupper($metric))];
            }
        }

        $this->validateThresholds();
    }

    private function validateThresholds(): void
    {
        foreach (get_object_vars($this) as $threshold) {
            if ($threshold < 0 || $threshold > 100) {
                throw new InvalidArgumentException('Threshold must be between 0 and 100');
            }
        }
    }
}
