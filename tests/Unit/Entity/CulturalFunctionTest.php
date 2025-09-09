<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\CulturalFunction;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class CulturalFunctionTest extends TestCase
{
    public function testGettersAndSettersFromCulturalFunctionEntityShouldBeSuccessful(): void
    {
        $culturalFunction = new CulturalFunction();

        $id = Uuid::v4();
        $name = 'Produtor Teste';

        $culturalFunction->setId($id);
        $culturalFunction->setName($name);

        $this->assertSame($id, $culturalFunction->getId());
        $this->assertSame($name, $culturalFunction->getName());

        $this->assertEquals([
            'id' => $id->toRfc4122(),
            'name' => $name,
        ], $culturalFunction->toArray());
    }
}
