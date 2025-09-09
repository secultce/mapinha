<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Agent;
use App\Entity\InscriptionOpportunity;
use App\Entity\Opportunity;
use App\Helper\DateFormatHelper;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class InscriptionOpportunityTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $inscription = new InscriptionOpportunity();

        $uuid = Uuid::v4();
        $inscription->setId($uuid);
        $this->assertSame($uuid, $inscription->getId());

        $agent = $this->createMock(Agent::class);
        $inscription->setAgent($agent);
        $this->assertSame($agent, $inscription->getAgent());

        $opportunity = $this->createMock(Opportunity::class);
        $inscription->setOpportunity($opportunity);
        $this->assertSame($opportunity, $inscription->getOpportunity());

        $inscription->setStatus(1);
        $this->assertSame(1, $inscription->getStatus());

        $createdAt = new DateTimeImmutable('2024-01-01 10:00:00');
        $updatedAt = new DateTime('2024-02-01 10:00:00');
        $deletedAt = new DateTime('2024-03-01 10:00:00');

        $inscription->setCreatedAt($createdAt);
        $inscription->setUpdatedAt($updatedAt);
        $inscription->setDeletedAt($deletedAt);

        $this->assertSame($createdAt, $inscription->getCreatedAt());
        $this->assertSame($updatedAt, $inscription->getUpdatedAt());
        $this->assertSame($deletedAt, $inscription->getDeletedAt());
    }

    public function testToArrayMethod(): void
    {
        $inscription = new InscriptionOpportunity();
        $uuid = Uuid::v4();
        $inscription->setId($uuid);

        $agent = $this->createMock(Agent::class);
        $agentUuid = Uuid::v4();
        $agent->method('getId')->willReturn($agentUuid);
        $inscription->setAgent($agent);

        $opportunity = $this->createMock(Opportunity::class);
        $opportunityUuid = Uuid::v4();
        $opportunity->method('getId')->willReturn($opportunityUuid);
        $inscription->setOpportunity($opportunity);

        $inscription->setStatus(1);

        $createdAt = new DateTimeImmutable('2024-01-01 10:00:00');
        $updatedAt = new DateTime('2024-02-01 10:00:00');
        $deletedAt = new DateTime('2024-03-01 10:00:00');

        $inscription->setCreatedAt($createdAt);
        $inscription->setUpdatedAt($updatedAt);
        $inscription->setDeletedAt($deletedAt);

        $expectedArray = [
            'id' => $uuid->toRfc4122(),
            'agent' => $agentUuid,
            'organization' => null,
            'opportunity' => $opportunityUuid,
            'status' => 1,
            'createdAt' => $createdAt->format(DateFormatHelper::DEFAULT_FORMAT),
            'updatedAt' => $updatedAt->format(DateFormatHelper::DEFAULT_FORMAT),
            'deletedAt' => $deletedAt->format(DateFormatHelper::DEFAULT_FORMAT),
        ];

        $this->assertSame($expectedArray, $inscription->toArray());
    }
}
