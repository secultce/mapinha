<?php

declare(strict_types=1);

namespace App\Tests;

use App\DataFixtures\Entity\UserFixtures;
use App\Repository\UserRepository;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

abstract class AbstractKernelTestCase extends KernelTestCase
{
    protected function loginUser(): void
    {
        $userRepository = self::getContainer()->get(UserRepository::class);
        $user = $userRepository->find(UserFixtures::USER_ID_1);

        if (!$user) {
            throw new RuntimeException('User fixture not found');
        }

        $tokenStorage = self::getContainer()->get('security.token_storage');
        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $tokenStorage->setToken($token);
    }
}
