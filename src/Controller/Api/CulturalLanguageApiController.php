<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Helper\EntityIdNormalizerHelper;
use App\Service\CulturalLanguageService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Uid\Uuid;

class CulturalLanguageApiController extends AbstractApiController
{
    public function __construct(
        private readonly CulturalLanguageService $service,
    ) {
    }

    public function list(): JsonResponse
    {
        return $this->json($this->service->list(), context: [
            'groups' => 'culturalLanguage.get',
            AbstractNormalizer::CALLBACKS => [
                'parent' => [EntityIdNormalizerHelper::class, 'normalizeEntityId'],
            ],
        ]);
    }

    public function get(?Uuid $id): JsonResponse
    {
        $culturalLanguage = $this->service->get($id);

        return $this->json($culturalLanguage, context: ['groups' => ['cultural-language.get', 'cultural-language.get.item']]);
    }

    public function remove(?Uuid $id): JsonResponse
    {
        $this->service->remove($id);

        return $this->json(data: [], status: Response::HTTP_NO_CONTENT);
    }

    public function create(Request $request): JsonResponse
    {
        $culturalLanguage = $this->service->create($request->toArray());

        return $this->json($culturalLanguage, Response::HTTP_CREATED, context: ['groups' => ['cultural-language.get', 'cultural-language.get.item']]);
    }

    public function update(?Uuid $id, Request $request): JsonResponse
    {
        $culturalLanguage = $this->service->update($id, $request->toArray());

        return $this->json($culturalLanguage, Response::HTTP_OK, context: ['groups' => ['cultural-language.get', 'cultural-language.get.item']]);
    }
}
