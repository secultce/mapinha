<?php

declare(strict_types=1);

namespace App\Tests\Unit\Document;

use App\Document\InscriptionEventTimeline;
use App\Tests\AbstractApiTestCase;
use DateTime;

class InscriptionEventTimelineDocumentTest extends AbstractApiTestCase
{
    private const array DOCUMENT = [
        'id' => '4ca44efd-92c5-4a12-b1e0-4a3f71c67440',
        'userId' => 'cded4cd6-3105-40a2-9a23-6b832376a5f7',
        'resourceId' => 'cded4cd6-3105-40a2-9a23-6b832376a5f7',
        'title' => 'Title testing',
        'priority' => 1,
        'datetime' => '2016-01-01 00:00:00',
        'device' => 'device',
        'platform' => 'platform',
        'from' => [],
        'to' => [
            'name' => 'InscriptionEvent x',
        ],
    ];

    private InscriptionEventTimeline $inscriptionEventTimeline;

    protected function setUp(): void
    {
        parent::setUp();

        $this->inscriptionEventTimeline = new InscriptionEventTimeline();
        $this->inscriptionEventTimeline->setId(self::DOCUMENT['id']);
        $this->inscriptionEventTimeline->setUserId(self::DOCUMENT['userId']);
        $this->inscriptionEventTimeline->setResourceId(self::DOCUMENT['resourceId']);
        $this->inscriptionEventTimeline->setTitle(self::DOCUMENT['title']);
        $this->inscriptionEventTimeline->setPriority(self::DOCUMENT['priority']);
        $this->inscriptionEventTimeline->setDatetime(DateTime::createFromFormat('Y-m-d H:i:s', self::DOCUMENT['datetime']));
        $this->inscriptionEventTimeline->setDevice(self::DOCUMENT['device']);
        $this->inscriptionEventTimeline->setPlatform(self::DOCUMENT['platform']);
        $this->inscriptionEventTimeline->setFrom(self::DOCUMENT['from']);
        $this->inscriptionEventTimeline->setTo(self::DOCUMENT['to']);
    }

    public function testGettersAndSettersFromInscriptionEventTimelineDocumentShouldBeSuccessful(): void
    {
        $this->assertEquals(self::DOCUMENT['id'], $this->inscriptionEventTimeline->getId());
        $this->assertIsString($this->inscriptionEventTimeline->getId());

        $this->assertEquals(self::DOCUMENT['userId'], $this->inscriptionEventTimeline->getUserId());
        $this->assertIsString($this->inscriptionEventTimeline->getUserId());

        $this->assertEquals(self::DOCUMENT['resourceId'], $this->inscriptionEventTimeline->getResourceId());
        $this->assertIsString($this->inscriptionEventTimeline->getResourceId());

        $this->assertEquals(self::DOCUMENT['title'], $this->inscriptionEventTimeline->getTitle());
        $this->assertIsString($this->inscriptionEventTimeline->getTitle());

        $this->assertEquals(self::DOCUMENT['priority'], $this->inscriptionEventTimeline->getPriority());
        $this->assertIsInt($this->inscriptionEventTimeline->getPriority());

        $this->assertEquals(self::DOCUMENT['device'], $this->inscriptionEventTimeline->getDevice());
        $this->assertIsString($this->inscriptionEventTimeline->getDevice());

        $this->assertEquals(self::DOCUMENT['platform'], $this->inscriptionEventTimeline->getPlatform());
        $this->assertIsString($this->inscriptionEventTimeline->getPlatform());

        $this->assertEquals(self::DOCUMENT['from'], $this->inscriptionEventTimeline->getFrom());
        $this->assertIsArray($this->inscriptionEventTimeline->getTo());

        $this->assertEquals(self::DOCUMENT['to'], $this->inscriptionEventTimeline->getTo());
        $this->assertIsArray($this->inscriptionEventTimeline->getTo());
        $this->assertArrayHasKey('name', $this->inscriptionEventTimeline->getTo());
    }

    public function testToArray(): void
    {
        $expected = [
            'author' => null,
            'id' => self::DOCUMENT['id'],
            'userId' => self::DOCUMENT['userId'],
            'resourceId' => self::DOCUMENT['resourceId'],
            'title' => self::DOCUMENT['title'],
            'priority' => self::DOCUMENT['priority'],
            'datetime' => DateTime::createFromFormat('Y-m-d H:i:s', self::DOCUMENT['datetime']),
            'device' => self::DOCUMENT['device'],
            'platform' => self::DOCUMENT['platform'],
            'from' => self::DOCUMENT['from'],
            'to' => self::DOCUMENT['to'],
        ];

        self::assertEquals($expected, $this->inscriptionEventTimeline->toArray());
    }
}
