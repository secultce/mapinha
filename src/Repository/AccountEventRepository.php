<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AccountEvent;
use App\Repository\Interface\AccountEventRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

class AccountEventRepository extends AbstractRepository implements AccountEventRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountEvent::class);
    }

    public function save(AccountEvent $accountEvent): AccountEvent
    {
        $this->getEntityManager()->persist($accountEvent);
        $this->getEntityManager()->flush();

        return $accountEvent;
    }
}
