<?php

declare(strict_types=1);

namespace App\Service\Interface;

use App\Entity\Agent;
use App\Entity\Initiative;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\ConstraintViolationList;

interface InitiativeServiceInterface
{
    public function create(array $initiative): Initiative|ConstraintViolationList;

    public function get(Uuid $id): Initiative;

    public function findOneBy(array $params): Initiative;

    public function findBy(array $params = []): array;

    public function list(int $limit = 50, array $params = [], string $order = 'DESC'): array;

    public function count(?Agent $createdBy = null): int;

    public function remove(Uuid $id): void;

    public function update(Uuid $id, array $initiative): Initiative;

    public function updateImage(Uuid $id, UploadedFile $uploadedFile): Initiative;

    public function countByStatus(string $status): int;
}
