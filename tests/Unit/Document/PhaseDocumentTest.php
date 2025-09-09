<?php

declare(strict_types=1);

namespace App\Tests\Unit\Document;

use App\Document\PhaseTimeline;
use App\Tests\AbstractApiTestCase;
use Datetime;

class PhaseDocumentTest extends AbstractApiTestCase
{
    public function testGettersAndSettersFromPhaseDocumentShouldBeSuccessful(): void
    {
        $phase = new PhaseTimeline();

        $id = '019b7bbb-8d2f-449e-ad14-717c81b36cb9';
        $userId = '3738c82c-e285-45ab-a7c2-a7e17163d673';
        $getResourceId = 'b7be8486-2559-4487-b09d-7e78e776b7fa';
        $datetime = new Datetime();
        $from = ['name' => 'Phase 1'];
        $to = ['name' => 'Phase 2'];

        $this->assertNull($phase->getId());

        $phase->setId($id);
        $phase->setUserId($userId);
        $phase->setResourceId($getResourceId);
        $phase->setTitle('Test Phase');
        $phase->setPriority(5);
        $phase->setDatetime($datetime);
        $phase->setDevice('Tablet');
        $phase->setPlatform('Web');
        $phase->setFrom($from);
        $phase->setTo($to);

        $this->assertEquals($id, $phase->getId());
        $this->assertIsString($phase->getId());

        $this->assertEquals($userId, $phase->getUserId());
        $this->assertIsString($phase->getUserId());

        $this->assertEquals($getResourceId, $phase->getResourceId());
        $this->assertIsString($phase->getResourceId());

        $this->assertEquals('Test Phase', $phase->getTitle());
        $this->assertIsString($phase->getTitle());

        $this->assertEquals(5, $phase->getPriority());
        $this->assertIsInt($phase->getPriority());
        $this->assertEquals($datetime, $phase->getDatetime());
        $this->assertInstanceOf(Datetime::class, $phase->getDatetime());

        $this->assertEquals('Tablet', $phase->getDevice());
        $this->assertIsString($phase->getDevice());

        $this->assertEquals('Web', $phase->getPlatform());
        $this->assertIsString($phase->getPlatform());

        $this->assertEquals($from, $phase->getFrom());
        $this->assertIsArray($phase->getFrom());

        $this->assertEquals($to, $phase->getTo());
        $this->assertIsArray($phase->getTo());
    }
}
