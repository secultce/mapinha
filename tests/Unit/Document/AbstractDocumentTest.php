<?php

declare(strict_types=1);

namespace App\Tests\Unit\Document;

use App\DataFixtures\Entity\UserFixtures;
use App\Document\AbstractDocument;
use App\Document\AgentTimeline;
use App\Entity\User;
use App\Tests\AbstractApiTestCase;
use DateTime;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Uid\Uuid;

class AbstractDocumentTest extends AbstractApiTestCase
{
    private DocumentManager $documentManager;

    public function setUp(): void
    {
        $this->documentManager = self::bootKernel()->getContainer()->get('doctrine_mongodb')->getManager();
    }

    public function testAuthorAssigned(): void
    {
        $document = $this->createAbstractClass();

        $documentTest = new $document();

        $author = $this->createPartialMock(User::class, ['toArray']);
        $author->setId(Uuid::v4());
        $author->setSocialName('Alessandro Feitoza');
        $author->method('toArray')->willReturn(['name' => 'Alessandro Feitoza']);

        $documentTest->author = $author;

        $this->assertEquals('Alessandro Feitoza', $documentTest->author->getSocialName());

        $this->assertIsArray($documentTest->toArray());
    }

    public function testToArray(): void
    {
        $document = $this->createAgentEvent();

        $this->assertEquals([
            'author' => null,
            'id' => '67d71508a78241dbf60f58bd',
            'userId' => '2604e656-57dc-4e1c-9fa8-efdf4a00b203',
            'resourceId' => 'f457b5a-4710-446b-a119-3980dd32f870',
            'title' => 'Título',
            'priority' => 1,
            'datetime' => new DateTime('2025-02-21T00:00:00Z'),
            'device' => 'smartphone',
            'platform' => 'android',
            'from' => ['name' => 'Fulano'],
            'to' => ['name' => 'Cicrano'],
        ], $document->toArray());
    }

    private function createAgentEvent(): AgentTimeline
    {
        $agentEvent = new AgentTimeline();
        $this->assertNull($agentEvent->getId());

        $resourceId = 'f457b5a-4710-446b-a119-3980dd32f870';
        $userId = UserFixtures::USER_ID_1;
        $title = 'Título';
        $priority = 1;
        $datetime = new DateTime('20250221T00:00:00Z');
        $device = 'smartphone';
        $platform = 'android';
        $from = ['name' => 'Fulano'];
        $to = ['name' => 'Cicrano'];

        $agentEvent->setId('67d71508a78241dbf60f58bd');
        $agentEvent->setUserId($userId);
        $agentEvent->setResourceId($resourceId);
        $agentEvent->setTitle($title);
        $agentEvent->setPriority($priority);
        $agentEvent->setDatetime($datetime);
        $agentEvent->setDevice($device);
        $agentEvent->setPlatform($platform);
        $agentEvent->setFrom($from);
        $agentEvent->setTo($to);

        return $agentEvent;
    }

    private function createAbstractClass(): AbstractDocument
    {
        return new class extends AbstractDocument {
            public function getId(): string
            {
                return '123';
            }

            public function getUserId(): string
            {
                return UserFixtures::USER_ID_1;
            }

            public function getResourceId(): string
            {
                return '789';
            }

            public function getTitle(): string
            {
                return 'Title';
            }

            public function getPriority(): int
            {
                return 1;
            }

            public function getDatetime(): string
            {
                return '2025-02-21T00:00:00Z';
            }

            public function getDevice(): string
            {
                return 'smartphone';
            }

            public function getPlatform(): string
            {
                return 'android';
            }

            public function getFrom(): array
            {
                return ['name' => 'Fulano'];
            }

            public function getTo(): array
            {
                return ['name' => 'Cicrano'];
            }
        };
    }
}
