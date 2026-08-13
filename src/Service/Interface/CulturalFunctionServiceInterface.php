<?php

declare(strict_types=1);

namespace App\Service\Interface;

use App\Entity\CulturalFunction;
use Symfony\Component\Uid\Uuid;

interface CulturalFunctionServiceInterface
{
    public function get(Uuid $id): CulturalFunction;

    public function list(int $limit = 50): array;
}
