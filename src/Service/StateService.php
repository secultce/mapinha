<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\City;
use App\Entity\State;
use App\Exception\State\StateResourceNotFoundException;
use App\Repository\Interface\StateRepositoryInterface;
use App\Service\Interface\StateServiceInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Uid\Uuid;

readonly class StateService extends AbstractEntityService implements StateServiceInterface
{
    public function __construct(
        private StateRepositoryInterface $repository,
        private Security $security,
    ) {
        parent::__construct($this->security);
    }

    public function findBy(array $params = []): array
    {
        return $this->repository->findBy($params);
    }

    public function findByCity(City|Uuid $city): ?State
    {
        return $this->repository->findOneBy(['city' => $city]);
    }

    public function findOneBy(array $params): ?State
    {
        $state = $this->repository->findOneBy($params);

        if (null === $state) {
            throw new StateResourceNotFoundException();
        }

        return $state;
    }

    public function list(int $limit = 50): array
    {
        return $this->repository->findBy([], ['name' => 'ASC'], $limit);
    }
}
