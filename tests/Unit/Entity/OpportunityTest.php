<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Agent;
use App\Entity\Event;
use App\Entity\Initiative;
use App\Entity\Opportunity;
use App\Entity\Phase;
use App\Entity\Space;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class OpportunityTest extends TestCase
{
    public function testGetAndSetId(): void
    {
        $opportunity = new Opportunity();
        $uuid = Uuid::v4();
        $opportunity->setId($uuid);

        $this->assertSame($uuid, $opportunity->getId());
    }

    public function testGetAndSetName(): void
    {
        $opportunity = new Opportunity();
        $name = 'New Opportunity';

        $opportunity->setName($name);
        $this->assertSame($name, $opportunity->getName());
    }

    public function testGetAndSetImage(): void
    {
        $opportunity = new Opportunity();
        $image = 'https://example.com/image.png';

        $opportunity->setImage($image);
        $this->assertSame($image, $opportunity->getImage());
    }

    public function testGetAndSetParent(): void
    {
        $opportunity = new Opportunity();
        $parentMock = $this->createMock(Opportunity::class);

        $opportunity->setParent($parentMock);
        $this->assertSame($parentMock, $opportunity->getParent());
    }

    public function testGetAndSetSpace(): void
    {
        $opportunity = new Opportunity();
        $spaceMock = $this->createMock(Space::class);

        $opportunity->setSpace($spaceMock);
        $this->assertSame($spaceMock, $opportunity->getSpace());
    }

    public function testGetAndSetInitiative(): void
    {
        $opportunity = new Opportunity();
        $initiativeMock = $this->createMock(Initiative::class);

        $opportunity->setInitiative($initiativeMock);
        $this->assertSame($initiativeMock, $opportunity->getInitiative());
    }

    public function testGetAndSetEvent(): void
    {
        $opportunity = new Opportunity();
        $eventMock = $this->createMock(Event::class);

        $opportunity->setEvent($eventMock);
        $this->assertSame($eventMock, $opportunity->getEvent());
    }

    public function testGetAndSetCreatedBy(): void
    {
        $opportunity = new Opportunity();
        $agentMock = $this->createMock(Agent::class);

        $opportunity->setCreatedBy($agentMock);
        $this->assertSame($agentMock, $opportunity->getCreatedBy());
    }

    public function testGetAndSetExtraFields(): void
    {
        $opportunity = new Opportunity();
        $fields = ['field1' => 'value1', 'field2' => 123];

        $opportunity->setExtraFields($fields);
        $this->assertSame($fields, $opportunity->getExtraFields());
    }

    public function testPhasesCollection(): void
    {
        $opportunity = new Opportunity();
        $this->assertCount(0, $opportunity->getPhases());

        $phaseMock = $this->createMock(Phase::class);
        $opportunity->addPhase($phaseMock);
        $this->assertCount(1, $opportunity->getPhases());
        $this->assertTrue($opportunity->getPhases()->contains($phaseMock));

        $opportunity->removePhase($phaseMock);
        $this->assertCount(0, $opportunity->getPhases());
        $this->assertFalse($opportunity->getPhases()->contains($phaseMock));
    }

    public function testSetAndGetPhases(): void
    {
        $opportunity = new Opportunity();

        $phaseMock1 = $this->createMock(Phase::class);
        $phaseMock2 = $this->createMock(Phase::class);

        $collection = new ArrayCollection([$phaseMock1, $phaseMock2]);
        $opportunity->setPhases($collection);

        $this->assertCount(2, $opportunity->getPhases());
        $this->assertTrue($opportunity->getPhases()->contains($phaseMock1));
        $this->assertTrue($opportunity->getPhases()->contains($phaseMock2));
    }

    public function testGetAndSetDates(): void
    {
        $opportunity = new Opportunity();
        $this->assertInstanceOf(DateTimeImmutable::class, $opportunity->getCreatedAt());

        $nowImmutable = new DateTimeImmutable('2025-01-01 12:00:00');
        $opportunity->setCreatedAt($nowImmutable);
        $this->assertSame($nowImmutable, $opportunity->getCreatedAt());

        $updated = new DateTime('2025-02-01 15:30:00');
        $opportunity->setUpdatedAt($updated);
        $this->assertSame($updated, $opportunity->getUpdatedAt());

        $deleted = new DateTime('2025-03-01 10:00:00');
        $opportunity->setDeletedAt($deleted);
        $this->assertSame($deleted, $opportunity->getDeletedAt());
    }

    public function testToArray(): void
    {
        $opportunity = new Opportunity();
        $id = Uuid::v4();
        $opportunity->setId($id);

        $name = 'Opportunity Name';
        $image = 'https://example.com/logo.png';
        $opportunity->setName($name);
        $opportunity->setImage($image);

        $parentMock = $this->createMock(Opportunity::class);
        $parentMock->method('toArray')->willReturn(['id' => 'parent-uuid']);
        $opportunity->setParent($parentMock);

        $spaceMock = $this->createMock(Space::class);
        $spaceMock->method('toArray')->willReturn(['id' => 'space-uuid']);
        $opportunity->setSpace($spaceMock);

        $initiativeMock = $this->createMock(Initiative::class);
        $initiativeMock->method('toArray')->willReturn(['id' => 'initiative-uuid']);
        $opportunity->setInitiative($initiativeMock);

        $eventMock = $this->createMock(Event::class);
        $eventMock->method('toArray')->willReturn(['id' => 'event-uuid']);
        $opportunity->setEvent($eventMock);

        $agentMock = $this->createMock(Agent::class);
        $agentMock->method('toArray')->willReturn(['id' => 'agent-uuid']);
        $opportunity->setCreatedBy($agentMock);

        $createdAt = new DateTimeImmutable('2025-01-02 10:00:00');
        $updatedAt = new DateTime('2025-01-03 12:00:00');
        $deletedAt = new DateTime('2025-01-04 14:00:00');

        $opportunity->setCreatedAt($createdAt);
        $opportunity->setUpdatedAt($updatedAt);
        $opportunity->setDeletedAt($deletedAt);

        $array = $opportunity->toArray();

        $this->assertIsArray($array);
        $this->assertSame($id->toRfc4122(), $array['id']);
        $this->assertSame($name, $array['name']);
        $this->assertSame($image, $array['image']);

        $this->assertArrayHasKey('parent', $array);
        $this->assertIsArray($array['parent']);
        $this->assertSame('parent-uuid', $array['parent']['id']);

        $this->assertArrayHasKey('space', $array);
        $this->assertIsArray($array['space']);
        $this->assertSame('space-uuid', $array['space']['id']);

        $this->assertArrayHasKey('initiative', $array);
        $this->assertIsArray($array['initiative']);
        $this->assertSame('initiative-uuid', $array['initiative']['id']);

        $this->assertArrayHasKey('event', $array);
        $this->assertIsArray($array['event']);
        $this->assertSame('event-uuid', $array['event']['id']);

        $this->assertArrayHasKey('createdBy', $array);
        $this->assertIsArray($array['createdBy']);
        $this->assertSame('agent-uuid', $array['createdBy']['id']);

        $this->assertIsArray($array['socialNetworks']);

        $this->assertSame('2025-01-02 10:00:00', $array['createdAt']);
        $this->assertSame('2025-01-03 12:00:00', $array['updatedAt']);
        $this->assertSame('2025-01-04 14:00:00', $array['deletedAt']);
    }
}
