<?php

declare(strict_types=1);

namespace App\Tests\Unit\Document;

use App\Document\AgentTimeline;
use App\Tests\AbstractApiTestCase;
use DateTime;

class AgentDocumentTest extends AbstractApiTestCase
{
    public function testGettersAndSettersFromAgentDocumentShouldBeSuccessful(): void
    {
        $agent = new AgentTimeline();
        $this->assertNull($agent->getId());

        $id = 'acc9eece-08b2-4043-a949-dbe0f0dd2326';
        $resourceId = 'f457b5a-4710-446b-a119-3980dd32f870';
        $userId = '42e08c8d-0509-4427-8ed3-fa2ed955c38c';
        $title = 'Título';
        $priority = 1;
        $datetime = new DateTime();
        $device = 'smartphone';
        $platform = 'android';
        $from = ['name' => 'Nayara'];
        $to = ['name' => 'Calenzo'];

        $agent->setId($id);
        $agent->setUserId($userId);
        $agent->setResourceId($resourceId);
        $agent->setTitle($title);
        $agent->setPriority($priority);
        $agent->setDatetime($datetime);
        $agent->setDevice($device);
        $agent->setPlatform($platform);
        $agent->setFrom($from);
        $agent->setTo($to);

        $this->assertEquals($id, $agent->getId());
        $this->assertIsString($agent->getId());

        $this->assertEquals($userId, $agent->getUserId());
        $this->assertIsString($agent->getUserId());

        $this->assertEquals($resourceId, $agent->getResourceId());
        $this->assertIsString($agent->getResourceId());

        $this->assertEquals($title, $agent->getTitle());
        $this->assertIsString($agent->getTitle());

        $this->assertEquals($priority, $agent->getPriority());
        $this->assertIsInt($agent->getPriority());

        $this->assertEquals($datetime, $agent->getDatetime());
        $this->assertInstanceOf(DateTime::class, $agent->getDatetime());

        $this->assertEquals($device, $agent->getDevice());
        $this->assertIsString($agent->getDevice());

        $this->assertEquals($platform, $agent->getPlatform());
        $this->assertIsString($agent->getPlatform());

        $this->assertEquals($from, $agent->getFrom());
        $this->assertIsArray($agent->getFrom());
        $this->assertArrayHasKey('name', $agent->getFrom());

        $this->assertEquals($to, $agent->getTo());
        $this->assertIsArray($agent->getTo());
        $this->assertArrayHasKey('name', $agent->getTo());
    }
}
