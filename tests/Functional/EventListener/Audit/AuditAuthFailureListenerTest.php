<?php

declare(strict_types=1);

namespace App\Tests\Functional\EventListener\Audit;

use App\Document\AuthTimeline;
use App\Entity\User;
use App\Tests\AbstractApiTestCase;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditAuthFailureListenerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/login';

    private ?DocumentManager $documentManager = null;
    private ?EntityManager $entityManager = null;

    protected function setUp(): void
    {
        parent::setUp();

        $client = static::createClient();
        $container = $client->getContainer();

        $this->documentManager = $container->get(DocumentManager::class);
        $this->entityManager = $container->get(EntityManagerInterface::class);
    }

    public function testAuditLoginFailedInvalidCredentials(): void
    {
        $requestBody = [
            'username' => 'henriquelopeslima@example.com',
            'password' => 'invalid',
        ];

        if (null !== static::$kernel) {
            static::ensureKernelShutdown();
        }

        $client = self::createClient();
        $client->request(Request::METHOD_POST, self::BASE_URL, server: [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ], content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        self::assertResponseBodySame([
            'message' => 'unauthorized',
            'details' => [
                'The credentials you provided are invalid. Please try again.',
            ],
        ]);

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $requestBody['username']]);

        $authTimeline = $this->documentManager->getRepository(AuthTimeline::class)
            ->findOneBy(['action' => 'login failure', 'platform' => 'unknown', 'userId' => $user->getId()->toRfc4122()]);

        self::assertNotNull($authTimeline, 'Audit document not found');

        $this->documentManager->remove($authTimeline);
        $this->documentManager->flush();
    }

    public function testAuditLoginFailedForUserNotFound(): void
    {
        $requestBody = [
            'username' => 'henrique@example.com',
            'password' => 'invalid',
        ];

        if (null !== static::$kernel) {
            static::ensureKernelShutdown();
        }

        $client = self::createClient();
        $client->request(Request::METHOD_POST, self::BASE_URL, server: [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ], content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);

        self::assertResponseBodySame([
            'message' => 'unauthorized',
            'details' => [
                'The credentials you provided are invalid. Please try again.',
            ],
        ]);

        $authTimeline = $this->documentManager->getRepository(AuthTimeline::class)
            ->findOneBy(['action' => 'user not found', 'platform' => 'unknown', 'userId' => null]);

        self::assertNotNull($authTimeline, 'Audit document not found');

        $this->documentManager->remove($authTimeline);
        $this->documentManager->flush();
    }
}
