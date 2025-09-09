<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Helper\EntityIdNormalizerHelper;
use App\Request\Query\Filters;
use App\Service\Interface\EventServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Uid\Uuid;

class EventApiController extends AbstractApiController
{
    public function __construct(
        public readonly EventServiceInterface $service,
    ) {
    }

    public function create(Request $request): JsonResponse
    {
        $event = $this->service->create($request->toArray());

        return $this->json($event, status: Response::HTTP_CREATED, context: ['groups' => ['event.get', 'event.get.item']]);
    }

    public function get(Uuid $id): JsonResponse
    {
        $event = $this->service->get($id);

        return $this->json($event, context: ['groups' => ['event.get', 'event.get.item']]);
    }

    public function list(Filters $filters): JsonResponse
    {
        $events = $this->service->list(params: array_merge($filters->toArray(), ['draft' => false]));

        return $this->json($events, context: [
            'groups' => 'event.get',
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

    public function update(Uuid $id, Request $request): JsonResponse
    {
        $event = $this->service->update($id, $request->toArray());

        return $this->json($event, Response::HTTP_OK, context: ['groups' => ['event.get', 'event.get.item']]);
    }

    public function updateImage(Uuid $id, Request $request): JsonResponse
    {
        $event = $this->service->updateImage($id, $request->files->get('image'));

        return $this->json($event, Response::HTTP_OK, context: ['groups' => ['event.get', 'event.get.item']]);
    }
}
