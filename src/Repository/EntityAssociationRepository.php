<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\EntityAssociation;
use App\Repository\Interface\EntityAssociationRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

class EntityAssociationRepository extends AbstractRepository implements EntityAssociationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EntityAssociation::class);
    }

    public function save(EntityAssociation $entityAssociation): EntityAssociation
    {
        $this->getEntityManager()->persist($entityAssociation);
        $this->getEntityManager()->flush();

        return $entityAssociation;
    }
}
