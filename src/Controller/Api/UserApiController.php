<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Log\Interface\EmailLoggerInterface;
use App\Service\Interface\AccountEventServiceInterface;
use App\Service\Interface\UserServiceInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class UserApiController extends AbstractApiController
{
    public function __construct(
        private readonly UserServiceInterface $service,
        private readonly AccountEventServiceInterface $accountEventService,
        private readonly EmailLoggerInterface $logger,
    ) {
    }

    public function create(Request $request): JsonResponse
    {
        $user = $this->service->create($request->toArray());

        try {
            $this->accountEventService->sendConfirmationEmail($user);
        } catch (Exception $e) {
            $this->logger->logEmailNotSent($user->getEmail(), $e->getMessage());
        }

        return $this->json($user, status: Response::HTTP_CREATED, context: ['groups' => 'user.get']);
    }

    public function update(Uuid $id, Request $request): JsonResponse
    {
        $user = $this->service->update($id, $request->toArray());

        return $this->json($user, context: ['groups' => 'user.get']);
    }

    public function exists(Request $request): JsonResponse
    {
        $email = $request->query->get('email');

        if (!$email) {
            return $this->json(
                ['error' => 'email não informado.'],
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $user = $this->service->findOneBy(['email' => $email]);
            $exists = null !== $user;
        } catch (Exception) {
            $exists = false;
        }

        return $this->json([
            'exists' => $exists,
        ]);
    }
}
