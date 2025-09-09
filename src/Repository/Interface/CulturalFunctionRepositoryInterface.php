<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\CulturalFunction;

interface CulturalFunctionRepositoryInterface
{
    public function save(CulturalFunction $culturalFunction): CulturalFunction;
}
