<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Helper\EntityIdNormalizerHelper;
use App\Helper\SocialNetworksNormalizerHelper;
use App\Service\Interface\AgentServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Uid\Uuid;

class AgentApiController extends AbstractApiController
{
    public function __construct(
        private readonly AgentServiceInterface $service,
    ) {
    }

    public function create(Request $request): JsonResponse
    {
        $agent = $this->service->create($request->toArray());

        return $this->json($agent, status: Response::HTTP_CREATED, context: ['groups' => ['agent.get', 'agent.get.item']]);
    }

    public function get(Uuid $id): JsonResponse
    {
        $agent = $this->service->get($id);

        return $this->json($agent, context: [
            'groups' => ['agent.get', 'agent.get.item'],
            AbstractNormalizer::CALLBACKS => [
                'socialNetworks' => [SocialNetworksNormalizerHelper::class, 'normalizeSocialNetworks'],
            ],
        ]);
    }

    public function list(): JsonResponse
    {
        return $this->json($this->service->list(), context: [
            'groups' => 'agent.get',
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
        $agent = $this->service->update($id, $request->toArray());

        return $this->json($agent, context: ['groups' => ['agent.get', 'agent.get.item']]);
    }

    public function updateImage(Uuid $id, Request $request): JsonResponse
    {
        $image = $request->files->get('image');

        $agent = $this->service->updateImage($id, $image);

        return $this->json($agent, context: ['groups' => ['agent.get', 'agent.get.item']]);
    }
}
