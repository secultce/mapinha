<?php

declare(strict_types=1);

namespace App\Tests\Functional\Services;

use App\DataFixtures\Entity\CulturalLanguageFixtures;
use App\Entity\CulturalLanguage;
use App\Exception\CulturalLanguage\CulturalLanguageResourceNotFoundException;
use App\Service\Interface\CulturalLanguageServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Uid\Uuid;

class CulturalLanguageServiceTest extends KernelTestCase
{
    private CulturalLanguageServiceInterface $service;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->service = self::getContainer()->get(CulturalLanguageServiceInterface::class);
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
    }

    public function testCreateCulturalLanguage(): void
    {
        $data = [
            'id' => Uuid::v4()->toRfc4122(),
            'name' => 'Test Language',
            'description' => 'Test description',
        ];

        $this->service->create($data);

        $culturalLanguage = $this->entityManager->find(CulturalLanguage::class, Uuid::v4()->fromRfc4122($data['id']));

        self::assertNotNull($culturalLanguage);
    }

    public function testUpdateCulturalLanguage(): void
    {
        $culturalLanguageId = Uuid::fromString(CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_5);

        $data = [
            'name' => 'Updated Language',
            'description' => 'Updated description',
        ];

        $this->service->update($culturalLanguageId, $data);

        $culturalLanguage = $this->entityManager->find(CulturalLanguage::class, $culturalLanguageId);

        self::assertEquals($data['name'], $culturalLanguage->getName());
        self::assertEquals($data['description'], $culturalLanguage->getDescription());
    }

    public function testGetCulturalLanguage(): void
    {
        $id = Uuid::fromString(CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_1);

        $culturalLanguage = $this->service->get($id);

        self::assertEquals($id, $culturalLanguage->getId());
    }

    public function testGetCulturalLanguageNotFound(): void
    {
        $this->expectException(CulturalLanguageResourceNotFoundException::class);

        $nonExistentId = Uuid::v4();
        $this->service->get($nonExistentId);
    }

    public function testListCulturalLanguages(): void
    {
        $limit = 3;

        $list = $this->service->list($limit);

        self::assertCount($limit, $list);
    }

    public function testRemoveCulturalLanguage(): void
    {
        $id = Uuid::fromString(CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_3);

        $this->service->remove($id);

        $culturalLanguage = $this->entityManager->find(CulturalLanguage::class, $id);

        self::assertNull($culturalLanguage);
    }

    public function testRemoveCulturalLanguageNotFound(): void
    {
        $this->expectException(CulturalLanguageResourceNotFoundException::class);

        $nonExistentId = Uuid::v4();
        $this->service->remove($nonExistentId);
    }
}
