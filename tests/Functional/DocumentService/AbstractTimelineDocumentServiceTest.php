<?php

declare(strict_types=1);

namespace App\Tests\Functional\DocumentService;

use App\DataFixtures\Entity\OrganizationFixtures;
use App\Document\OrganizationTimeline;
use App\DocumentService\AbstractTimelineDocumentService;
use App\Service\Interface\UserServiceInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

final class AbstractTimelineDocumentServiceTest extends KernelTestCase
{
    private DocumentManager $documentManager;

    private UserServiceInterface $userService;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();
        $this->documentManager = $kernel->getContainer()->get('doctrine_mongodb')->getManager();
        $this->userService = self::getContainer()->get(UserServiceInterface::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->documentManager->close();
        unset($this->documentManager);
    }

    public function testGetEventsByEntityId(): void
    {
        $organizationId = Uuid::fromRfc4122(OrganizationFixtures::ORGANIZATION_ID_1);

        $timelineDocumentService = $this->createAbstractClass();

        $events = $timelineDocumentService->getEventsByEntityId($organizationId);

        $this->assertIsArray($events);

        self::assertSame($events[0]['author']['socialName'], 'Alessandro Feitoza');

        $this->assertEquals($events[0]['resourceId'], OrganizationFixtures::ORGANIZATION_ID_1);
        $this->assertEquals($events[0]['title'], 'The resource was created');
        $this->assertEquals($events[0]['priority'], 0);
        $this->assertEquals($events[0]['device'], 'unknown');
        $this->assertEquals($events[0]['platform'], 'unknown');
        $this->assertEmpty($events[0]['from']);
        $this->assertEquals($events[0]['to']['name'], 'PHP com rapadura');

        $this->assertEquals($events[1]['resourceId'], OrganizationFixtures::ORGANIZATION_ID_1);
        $this->assertEquals($events[1]['title'], 'The resource was updated');
        $this->assertEquals($events[1]['priority'], 0);
        $this->assertEquals($events[1]['device'], 'unknown');
        $this->assertEquals($events[1]['platform'], 'unknown');
        $this->assertEquals($events[1]['from']['name'], 'PHP com rapadura');
        $this->assertEquals($events[1]['to']['name'], 'PHP com RAPadura');
    }

    public function testGetEventsByEntityIdWhenDoesNotExistEvents(): void
    {
        $organizationId = Uuid::v4();

        $timelineDocumentService = $this->createAbstractClass();

        $events = $timelineDocumentService->getEventsByEntityId($organizationId);

        $this->assertIsArray($events);
        $this->assertEmpty($events);
    }

    private function createAbstractClass(): AbstractTimelineDocumentService
    {
        return new class ($this->documentManager, OrganizationTimeline::class, $this->userService) extends AbstractTimelineDocumentService {
        };
    }
}
