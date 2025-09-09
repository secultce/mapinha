<?php

declare(strict_types=1);

namespace App\Tests\Unit\Document;

use App\Document\InitiativeTimeline;
use App\Tests\AbstractApiTestCase;
use DateTime;
use Symfony\Component\Uid\Uuid;

class InitiativeDocumentTest extends AbstractApiTestCase
{
    public function testGettersAndSettersFromInitiativeDocumentShouldBeSuccessful(): void
    {
        $initiative = new InitiativeTimeline();

        $this->assertNull($initiative->getId());

        $dateTime = new DateTime();
        $id = Uuid::v4()->toString();
        $initiative->setId($id);
        $initiative->setUserId('ce1c6dff-15b3-4e59-a8e7-4e4b110622bd');
        $initiative->setResourceId('6cadbd03-72af-44fe-9f64-052dfd7a26b6');
        $initiative->setTitle('projeto da vida');
        $initiative->setPriority(1);
        $initiative->setDatetime($dateTime);
        $initiative->setDevice('dev01');
        $initiative->setPlatform('plat01');
        $initiative->setFrom(['name' => 'Initiative X']);
        $initiative->setTo(['name' => 'Initiative Y']);

        $this->assertEquals($id, $initiative->getId());
        $this->assertIsString($initiative->getId());
        $this->assertIsString($initiative->getUserId());
        $this->assertIsString($initiative->getResourceId());
        $this->assertEquals('projeto da vida', $initiative->getTitle());
        $this->assertEquals(1, $initiative->getPriority());
        $this->assertEquals($dateTime, $initiative->getDatetime());
        $this->assertEquals('dev01', $initiative->getDevice());
        $this->assertEquals('plat01', $initiative->getPlatform());
        $this->assertEquals(['name' => 'Initiative X'], $initiative->getFrom());
        $this->assertEquals(['name' => 'Initiative Y'], $initiative->getTo());
    }
}
