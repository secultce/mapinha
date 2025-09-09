<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\CulturalLanguage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class CulturalLanguageTest extends TestCase
{
    public function testCreateCulturalLanguage(): void
    {
        $culturalLanguage = new CulturalLanguage();

        $id = Uuid::v4();
        $name = 'Cultural Language Test';
        $description = 'Cultural Language test description';

        $culturalLanguage->setId($id);
        $culturalLanguage->setName($name);
        $culturalLanguage->setDescription($description);

        $this->assertEquals($id, $culturalLanguage->getId());
        $this->assertEquals($name, $culturalLanguage->getName());
        $this->assertEquals($description, $culturalLanguage->getDescription());
    }

    public function testToArray(): void
    {
        $culturalLanguage = new CulturalLanguage();

        $id = Uuid::v4();
        $name = 'Cultural Language Test';
        $description = 'Cultural Language test description';

        $culturalLanguage->setId($id);
        $culturalLanguage->setName($name);
        $culturalLanguage->setDescription($description);

        $this->assertEquals(
            [
                'id' => $id->toRfc4122(),
                'name' => $name,
                'description' => $description,
            ],
            $culturalLanguage->toArray()
        );
    }
}
