<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Agent;
use App\Entity\InscriptionPhase;
use App\Entity\Phase;
use App\Helper\DateFormatHelper;
use App\Tests\AbstractApiTestCase;
use DateTime;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

final class InscriptionPhaseEntityTest extends AbstractApiTestCase
{
    public function testGettersAndSettersFromInscriptionPhaseEntityShouldBeSuccessful(): void
    {
        $inscriptionPhase = new InscriptionPhase();

        $agentId = Uuid::v4();
        $agent = new Agent();
        $agent->setId($agentId);

        $phaseId = Uuid::v4();
        $phase = new Phase();
        $phase->setId($phaseId);

        $id = Uuid::v4();
        $status = 1;
        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTime();
        $deletedAt = new DateTime();

        $inscriptionPhase->setId($id);
        $inscriptionPhase->setAgent($agent);
        $inscriptionPhase->setPhase($phase);
        $inscriptionPhase->setStatus($status);
        $inscriptionPhase->setCreatedAt($createdAt);
        $inscriptionPhase->setUpdatedAt($updatedAt);
        $inscriptionPhase->setDeletedAt($deletedAt);

        $this->assertSame($id, $inscriptionPhase->getId());
        $this->assertSame($agent, $inscriptionPhase->getAgent());
        $this->assertSame($phase, $inscriptionPhase->getPhase());
        $this->assertSame($status, $inscriptionPhase->getStatus());
        $this->assertSame($createdAt, $inscriptionPhase->getCreatedAt());
        $this->assertSame($updatedAt, $inscriptionPhase->getUpdatedAt());
        $this->assertSame($deletedAt, $inscriptionPhase->getDeletedAt());

        $this->assertEquals([
            'id' => $id->toRfc4122(),
            'agent' => $agentId,
            'organization' => null,
            'phase' => $phaseId,
            'status' => $status,
            'extraFields' => null,
            'createdAt' => $createdAt->format(DateFormatHelper::DEFAULT_FORMAT),
            'updatedAt' => $updatedAt->format(DateFormatHelper::DEFAULT_FORMAT),
            'deletedAt' => $deletedAt->format(DateFormatHelper::DEFAULT_FORMAT),
        ], $inscriptionPhase->toArray());
    }
}
