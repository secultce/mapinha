<?php

declare(strict_types=1);

namespace App\Tests\Unit\Document;

use App\Document\SpaceTimeline;
use App\Tests\AbstractApiTestCase;

class SpaceDocumentTest extends AbstractApiTestCase
{
    public function testGettersAndSettersFromSpaceDocumentShouldBeSuccessful(): void
    {
        $space = new SpaceTimeline();
        $space->setId('01c5f148-a9d6-4eff-a768-27293f923867');
        $space->setResourceId('f3118ca0-9769-4616-9ebd-8d6ac6065934');
        $space->setUserId('792a9c65-484d-4d25-91af-78b7a6e42bac');
        $space->setPlatform('android');
        $space->setPriority(1);
        $space->setTo([
            'name' => 'Agente x',
        ]);

        $this->assertEquals('01c5f148-a9d6-4eff-a768-27293f923867', $space->getId());
        $this->assertIsString($space->getId());

        $this->assertEquals('f3118ca0-9769-4616-9ebd-8d6ac6065934', $space->getResourceId());
        $this->assertIsString($space->getResourceId());

        $this->assertEquals('792a9c65-484d-4d25-91af-78b7a6e42bac', $space->getUserId());
        $this->assertIsString($space->getUserId());

        $this->assertEquals('android', $space->getPlatform());
        $this->assertIsString($space->getPlatform());

        $this->assertEquals(1, $space->getPriority());
        $this->assertIsInt($space->getPriority());

        $this->assertEquals(['name' => 'Agente x'], $space->getTo());
        $this->assertIsArray($space->getTo());
        $this->assertArrayHasKey('name', $space->getTo());
    }
}
