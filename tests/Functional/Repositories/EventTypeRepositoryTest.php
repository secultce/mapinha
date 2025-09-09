<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repositories;

use App\Entity\EventType;
use App\Repository\EventTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class EventTypeRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private EventTypeRepository $eventTypeRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->eventTypeRepository = $this->entityManager->getRepository(EventType::class);
    }

    public function testSaveEventType(): void
    {
        $eventType = new EventType();
        $eventType->setId(Uuid::v4());
        $eventType->setName('Test Event Type');

        $this->eventTypeRepository->save($eventType);

        $this->entityManager->clear();
        $foundEventType = $this->eventTypeRepository->find($eventType->getId());
        $this->assertNotNull($foundEventType);
        $this->assertEquals('Test Event Type', $foundEventType->getName());
    }
}
