<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repositories;

use App\Entity\City;
use App\Entity\State;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class CityRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private CityRepository $repository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->repository = $this->entityManager->getRepository(City::class);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        unset($this->entityManager);
    }

    public function testGetCitiesByUppercaseAcronym(): void
    {
        $cities = $this->repository->findByState('CE');

        $this->assertIsArray($cities);
        $this->assertCount(184, $cities);
        $this->assertSame('Fortaleza', $cities[0]->getName());
        $this->assertSame('Abaiara', $cities[1]->getName());
        $this->assertSame('Acarape', $cities[2]->getName());
        $this->assertSame('Acaraú', $cities[3]->getName());
        $this->assertSame('Acopiara', $cities[4]->getName());
    }

    public function testGetCitiesByLowercaseAcronym(): void
    {
        $cities = $this->repository->findByState('ce');

        $this->assertIsArray($cities);
        $this->assertCount(184, $cities);
        $this->assertSame('Fortaleza', $cities[0]->getName());
        $this->assertSame('Abaiara', $cities[1]->getName());
        $this->assertSame('Acarape', $cities[2]->getName());
        $this->assertSame('Acaraú', $cities[3]->getName());
        $this->assertSame('Acopiara', $cities[4]->getName());
    }

    public function testGetCitiesByInvalidAcronym(): void
    {
        $cities = $this->repository->findByState('ca');

        $this->assertIsArray($cities);
        $this->assertEmpty($cities);
    }

    public function testGetCitiesByStateObject(): void
    {
        $state = $this->entityManager->getRepository(State::class)->findOneBy(['acronym' => 'CE']);

        $cities = $this->repository->findByState($state);

        $this->assertIsArray($cities);
        $this->assertCount(184, $cities);
        $this->assertSame('Fortaleza', $cities[0]->getName());
        $this->assertSame('Abaiara', $cities[1]->getName());
        $this->assertSame('Acarape', $cities[2]->getName());
        $this->assertSame('Acaraú', $cities[3]->getName());
        $this->assertSame('Acopiara', $cities[4]->getName());
    }
}
