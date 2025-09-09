<?php

declare(strict_types=1);

namespace App\Tests\Entity;

use App\Entity\Agent;
use App\Entity\Event;
use App\Entity\InscriptionEvent;
use App\Helper\DateFormatHelper;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

final class InscriptionEventTest extends KernelTestCase
{
    public function testGettersAndSetters(): void
    {
        $inscription = new InscriptionEvent();

        $id = Uuid::v4();
        $agent = new Agent();
        $event = new Event();
        $createdAt = new DateTimeImmutable('2024-01-01 10:00:00');
        $updatedAt = new DateTime('2024-02-01 10:00:00');
        $deletedAt = new DateTime('2024-03-01 10:00:00');

        $inscription->setId($id);
        $inscription->setAgent($agent);
        $inscription->setEvent($event);
        $inscription->setStatus(1);
        $inscription->setCreatedAt($createdAt);
        $inscription->setUpdatedAt($updatedAt);
        $inscription->setDeletedAt($deletedAt);

        $this->assertSame($id, $inscription->getId());
        $this->assertSame($agent, $inscription->getAgent());
        $this->assertSame($event, $inscription->getEvent());
        $this->assertSame(1, $inscription->getStatus());
        $this->assertSame($createdAt, $inscription->getCreatedAt());
        $this->assertSame($updatedAt, $inscription->getUpdatedAt());
        $this->assertSame($deletedAt, $inscription->getDeletedAt());
    }

    public function testToArrayMethod(): void
    {
        $inscription = new InscriptionEvent();

        $id = Uuid::v4();
        $agent = new Agent();
        $agentUuid = Uuid::v4();
        $agent->setId($agentUuid);
        $event = new Event();
        $eventUuid = Uuid::v4();
        $event->setId($eventUuid);
        $createdAt = new DateTimeImmutable('2024-01-01 10:00:00');
        $updatedAt = new DateTime('2024-02-01 10:00:00');
        $deletedAt = new DateTime('2024-03-01 10:00:00');

        $inscription->setId($id);
        $inscription->setAgent($agent);
        $inscription->setEvent($event);
        $inscription->setStatus(1);
        $inscription->setCreatedAt($createdAt);
        $inscription->setUpdatedAt($updatedAt);
        $inscription->setDeletedAt($deletedAt);

        $expectedArray = [
            'id' => $id->toRfc4122(),
            'agent' => $agentUuid->toRfc4122(),
            'event' => $eventUuid->toRfc4122(),
            'status' => 1,
            'createdAt' => $createdAt->format(DateFormatHelper::DEFAULT_FORMAT),
            'updatedAt' => $updatedAt->format(DateFormatHelper::DEFAULT_FORMAT),
            'deletedAt' => $deletedAt->format(DateFormatHelper::DEFAULT_FORMAT),
        ];

        $this->assertSame($expectedArray, $inscription->toArray());
    }
}
