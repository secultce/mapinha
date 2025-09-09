<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Helper\EntityIdNormalizerHelper;
use App\Service\Interface\CityServiceInterface;
use App\Service\Interface\StateServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Uid\Uuid;

class StateApiController extends AbstractApiController
{
    public function __construct(
        private readonly StateServiceInterface $stateService,
        private readonly CityServiceInterface $cityService,
    ) {
    }

    public function list(): JsonResponse
    {
        return $this->json($this->stateService->list(), context: [
            'groups' => 'state.get',
            AbstractNormalizer::CALLBACKS => [
                'parent' => [EntityIdNormalizerHelper::class, 'normalizeEntityId'],
            ],
        ]);
    }

    public function listByRegion(string $region): JsonResponse
    {
        return $this->json($this->stateService->findBy([
            'region' => $region,
        ]), context: [
            'groups' => 'state.get',
            AbstractNormalizer::CALLBACKS => [
                'parent' => [EntityIdNormalizerHelper::class, 'normalizeEntityId'],
            ],
        ]);
    }

    public function listCitiesByState(string $state): JsonResponse
    {
        $state = $this->stateService->findOneBy(['id' => Uuid::fromString($state)]);

        return $this->json($this->cityService->findByState($state), context: [
            'groups' => 'city.get',
        ]);
    }
}
