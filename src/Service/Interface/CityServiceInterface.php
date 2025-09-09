<?php

declare(strict_types=1);

namespace App\Service\Interface;

use App\Entity\City;
use App\Entity\State;
use Symfony\Component\Uid\Uuid;

interface CityServiceInterface
{
    public function findBy(array $params = []): array;

    public function findByState(State|string $state): array;

    public function findOneBy(array $params): ?City;

    public function list(int $limit = 50): array;

    public function get(Uuid|string $id): ?City;
}
