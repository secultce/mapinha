<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Event;
use App\Entity\EventSchedule;
use App\Helper\DateFormatHelper;
use App\Tests\AbstractApiTestCase;
use DateTime;
use Symfony\Component\Uid\Uuid;

class EventScheduleTest extends AbstractApiTestCase
{
    public function testGetters(): void
    {
        $eventSchedule = new EventSchedule();

        $id = Uuid::v4();
        $startHour = new DateTime('2024-01-01 12:00:00');
        $endHour = new DateTime('2024-01-01 16:00:00');

        $eventSchedule->setId($id);
        $eventSchedule->setStartHour($startHour);
        $eventSchedule->setEndHour($endHour);

        $event = new Event();
        $event->setId(Uuid::v4());
        $eventSchedule->setEvent($event);

        $this->assertEquals($id, $eventSchedule->getId());
        $this->assertEquals($event->getId(), $eventSchedule->getEvent()->getId());
        $this->assertSame($startHour, $eventSchedule->getStartHour());
        $this->assertSame($endHour, $eventSchedule->getEndHour());
    }

    public function testToArray(): void
    {
        $eventSchedule = new EventSchedule();

        $id = Uuid::v4();
        $startHour = new DateTime('2024-01-01 08:00:00');
        $endHour = new DateTime('2024-01-01 12:00:00');

        $event = $this->createMock(Event::class);
        $event->method('toArray')->willReturn(['id' => 'event-id']);
        $eventSchedule->setEvent($event);

        $eventSchedule->setId($id);
        $eventSchedule->setStartHour($startHour);
        $eventSchedule->setEndHour($endHour);

        $this->assertEquals(
            [
                'id' => $id->toRfc4122(),
                'event' => ['id' => 'event-id'],
                'startHour' => $startHour->format(DateFormatHelper::DEFAULT_FORMAT),
                'endHour' => $endHour->format(DateFormatHelper::DEFAULT_FORMAT),
            ],
            $eventSchedule->toArray()
        );
    }
}
