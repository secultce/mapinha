<?php

declare(strict_types=1);

namespace App\Tests\Unit\Document;

use App\Document\EventTimeline;
use App\Tests\AbstractApiTestCase;
use Datetime;
use Symfony\Component\Uid\Uuid;

class EventDocumentTest extends AbstractApiTestCase
{
    public function testGettersAndSettersFromEventDocumentShouldBeSuccessful(): void
    {
        $event = new EventTimeline();

        $this->assertNull($event->getId());
        $id = Uuid::v4()->toString();
        $event->setId($id);
        $this->assertEquals($id, $event->getId());
        $this->assertIsString($event->getId());

        $userId = Uuid::v4()->toString();
        $event->setUserId($userId);
        $this->assertEquals($userId, $event->getUserId());
        $this->assertIsString($event->getUserId());

        $getResourceId = Uuid::v4()->toString();
        $event->setResourceId($getResourceId);
        $this->assertEquals($getResourceId, $event->getResourceId());
        $this->assertIsString($event->getResourceId());

        $event->setTitle('Test Event');
        $this->assertEquals('Test Event', $event->getTitle());
        $this->assertIsString($event->getTitle());

        $event->setPriority(1);
        $this->assertEquals(1, $event->getPriority());
        $this->assertIsInt($event->getPriority());

        $datetime = new Datetime();
        $event->setDatetime($datetime);
        $this->assertEquals($datetime, $event->getDatetime());
        $this->assertInstanceOf(Datetime::class, $event->getDatetime());

        $event->setDevice('Test Device');
        $this->assertEquals('Test Device', $event->getDevice());
        $this->assertIsString($event->getDevice());

        $from = ['name' => 'Thiago'];
        $event->setFrom($from);
        $this->assertEquals($from, $event->getFrom());
        $this->assertIsArray($event->getFrom());

        $to = ['name' => 'Tiago'];
        $event->setTo($to);
        $this->assertEquals($to, $event->getTo());
        $this->assertIsArray($event->getTo());
    }
}
