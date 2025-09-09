<?php

declare(strict_types=1);

namespace App\Service\Interface;

use App\Entity\Agent;
use App\Entity\Organization;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

interface OrganizationServiceInterface
{
    public function count(?Agent $createdBy = null): int;

    public function create(array $organization): Organization;

    public function get(Uuid $id): Organization;

    public function findOneBy(array $params): ?Organization;

    public function findBy(array $params = []): array;

    public function list(int $limit = 50): array;

    public function remove(Uuid $id): void;

    public function removeAgent(Uuid $agentId, Uuid $organizationId): void;

    public function update(Uuid $identifier, array $organization): Organization;

    public function updateImage(Uuid $id, UploadedFile $uploadedFile): Organization;

    public function getCsvHeaders(?string $type): array;

    public function getCsvRow(object $entity, ?string $type): array;
}
