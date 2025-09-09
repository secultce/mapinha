<?php

declare(strict_types=1);

namespace App\Tests\Unit\Document;

use App\Document\OrganizationTimeline;
use App\Tests\AbstractApiTestCase;
use DateTime;

class OrganizationTimelineDocumentTest extends AbstractApiTestCase
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
            'name' => 'Organization x',
        ],
    ];

    private OrganizationTimeline $organizationTimeline;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organizationTimeline = new OrganizationTimeline();
        $this->organizationTimeline->setId(self::DOCUMENT['id']);
        $this->organizationTimeline->setUserId(self::DOCUMENT['userId']);
        $this->organizationTimeline->setResourceId(self::DOCUMENT['resourceId']);
        $this->organizationTimeline->setTitle(self::DOCUMENT['title']);
        $this->organizationTimeline->setPriority(self::DOCUMENT['priority']);
        $this->organizationTimeline->setDatetime(DateTime::createFromFormat('Y-m-d H:i:s', self::DOCUMENT['datetime']));
        $this->organizationTimeline->setDevice(self::DOCUMENT['device']);
        $this->organizationTimeline->setPlatform(self::DOCUMENT['platform']);
        $this->organizationTimeline->setFrom(self::DOCUMENT['from']);
        $this->organizationTimeline->setTo(self::DOCUMENT['to']);
    }

    public function testGettersAndSettersFromOrganizationTimelineDocumentShouldBeSuccessful(): void
    {
        $this->assertEquals(self::DOCUMENT['id'], $this->organizationTimeline->getId());
        $this->assertIsString($this->organizationTimeline->getId());

        $this->assertEquals(self::DOCUMENT['userId'], $this->organizationTimeline->getUserId());
        $this->assertIsString($this->organizationTimeline->getUserId());

        $this->assertEquals(self::DOCUMENT['resourceId'], $this->organizationTimeline->getResourceId());
        $this->assertIsString($this->organizationTimeline->getResourceId());

        $this->assertEquals(self::DOCUMENT['title'], $this->organizationTimeline->getTitle());
        $this->assertIsString($this->organizationTimeline->getTitle());

        $this->assertEquals(self::DOCUMENT['priority'], $this->organizationTimeline->getPriority());
        $this->assertIsInt($this->organizationTimeline->getPriority());

        $this->assertEquals(self::DOCUMENT['device'], $this->organizationTimeline->getDevice());
        $this->assertIsString($this->organizationTimeline->getDevice());

        $this->assertEquals(self::DOCUMENT['platform'], $this->organizationTimeline->getPlatform());
        $this->assertIsString($this->organizationTimeline->getPlatform());

        $this->assertEquals(self::DOCUMENT['from'], $this->organizationTimeline->getFrom());
        $this->assertIsArray($this->organizationTimeline->getTo());

        $this->assertEquals(self::DOCUMENT['to'], $this->organizationTimeline->getTo());
        $this->assertIsArray($this->organizationTimeline->getTo());
        $this->assertArrayHasKey('name', $this->organizationTimeline->getTo());
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

        self::assertEquals($expected, $this->organizationTimeline->toArray());
    }
}
