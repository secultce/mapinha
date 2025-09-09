<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Initiative;
use App\Repository\Interface\InitiativeRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

class InitiativeRepository extends AbstractRepository implements InitiativeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Initiative::class);
    }

    public function save(Initiative $initiative): Initiative
    {
        $this->getEntityManager()->persist($initiative);
        $this->getEntityManager()->flush();

        return $initiative;
    }

    public function findByFilters(?string $region, ?string $state, ?string $cityName, ?string $status): array
    {
        $connection = $this->getEntityManager()->getConnection();
        $queryBuilder = $connection->createQueryBuilder()
            ->select('i.id')
            ->from('initiative', 'i')
            ->orderBy('i.created_at', 'DESC');

        if ($region) {
            $queryBuilder->andWhere("i.extra_fields->>'region' = :region")
                ->setParameter('region', $region);
        }

        if ($state) {
            $queryBuilder->andWhere("i.extra_fields->>'state' = :state")
                ->setParameter('state', $state);
        }

        if ($cityName) {
            $queryBuilder->andWhere("i.extra_fields->>'city_name' = :cityName")
                ->setParameter('cityName', $cityName);
        }

        if ($status) {
            $queryBuilder->andWhere("i.extra_fields->>'status' = :status")
                ->setParameter('status', $status);
        }

        $ids = $queryBuilder->executeQuery()->fetchFirstColumn();
        if (empty($ids)) {
            return [];
        }

        return $this->findBy(
            ['id' => $ids],
            ['createdAt' => 'DESC']
        );
    }
}
