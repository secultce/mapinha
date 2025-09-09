<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Helper\EntityIdNormalizerHelper;
use App\Request\Query\Filters;
use App\Service\Interface\SpaceServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Uid\Uuid;

class SpaceApiController extends AbstractApiController
{
    public function __construct(
        private readonly SpaceServiceInterface $service,
    ) {
    }

    public function create(Request $request): JsonResponse
    {
        $space = $this->service->create($request->toArray());

        return $this->json($space, status: Response::HTTP_CREATED, context: ['groups' => ['space.get', 'space.get.item']]);
    }

    public function get(?Uuid $id): JsonResponse
    {
        $space = $this->service->get($id);

        return $this->json($space, context: ['groups' => ['space.get', 'space.get.item']]);
    }

    public function list(Filters $filters): JsonResponse
    {
        $spaces = $this->service->list(params: array_merge($filters->toArray(), ['isDraft' => false]));

        return $this->json($spaces, context: [
            'groups' => 'space.get',
            AbstractNormalizer::CALLBACKS => [
                'parent' => [EntityIdNormalizerHelper::class, 'normalizeEntityId'],
            ],
        ]);
    }

    public function remove(?Uuid $id): JsonResponse
    {
        $this->service->remove($id);

        return $this->json(data: [], status: Response::HTTP_NO_CONTENT);
    }

    public function update(?Uuid $id, Request $request): JsonResponse
    {
        $space = $this->service->update($id, $request->toArray());

        return $this->json($space, Response::HTTP_OK, context: ['groups' => ['space.get', 'space.get.item']]);
    }

    public function updateImage(Uuid $id, Request $request): JsonResponse
    {
        $image = $request->files->get('image');

        $space = $this->service->updateImage($id, $image);

        return $this->json($space, context: ['groups' => ['space.get', 'space.get.item']]);
    }
}
