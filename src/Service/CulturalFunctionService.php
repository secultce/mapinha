<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\CulturalFunction;
use App\Exception\CulturalFunction\CulturalFunctionResourceNotFoundException;
use App\Repository\Interface\CulturalFunctionRepositoryInterface;
use App\Service\Interface\CulturalFunctionServiceInterface;
use Symfony\Component\Uid\Uuid;

readonly class CulturalFunctionService implements CulturalFunctionServiceInterface
{
    public function __construct(
        private CulturalFunctionRepositoryInterface $repository,
    ) {
    }

    public function get(Uuid $id): CulturalFunction
    {
        $culturalFunction = $this->repository->findOneBy(['id' => $id]);

        if (null === $culturalFunction) {
            throw new CulturalFunctionResourceNotFoundException();
        }

        return $culturalFunction;
    }

    public function list(int $limit = 50): array
    {
        return $this->repository->findBy(
            [],
            ['name' => 'ASC'],
            $limit
        );
    }
}
