<?php

declare(strict_types=1);

namespace App\Service\Interface;

use App\Entity\Phase;
use Symfony\Component\Uid\Uuid;

interface PhaseServiceInterface
{
    public function create(Uuid $opportunity, array $phase): Phase;

    public function get(Uuid $opportunity, Uuid $id);

    public function list(Uuid $opportunity, int $limit = 50): array;

    public function remove(Uuid $opportunity, Uuid $id): void;

    public function update(Uuid $opportunity, Uuid $identifier, array $phase): Phase;

    public function isPhaseActive(Uuid $opportunityId): bool;
}
