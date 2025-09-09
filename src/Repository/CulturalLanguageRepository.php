<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CulturalLanguage;
use App\Repository\Interface\CulturalLanguageRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

class CulturalLanguageRepository extends AbstractRepository implements CulturalLanguageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CulturalLanguage::class);
    }

    public function save(CulturalLanguage $culturalLanguage): CulturalLanguage
    {
        $this->getEntityManager()->persist($culturalLanguage);
        $this->getEntityManager()->flush();

        return $culturalLanguage;
    }

    public function remove(CulturalLanguage $culturalLanguage): void
    {
        $this->getEntityManager()->remove($culturalLanguage);
        $this->getEntityManager()->flush();
    }
}
