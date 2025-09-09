<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CulturalFunction;
use App\Repository\Interface\CulturalFunctionRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

class CulturalFunctionRepository extends AbstractRepository implements CulturalFunctionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CulturalFunction::class);
    }

    public function save(CulturalFunction $culturalFunction): CulturalFunction
    {
        $this->getEntityManager()->persist($culturalFunction);
        $this->getEntityManager()->flush();

        return $culturalFunction;
    }
}
