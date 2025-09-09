<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Address;
use App\Entity\City;
use App\Repository\CityRepository;
use App\Repository\Interface\CityRepositoryInterface;
use App\Tests\AbstractApiTestCase;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

class AddressTest extends AbstractApiTestCase
{
    private CityRepositoryInterface $cityRepository;

    public function setUp(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $this->cityRepository = $container->get(CityRepository::class);
    }

    public function testGettersAndSettersFromEntityAddressShouldBeSuccessful(): void
    {
        $addressConcrete = new class extends Address {
        };

        $address = new $addressConcrete();

        $this->assertNull($address->getId());
        $this->assertNull($address->getComplement());
        $this->assertNull($address->getUpdatedAt());
        $this->assertNull($address->getDeletedAt());

        $id = new Uuid('fbeb1195-7040-4600-877b-492d40de7352');
        $city = $this->cityRepository->findOneBy([]);
        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTimeImmutable();
        $deletedAt = new DateTimeImmutable();

        $address->setId($id);
        $address->setStreet('Rua Barca Velha');
        $address->setNumber('623');
        $address->setNeighborhood('Quintino Cunha');
        $address->setComplement('Apto 304');
        $address->setCity($city);
        $address->setZipcode('60000-00');
        $address->setCreatedAt($createdAt);
        $address->setUpdatedAt($updatedAt);
        $address->setDeletedAt($deletedAt);
        $addressToArray = $address->toArray();

        $this->assertEquals($id, $address->getId());
        $this->assertInstanceOf(Uuid::class, $address->getId());

        $this->assertEquals('Rua Barca Velha', $address->getStreet());
        $this->assertIsString($address->getStreet());

        $this->assertEquals('623', $address->getNumber());
        $this->assertIsString($address->getNumber());

        $this->assertEquals('Quintino Cunha', $address->getNeighborhood());
        $this->assertIsString($address->getNeighborhood());

        $this->assertEquals('Apto 304', $address->getComplement());
        $this->assertIsString($address->getComplement());

        $this->assertEquals($city, $address->getCity());
        $this->assertInstanceOf(City::class, $address->getCity());

        $this->assertEquals('60000-00', $address->getZipcode());
        $this->assertIsString($address->getZipcode());

        $this->assertEquals($createdAt, $address->getCreatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $address->getUpdatedAt());

        $this->assertEquals($updatedAt, $address->getUpdatedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $address->getUpdatedAt());

        $this->assertEquals($deletedAt, $address->getDeletedAt());
        $this->assertInstanceOf(DateTimeImmutable::class, $address->getDeletedAt());

        $this->assertEquals([
            'id' => 'fbeb1195-7040-4600-877b-492d40de7352',
            'street' => 'Rua Barca Velha',
            'number' => '623',
            'neighborhood' => 'Quintino Cunha',
            'complement' => 'Apto 304',
            'zipcode' => '60000-00',
            'city' => $city->toArray(),
            'createdAt' => $address->getCreatedAt()->format('Y-m-d H:i:s'),
            'updatedAt' => $address->getUpdatedAt()->format('Y-m-d H:i:s'),
            'deletedAt' => $address->getDeletedAt()->format('Y-m-d H:i:s'),
            'owner' => null,
        ], $address->toArray());
        $this->assertEquals("Rua Barca Velha, 623 - Quintino Cunha, Alta Floresta D'Oeste-RO, 60000-00", $address->getCompleteAddress());
        $this->assertIsArray($address->toArray());
    }
}
