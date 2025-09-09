<?php

declare(strict_types=1);

namespace App\Tests\Functional\EventListener\Audit;

use App\DataFixtures\Entity\UserFixtures;
use App\Document\AuthTimeline;
use App\Entity\User;
use App\Tests\AbstractApiTestCase;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditAuthSuccessListenerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/login';

    private ?DocumentManager $documentManager = null;
    private ?EntityManager $entityManager = null;

    protected function setUp(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $this->documentManager = $container->get(DocumentManager::class);
        $this->entityManager = $container->get(EntityManagerInterface::class);
    }

    public function testAuditLoginSuccess(): void
    {
        $requestBody = [
            'username' => 'alessandrofeitoza@example.com',
            'password' => UserFixtures::DEFAULT_PASSWORD,
        ];

        if (null !== static::$kernel) {
            static::ensureKernelShutdown();
        }

        $client = self::createClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, server: [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ], content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseArray = self::getCurrentResponseArray();

        self::assertArrayHasKey('token', $responseArray);
        self::assertSame($requestBody['username'], $responseArray['user']);

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $requestBody['username']]);

        $authTimeline = $this->documentManager->getRepository(AuthTimeline::class)
            ->findOneBy(['action' => 'login success', 'platform' => 'unknown', 'userId' => $user->getId()->toRfc4122()]);

        self::assertNotNull($authTimeline, 'Auth document not found');

        $this->documentManager->remove($authTimeline);
        $this->documentManager->flush();
    }
}
