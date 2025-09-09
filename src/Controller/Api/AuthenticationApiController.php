<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\User;
use App\Enum\UserStatusEnum;
use App\Exception\AccountEvent\AccountNotActivatedException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class AuthenticationApiController extends AbstractApiController
{
    public function __construct(
        private readonly JWTTokenManagerInterface $jwtManager
    ) {
    }

    public function login(): JsonResponse
    {
        $user = $this->getUser();

        if (null === $user) {
            return new JsonResponse([
                'message' => 'unauthorized',
                'details' => [
                    'The credentials you provided are invalid. Please try again.',
                ],
            ], Response::HTTP_UNAUTHORIZED);
        }

        /* @var $user User */
        if (UserStatusEnum::AWAITING_CONFIRMATION->value === $user->getStatus()) {
            throw new AccountNotActivatedException();
        }

        $token = $this->jwtManager->create($user);

        return new JsonResponse(['token' => $token, 'user' => $user->getUserIdentifier()]);
    }

    public function logout(Request $request, Security $security, EventDispatcherInterface $eventDispatcher): JsonResponse
    {
        $eventDispatcher->dispatch(new LogoutEvent($request, $security->getToken()));

        return new JsonResponse(['message' => 'Logged out successfully'], Response::HTTP_OK);
    }
}
