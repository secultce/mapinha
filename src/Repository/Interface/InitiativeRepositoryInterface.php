<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\Initiative;

interface InitiativeRepositoryInterface
{
    public function save(Initiative $initiative): Initiative;

    public function countByStatus(string $status): int;
}
