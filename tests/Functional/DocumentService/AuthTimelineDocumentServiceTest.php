<?php

declare(strict_types=1);

namespace App\Tests\Functional\DocumentService;

use App\DataFixtures\Entity\UserFixtures;
use App\DocumentService\Interface\AuthTimelineDocumentServiceInterface;
use App\Repository\UserRepository;
use App\Tests\AbstractApiTestCase;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthTimelineDocumentServiceTest extends AbstractApiTestCase
{
    public function testGetTimelineLoginByUser(): void
    {
        $client = static::createClient();
        $authTimelineDocumentService = $client->getContainer()->get(AuthTimelineDocumentServiceInterface::class);
        $user = $client->getContainer()->get(UserRepository::class)->findOneByEmail('talysonsoares@example.com');

        $requestBody = [
            'username' => 'talysonsoares@example.com',
            'password' => UserFixtures::DEFAULT_PASSWORD,
        ];

        $client->request(Request::METHOD_POST, '/api/login', server: [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ], content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $timeline = $authTimelineDocumentService->getTimelineLoginByUserId($user->getId());
        $this->assertIsArray($timeline);
        $this->assertGreaterThanOrEqual(1, count($timeline));

        foreach ($timeline as $entry) {
            $this->assertObjectHasProperty('userId', $entry);
            $this->assertObjectHasProperty('action', $entry);
            $this->assertObjectHasProperty('datetime', $entry);
            $this->assertEquals($user->getId(), $entry->getUserId());
            $this->assertEquals('login success', $entry->getAction());
            $this->assertInstanceOf(DateTime::class, $entry->getDatetime());
        }
    }
}
