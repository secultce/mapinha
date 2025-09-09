<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\EventType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class EventTypeTest extends TestCase
{
    public function testGettersAndSettersFromEventTypeEntityShouldBeSuccessful(): void
    {
        $eventType = new EventType();

        $id = Uuid::v4();
        $name = 'Congresso';

        $eventType->setId($id);
        $eventType->setName($name);

        $this->assertSame($id, $eventType->getId());
        $this->assertSame($name, $eventType->getName());

        $this->assertEquals([
            'id' => $id->toRfc4122(),
            'name' => $name,
        ], $eventType->toArray());
    }
}
