<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Enum\InscriptionEventStatusEnum;
use App\Service\Interface\InscriptionEventServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Uid\Uuid;

final class InscriptionEventApiController extends AbstractApiController
{
    private array $context;

    public function __construct(private readonly InscriptionEventServiceInterface $service)
    {
        $this->context = [
            'groups' => ['inscription-event.get'],
            AbstractNormalizer::CALLBACKS => [
                'status' => fn ($value) => InscriptionEventStatusEnum::getName($value),
            ],
        ];
    }

    public function list(Uuid $event): JsonResponse
    {
        return $this->json($this->service->list($event), context: $this->context);
    }

    public function get(Uuid $event, Uuid $id): JsonResponse
    {
        return $this->json($this->service->get($event, $id), context: $this->context);
    }

    public function create(Uuid $event, Request $request): JsonResponse
    {
        $inscriptionEvent = $this->service->create($event, $request->toArray());

        return $this->json($inscriptionEvent, status: Response::HTTP_CREATED, context: $this->context);
    }

    public function remove(Uuid $event, ?Uuid $id): JsonResponse
    {
        $this->service->remove($event, $id);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }

    public function update(Uuid $event, ?Uuid $id, Request $request): JsonResponse
    {
        $inscriptionEvent = $this->service->update($event, $id, $request->toArray());

        return $this->json($inscriptionEvent, Response::HTTP_OK, context: $this->context);
    }
}
