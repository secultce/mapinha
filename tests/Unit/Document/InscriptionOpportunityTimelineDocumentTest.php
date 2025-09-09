<?php

declare(strict_types=1);

namespace App\Tests\Unit\Document;

use App\Document\InscriptionOpportunityTimeline;
use App\Tests\AbstractApiTestCase;
use DateTime;

class InscriptionOpportunityTimelineDocumentTest extends AbstractApiTestCase
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
            'name' => 'InscriptionOpportunity x',
        ],
    ];

    private InscriptionOpportunityTimeline $inscriptionOpportunityTimeline;

    protected function setUp(): void
    {
        parent::setUp();

        $this->inscriptionOpportunityTimeline = new InscriptionOpportunityTimeline();
        $this->inscriptionOpportunityTimeline->setId(self::DOCUMENT['id']);
        $this->inscriptionOpportunityTimeline->setUserId(self::DOCUMENT['userId']);
        $this->inscriptionOpportunityTimeline->setResourceId(self::DOCUMENT['resourceId']);
        $this->inscriptionOpportunityTimeline->setTitle(self::DOCUMENT['title']);
        $this->inscriptionOpportunityTimeline->setPriority(self::DOCUMENT['priority']);
        $this->inscriptionOpportunityTimeline->setDatetime(DateTime::createFromFormat('Y-m-d H:i:s', self::DOCUMENT['datetime']));
        $this->inscriptionOpportunityTimeline->setDevice(self::DOCUMENT['device']);
        $this->inscriptionOpportunityTimeline->setPlatform(self::DOCUMENT['platform']);
        $this->inscriptionOpportunityTimeline->setFrom(self::DOCUMENT['from']);
        $this->inscriptionOpportunityTimeline->setTo(self::DOCUMENT['to']);
    }

    public function testGettersAndSettersFromInscriptionOpportunityTimelineDocumentShouldBeSuccessful(): void
    {
        $this->assertEquals(self::DOCUMENT['id'], $this->inscriptionOpportunityTimeline->getId());
        $this->assertIsString($this->inscriptionOpportunityTimeline->getId());

        $this->assertEquals(self::DOCUMENT['userId'], $this->inscriptionOpportunityTimeline->getUserId());
        $this->assertIsString($this->inscriptionOpportunityTimeline->getUserId());

        $this->assertEquals(self::DOCUMENT['resourceId'], $this->inscriptionOpportunityTimeline->getResourceId());
        $this->assertIsString($this->inscriptionOpportunityTimeline->getResourceId());

        $this->assertEquals(self::DOCUMENT['title'], $this->inscriptionOpportunityTimeline->getTitle());
        $this->assertIsString($this->inscriptionOpportunityTimeline->getTitle());

        $this->assertEquals(self::DOCUMENT['priority'], $this->inscriptionOpportunityTimeline->getPriority());
        $this->assertIsInt($this->inscriptionOpportunityTimeline->getPriority());

        $this->assertEquals(self::DOCUMENT['device'], $this->inscriptionOpportunityTimeline->getDevice());
        $this->assertIsString($this->inscriptionOpportunityTimeline->getDevice());

        $this->assertEquals(self::DOCUMENT['platform'], $this->inscriptionOpportunityTimeline->getPlatform());
        $this->assertIsString($this->inscriptionOpportunityTimeline->getPlatform());

        $this->assertEquals(self::DOCUMENT['from'], $this->inscriptionOpportunityTimeline->getFrom());
        $this->assertIsArray($this->inscriptionOpportunityTimeline->getTo());

        $this->assertEquals(self::DOCUMENT['to'], $this->inscriptionOpportunityTimeline->getTo());
        $this->assertIsArray($this->inscriptionOpportunityTimeline->getTo());
        $this->assertArrayHasKey('name', $this->inscriptionOpportunityTimeline->getTo());
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

        self::assertEquals($expected, $this->inscriptionOpportunityTimeline->toArray());
    }
}
