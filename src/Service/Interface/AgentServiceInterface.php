<?php

declare(strict_types=1);

namespace App\Service\Interface;

use App\Entity\Agent;
use App\Entity\User;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

interface AgentServiceInterface
{
    public function create(array $agent): Agent;

    public function createFromUser(array $user, ?array $extraFields = null): Agent;

    public function get(Uuid $id): Agent;

    public function getMainAgentByEmail(string $email): ?Agent;

    public function findOneBy(array $params): ?Agent;

    public function findBy(array $params = []): array;

    public function list(int $limit = 50, array $params = [], string $order = 'DESC'): array;

    public function remove(Uuid $id): void;

    public function update(Uuid $id, array $agent): Agent;

    public function updateImage(Uuid $id, UploadedFile $uploadedFile): Agent;

    public function count(?User $user = null): int;
}
