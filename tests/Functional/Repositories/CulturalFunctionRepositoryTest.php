<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repositories;

use App\Entity\CulturalFunction;
use App\Repository\CulturalFunctionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class CulturalFunctionRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private CulturalFunctionRepository $culturalFunctionRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->culturalFunctionRepository = $this->entityManager->getRepository(CulturalFunction::class);
    }

    public function testSaveCulturalFunction(): void
    {
        $culturalFunction = new CulturalFunction();
        $culturalFunction->setId(Uuid::v4());
        $culturalFunction->setName('Show');

        $this->culturalFunctionRepository->save($culturalFunction);

        $this->entityManager->clear();
        $foundCulturalFunction = $this->culturalFunctionRepository->find($culturalFunction->getId());
        $this->assertNotNull($foundCulturalFunction);
        $this->assertEquals('Show', $foundCulturalFunction->getName());
    }
}
