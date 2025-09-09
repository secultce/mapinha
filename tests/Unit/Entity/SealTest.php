<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Agent;
use App\Entity\Seal;
use App\Tests\AbstractApiTestCase;
use DateTime;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

class SealTest extends AbstractApiTestCase
{
    public function testGettersAndSettersFromSealEntityShouldBeSuccessful(): void
    {
        $seal = new Seal();

        $id = Uuid::v4();
        $name = 'Seal XXX';
        $description = 'This is description of a test seal';
        $agent = new Agent();
        $agentId = Uuid::v4();
        $expirationDate = new DateTimeImmutable('+6 month');
        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTime();
        $deletedAt = new DateTime();

        $seal->setId($id);
        $seal->setName($name);
        $seal->setDescription($description);
        $seal->setActive(true);
        $agent->setId($agentId);
        $seal->setCreatedBy($agent);
        $seal->setExpirationDate($expirationDate);
        $seal->setCreatedAt($createdAt);
        $seal->setUpdatedAt($updatedAt);
        $seal->setDeletedAt($deletedAt);

        $this->assertEquals($createdAt, $seal->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $seal->getCreatedAt());

        $this->assertEquals([
            'id' => $id->toRfc4122(),
            'name' => $name,
            'description' => $description,
            'createdBy' => $agentId->toRfc4122(),
            'expirationDate' => $expirationDate->format('Y-m-d H:i:s'),
            'createdAt' => $createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $updatedAt->format('Y-m-d H:i:s'),
            'deletedAt' => $deletedAt->format('Y-m-d H:i:s'),
            'active' => true,
        ], $seal->toArray());
    }
}
