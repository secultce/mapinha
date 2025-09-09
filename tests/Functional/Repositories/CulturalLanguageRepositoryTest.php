<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repositories;

use App\Entity\CulturalLanguage;
use App\Repository\CulturalLanguageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class CulturalLanguageRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private CulturalLanguageRepository $culturalLanguageRepository;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        $this->culturalLanguageRepository = $this->entityManager->getRepository(CulturalLanguage::class);
    }

    public function testSaveCulturalLanguage(): void
    {
        $culturalLanguage = new CulturalLanguage();
        $culturalLanguage->setId(Uuid::v4());
        $culturalLanguage->setName('Test Language');
        $culturalLanguage->setDescription('Test description');

        $this->culturalLanguageRepository->save($culturalLanguage);

        $this->entityManager->clear();
        $foundLanguage = $this->culturalLanguageRepository->find($culturalLanguage->getId());
        $this->assertNotNull($foundLanguage);
        $this->assertEquals('Test Language', $foundLanguage->getName());
    }

    public function testRemoveCulturalLanguage(): void
    {
        $culturalLanguage = new CulturalLanguage();
        $culturalLanguage->setId(Uuid::v4());
        $culturalLanguage->setName('Test Language');
        $culturalLanguage->setDescription('Test description');

        $this->culturalLanguageRepository->save($culturalLanguage);

        $this->entityManager->clear();
        $foundLanguage = $this->culturalLanguageRepository->find($culturalLanguage->getId());
        $this->assertNotNull($foundLanguage);

        $this->culturalLanguageRepository->remove($foundLanguage);

        $this->entityManager->clear();
        $deletedLanguage = $this->culturalLanguageRepository->find($culturalLanguage->getId());
        $this->assertNull($deletedLanguage);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        unset($this->entityManager);
    }
}
