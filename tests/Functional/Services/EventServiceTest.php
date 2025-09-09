<?php

declare(strict_types=1);

namespace App\Tests\Functional\Services;

use App\DataFixtures\Entity\AgentFixtures;
use App\DataFixtures\Entity\EventFixtures;
use App\Service\Interface\EventServiceInterface;
use App\Tests\AbstractKernelTestCase;
use Symfony\Component\Uid\Uuid;

final class EventServiceTest extends AbstractKernelTestCase
{
    private EventServiceInterface $service;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->service = self::getEventService();
    }

    public function testFindByAgent(): void
    {
        $events = $this->service->findByAgent(AgentFixtures::AGENT_ID_2);

        self::assertIsArray($events);
        self::assertCount(3, $events);
        self::assertEquals(EventFixtures::EVENT_ID_9, $events[0]->getId()->toRfc4122());
        self::assertEquals(EventFixtures::EVENT_ID_8, $events[1]->getId()->toRfc4122());
        self::assertEquals(EventFixtures::EVENT_ID_7, $events[2]->getId()->toRfc4122());
    }

    public function testTogglePublish(): void
    {
        self::loginUser();

        $event = $this->service->get(Uuid::fromRfc4122(EventFixtures::EVENT_ID_1));
        self::assertFalse($event->isDraft());

        $this->service->togglePublish(Uuid::fromRfc4122(EventFixtures::EVENT_ID_1));

        $event = $this->service->get(Uuid::fromRfc4122(EventFixtures::EVENT_ID_1));
        self::assertTrue($event->isDraft());
    }

    private function getEventService(): EventServiceInterface
    {
        return self::getContainer()->get(EventServiceInterface::class);
    }
}
