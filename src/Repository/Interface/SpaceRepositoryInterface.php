<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\Space;
use App\Enum\EntityEnum;

interface SpaceRepositoryInterface
{
    public function save(Space $space): Space;

    public function findByNameAndEntityAssociation(?string $name, EntityEnum $entityAssociation, int $limit): array;

    public function findByFilters(array $filters, array $orderBy, int $limit): array;
}
