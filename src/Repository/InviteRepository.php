<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Invite;
use App\Repository\Interface\InviteRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

class InviteRepository extends AbstractRepository implements InviteRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invite::class);
    }

    public function save(Invite $invite): Invite
    {
        $this->getEntityManager()->persist($invite);
        $this->getEntityManager()->flush();

        return $invite;
    }
}
