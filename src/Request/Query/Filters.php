<?php

declare(strict_types=1);

namespace App\Request\Query;

final readonly class Filters
{
    public function __construct(private array $value)
    {
    }

    public function toArray(): array
    {
        return $this->value;
    }
}
