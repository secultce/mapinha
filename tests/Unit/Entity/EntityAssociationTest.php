<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Agent;
use App\Entity\EntityAssociation;
use App\Entity\Event;
use App\Entity\Initiative;
use App\Entity\Opportunity;
use App\Entity\Organization;
use App\Entity\Space;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class EntityAssociationTest extends TestCase
{
    public function testGetters(): void
    {
        $entityAssociation = new EntityAssociation();

        $id = Uuid::v4();

        $agentId = Uuid::v4();
        $agent = new Agent();
        $agent->setId($agentId);

        $eventId = Uuid::v4();
        $event = new Event();
        $event->setId($eventId);

        $initiativeId = Uuid::v4();
        $initiative = new Initiative();
        $initiative->setId($initiativeId);

        $opportunityId = Uuid::v4();
        $opportunity = new Opportunity();
        $opportunity->setId($opportunityId);

        $organizationId = Uuid::v4();
        $organization = new Organization();
        $organization->setId($organizationId);

        $spaceId = Uuid::v4();
        $space = new Space();
        $space->setId($spaceId);

        $entityAssociation->setId($id);
        $entityAssociation->setAgent($agent);
        $entityAssociation->setEvent($event);
        $entityAssociation->setInitiative($initiative);
        $entityAssociation->setOpportunity($opportunity);
        $entityAssociation->setOrganization($organization);
        $entityAssociation->setSpace($space);
        $entityAssociation->setWithAgent(true);
        $entityAssociation->setWithEvent(true);
        $entityAssociation->setWithInitiative(true);
        $entityAssociation->setWithOpportunity(true);
        $entityAssociation->setWithOrganization(true);
        $entityAssociation->setWithSpace(false);

        $this->assertSame($id->toRfc4122(), $entityAssociation->getId()->toRfc4122());
        $this->assertSame($spaceId->toRfc4122(), $entityAssociation->getSpace()->getId()->toRfc4122());
        $this->assertSame(true, $entityAssociation->withAgent());
        $this->assertSame(true, $entityAssociation->withEvent());
        $this->assertSame(true, $entityAssociation->withInitiative());
        $this->assertSame(false, $entityAssociation->withSpace());
        $this->assertSame(true, $entityAssociation->withOpportunity());
        $this->assertSame(true, $entityAssociation->withOrganization());

        self::assertSame($agentId->toRfc4122(), $entityAssociation->getAgent()->getId()->toRfc4122());
        self::assertSame($eventId->toRfc4122(), $entityAssociation->getEvent()->getId()->toRfc4122());
        self::assertSame($initiativeId->toRfc4122(), $entityAssociation->getInitiative()->getId()->toRfc4122());
        self::assertSame($opportunityId->toRfc4122(), $entityAssociation->getOpportunity()->getId()->toRfc4122());
        self::assertSame($organizationId->toRfc4122(), $entityAssociation->getOrganization()->getId()->toRfc4122());
        self::assertSame($spaceId->toRfc4122(), $entityAssociation->getSpace()->getId()->toRfc4122());
    }

    public function testToArray(): void
    {
        $entityAssociation = new EntityAssociation();

        $id = Uuid::v4();
        $spaceId = Uuid::v4();

        $space = new Space();
        $space->setId($spaceId);

        $entityAssociation->setId($id);
        $entityAssociation->setSpace($space);
        $entityAssociation->setWithAgent(true);
        $entityAssociation->setWithEvent(true);
        $entityAssociation->setWithInitiative(true);
        $entityAssociation->setWithOpportunity(true);
        $entityAssociation->setWithOrganization(true);

        $expectedArray = [
            'id' => $id->toRfc4122(),
            'agent' => null,
            'event' => null,
            'initiative' => null,
            'opportunity' => null,
            'organization' => null,
            'space' => $space->getId()->toRfc4122(),
            'withAgent' => true,
            'withEvent' => true,
            'withInitiative' => true,
            'withSpace' => false,
            'withOpportunity' => true,
            'withOrganization' => true,
        ];
        $actualArray = $entityAssociation->toArray();

        $this->assertEquals($expectedArray, $actualArray);
    }
}
