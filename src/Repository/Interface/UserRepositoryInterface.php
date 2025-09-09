<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): User;

    public function beginTransaction(): void;

    public function commit(): void;

    public function rollback(): void;

    public function findOneByRole(string $role): ?User;
}
