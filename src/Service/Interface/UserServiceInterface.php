<?php

declare(strict_types=1);

namespace App\Service\Interface;

use App\Entity\Agent;
use App\Entity\User;
use Symfony\Component\Uid\Uuid;

interface UserServiceInterface
{
    public function create(array $user): User;

    public function get(Uuid $id): User;

    public function findOneBy(array $params): User;

    public function findAll(): array;

    public function update(Uuid $id, array $user, ?string $browserUserAgent = null): User;

    public function authenticate(User $user, $password): bool;

    public function getMainAgent(User $user): Agent;

    public function confirmAccount(Uuid $id): void;
}
