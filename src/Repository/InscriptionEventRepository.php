<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\InscriptionEvent;
use App\Repository\Interface\InscriptionEventRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

final class InscriptionEventRepository extends AbstractRepository implements InscriptionEventRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InscriptionEvent::class);
    }

    public function findMyInscriptions(string $agent, int $limit): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return $qb->select('ie')
            ->from(InscriptionEvent::class, 'ie')
            ->where('ie.agent = :agent')
            ->setParameter('agent', $agent)
            ->setMaxResults($limit)
            ->orderBy('ie.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findInscriptionsByEvent(string $eventId, int $limit): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return $qb->select('ie')
            ->from(InscriptionEvent::class, 'ie')
            ->where('ie.event = :eventId')
            ->setParameter('eventId', $eventId)
            ->setMaxResults($limit)
            ->orderBy('ie.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOneInscriptionEvent(string $inscriptionId, string $eventId): ?InscriptionEvent
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        return $qb->select('ie')
            ->from(InscriptionEvent::class, 'ie')
            ->where('ie.id = :inscriptionId')
            ->andWhere('ie.event = :eventId')
            ->setParameter('inscriptionId', $inscriptionId)
            ->setParameter('eventId', $eventId)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function save(InscriptionEvent $inscriptionEvent): InscriptionEvent
    {
        $this->getEntityManager()->persist($inscriptionEvent);
        $this->getEntityManager()->flush();

        return $inscriptionEvent;
    }
}
