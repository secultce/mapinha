<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Repository\Interface\UserRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $user): User
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();

        return $user;
    }

    public function beginTransaction(): void
    {
        $this->getEntityManager()->beginTransaction();
    }

    public function commit(): void
    {
        $this->getEntityManager()->commit();
    }

    public function rollback(): void
    {
        $this->getEntityManager()->rollback();
    }

    public function findOneByRole(string $role): ?User
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = <<<SQL
            SELECT id FROM app_user
            WHERE roles @> :role
            AND deleted_at IS NULL
            LIMIT 1
            SQL;

        $result = $connection->executeQuery($sql, [
            'role' => json_encode([$role]),
        ]);

        $id = $result->fetchOne();

        return $id ? $this->find($id) : null;
    }
}
