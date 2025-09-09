<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Event;
use App\Entity\EventActivity;
use App\Helper\DateFormatHelper;
use App\Tests\AbstractApiTestCase;
use DateTime;
use Symfony\Component\Uid\Uuid;

class EventActivityTest extends AbstractApiTestCase
{
    public function testGetters(): void
    {
        $eventActivity = new EventActivity();

        $id = Uuid::v4();
        $title = 'Cultural activity of event';
        $description = 'This is the description of the event';
        $startDate = new DateTime('2024-01-01 12:00:00');
        $endDate = new DateTime('2024-01-02 12:00:00');

        $eventActivity->setId($id);
        $eventActivity->setTitle($title);
        $eventActivity->setDescription($description);
        $eventActivity->setStartDate($startDate);
        $eventActivity->setEndDate($endDate);

        $event = new Event();
        $event->setId(Uuid::v4());
        $eventActivity->setEvent($event);

        $this->assertEquals($id, $eventActivity->getId());
        $this->assertEquals($title, $eventActivity->getTitle());
        $this->assertEquals($description, $eventActivity->getDescription());
        $this->assertEquals($event->getId(), $eventActivity->getEvent()->getId());
        $this->assertSame($startDate, $eventActivity->getStartDate());
        $this->assertSame($endDate, $eventActivity->getEndDate());
    }

    public function testToArray(): void
    {
        $eventActivity = new EventActivity();

        $id = Uuid::v4();
        $title = 'Cultural activity of event';
        $description = 'This is the description of the event';
        $startDate = new DateTime('2024-01-01 12:00:00');
        $endDate = new DateTime('2024-01-02 12:00:00');

        $event = $this->createMock(Event::class);
        $event->method('toArray')->willReturn(['id' => 'event-id']);
        $eventActivity->setEvent($event);

        $eventActivity->setId($id);
        $eventActivity->setTitle($title);
        $eventActivity->setDescription($description);
        $eventActivity->setStartDate($startDate);
        $eventActivity->setEndDate($endDate);

        $this->assertEquals(
            [
                'id' => $id->toRfc4122(),
                'event' => ['id' => 'event-id'],
                'title' => $title,
                'description' => $description,
                'startDate' => $startDate->format(DateFormatHelper::DEFAULT_FORMAT),
                'endDate' => $endDate->format(DateFormatHelper::DEFAULT_FORMAT),
            ],
            $eventActivity->toArray()
        );
    }
}
