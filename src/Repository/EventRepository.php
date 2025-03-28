<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Event;
use App\Repository\Interface\EventRepositoryInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

class EventRepository extends AbstractRepository implements EventRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    public function findByFilters(array $filters, array $order = [], int $limit = 50): array
    {
        $qb = $this->createQueryBuilder('e');
        if (!empty($filters['name'])) {
            $qb->andWhere('lower(e.name) LIKE lower(:name)')
                ->setParameter('name', "%{$filters['name']}%");
            unset($filters['name']);
        }

        if (!empty($filters['period']['start']) && !empty($filters['period']['end'])) {
            $qb->leftJoin('e.eventSchedules', 'es')
                ->andWhere('es.startHour BETWEEN :startPeriod AND :endPeriod')
                ->setParameter('startPeriod', $filters['period']['start'], Types::DATETIME_IMMUTABLE)
                ->setParameter('endPeriod', $filters['period']['end'], Types::DATETIME_IMMUTABLE);
            unset($filters['period']);
        }

        foreach ($filters as $key => $value) {
            $paramName = $key;
            $expr = match (true) {
                is_array($value) => "e.$key IN (:$paramName)",
                is_null($value) => "e.$key IS NULL",
                is_bool($value) => "e.$key is :$paramName",
                default => "e.$key = :$paramName",
            };

            $qb->andWhere($expr);

            if (null !== $value) {
                $qb->setParameter($paramName, $value, type: is_bool($value) ? Types::BOOLEAN : null);
            }
        }

        foreach ($order as $key => $value) {
            $qb->addOrderBy("e.$key", $value);
        }

        return $qb
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function save(Event $event): Event
    {
        $this->getEntityManager()->persist($event);
        $this->getEntityManager()->flush();

        return $event;
    }
}
