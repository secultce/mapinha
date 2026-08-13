<?php

declare(strict_types=1);

namespace App\Service\Interface;

use App\Entity\Agent;
use App\Entity\Space;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

interface SpaceServiceInterface
{
    public function create(array $space): Space;

    public function get(Uuid $id): Space;

    public function findOneBy(array $params): Space;

    public function findBy(array $params = []): array;

    public function list(int $limit = 50, array $params = [], string $order = 'DESC'): array;

    public function count(?Agent $createdBy = null): int;

    public function remove(Uuid $id): void;

    public function update(Uuid $identifier, array $space): Space;

    public function updateImage(Uuid $id, UploadedFile $uploadedFile): Space;

    public function togglePublish(Uuid $id): void;

    public function addPortfolioImage(Space $space, UploadedFile $uploadedFile, ?string $description = null): Space;

    public function removePortfolioImage(Uuid $spaceId, Uuid $photoId): Space;
}
