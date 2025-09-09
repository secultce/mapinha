<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\City;
use App\Entity\State;
use App\Repository\Interface\CityRepositoryInterface;
use App\Service\Interface\CityServiceInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Uuid;

readonly class CityService extends AbstractEntityService implements CityServiceInterface
{
    public function __construct(
        private Security $security,
        private CityRepositoryInterface $repository,
    ) {
        parent::__construct($this->security);
    }

    public function findBy(array $params = []): array
    {
        return $this->repository->findBy($params);
    }

    public function findByState(State|string $state): array
    {
        return $this->repository->findByState($state);
    }

    public function findOneBy(array $params): ?City
    {
        return $this->repository->findOneBy($params);
    }

    public function list(int $limit = 50): array
    {
        return $this->repository->findBy([], ['name' => 'ASC'], $limit);
    }

    public function get(Uuid|string $id): ?City
    {
        return $this->repository->find($id);
    }
}
