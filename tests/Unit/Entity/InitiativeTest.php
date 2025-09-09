<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Agent;
use App\Entity\Initiative;
use App\Entity\Space;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class InitiativeTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $initiative = new Initiative();

        $id = Uuid::v4();
        $name = 'Initiative Test';
        $image = 'test_image.jpg';
        $parent = new Initiative();
        $space = new Space();
        $createdBy = new Agent();
        $extraFields = ['key1' => 'value1'];
        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTime();
        $deletedAt = new DateTime();

        $initiative->setId($id);
        $initiative->setName($name);
        $initiative->setImage($image);
        $initiative->setParent($parent);
        $initiative->setSpace($space);
        $initiative->setCreatedBy($createdBy);
        $initiative->setExtraFields($extraFields);
        $initiative->setCreatedAt($createdAt);
        $initiative->setUpdatedAt($updatedAt);
        $initiative->setDeletedAt($deletedAt);

        $this->assertSame($id, $initiative->getId());
        $this->assertSame($name, $initiative->getName());
        $this->assertSame($image, $initiative->getImage());
        $this->assertSame($parent, $initiative->getParent());
        $this->assertSame($space, $initiative->getSpace());
        $this->assertSame($createdBy, $initiative->getCreatedBy());
        $this->assertSame($extraFields, $initiative->getExtraFields());
        $this->assertSame($createdAt, $initiative->getCreatedAt());
        $this->assertSame($updatedAt, $initiative->getUpdatedAt());
        $this->assertSame($deletedAt, $initiative->getDeletedAt());
    }

    public function testToArray(): void
    {
        $initiative = new Initiative();
        $id = Uuid::v4();
        $agent = new Agent();
        $space = new Space();
        $parent = new Initiative();

        $agent->setId(Uuid::v4());
        $space->setId(Uuid::v4());
        $parent->setId(Uuid::v4());

        $initiative->setId($id);
        $initiative->setName('Test Initiative');
        $initiative->setCreatedBy($agent);
        $initiative->setSpace($space);
        $initiative->setParent($parent);

        $arrayData = $initiative->toArray();

        $this->assertSame($id->toRfc4122(), $arrayData['id']);
        $this->assertSame('Test Initiative', $arrayData['name']);
        $this->assertNotEmpty($arrayData['createdAt']);
        $this->assertArrayHasKey('extraFields', $arrayData);
        $this->assertArrayHasKey('space', $arrayData);
        $this->assertArrayHasKey('parent', $arrayData);
        $this->assertArrayHasKey('createdBy', $arrayData);
        $this->assertIsArray($arrayData['socialNetworks']);
        $this->assertEmpty($arrayData['socialNetworks']);
    }
}
