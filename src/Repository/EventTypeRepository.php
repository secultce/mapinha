<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\EventType;
use App\Repository\Interface\EventTypeRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

class EventTypeRepository extends AbstractRepository implements EventTypeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventType::class);
    }

    public function save(EventType $eventType): EventType
    {
        $this->getEntityManager()->persist($eventType);
        $this->getEntityManager()->flush();

        return $eventType;
    }
}
