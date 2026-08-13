<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Opportunity;
use App\Repository\Interface\OpportunityRepositoryInterface;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;

class OpportunityRepository extends AbstractRepository implements OpportunityRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Opportunity::class);
    }

    public function save(Opportunity $opportunity): Opportunity
    {
        $this->getEntityManager()->persist($opportunity);
        $this->getEntityManager()->flush();

        return $opportunity;
    }

    public function countRecentOpportunities(DateTime $startDate): int
    {
        return $this->createQueryBuilder('o')
            ->select('COUNT(o.id)')
            ->where('o.createdAt >= :startDate')
            ->andWhere('o.deletedAt IS NULL')
            ->setParameter('startDate', $startDate)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countOpenedOpportunities(): int
    {
        $now = new DateTime();

        return $this->createQueryBuilder('o')
            ->select('COUNT(DISTINCT o.id)')
            ->leftJoin('o.phases', 'p')
            ->where('o.deletedAt IS NULL')
            ->andWhere(
                'p.id IS NULL OR (p.startDate <= :now AND p.endDate >= :now AND p.status = true)'
            )
            ->setParameter('now', $now)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countFinishedOpportunities(): int
    {
        $now = new DateTime();

        return (int) $this->createQueryBuilder('o')
            ->select('COUNT(DISTINCT o.id)')
            ->innerJoin('o.phases', 'p')
            ->where('o.deletedAt IS NULL')
            ->andWhere('p.endDate < :now')
            ->setParameter('now', $now)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
