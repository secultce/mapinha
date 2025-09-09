<?php

declare(strict_types=1);

namespace App\Tests\Functional\Services;

use App\Entity\Opportunity;
use App\Exception\ValidatorException;
use App\Repository\UserRepository;
use App\Service\Interface\OpportunityServiceInterface;
use App\Tests\AbstractApiTestCase;
use App\Tests\Fixtures\ImageTestFixtures;
use Doctrine\ORM\EntityManagerInterface;

class OpportunityServiceTest extends AbstractApiTestCase
{
    private OpportunityServiceInterface $service;

    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $userRepository = $container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('talysonsoares@example.com');
        $client->loginUser($testUser);

        $this->service = $container->get(OpportunityServiceInterface::class);
        $this->entityManager = $container->get(EntityManagerInterface::class);
    }

    public function testUpdateCoverImageSuccessfully(): void
    {
        $coverImage = ImageTestFixtures::getImageValid();

        $opportunity = $this->entityManager->getRepository(Opportunity::class)->find('d1068a81-c006-4358-8846-a95ef252c881');
        $this->assertNull($opportunity->getExtraFields()['coverImage'] ?? null);
        $this->assertNull($opportunity->getUpdatedAt());

        $this->service->updateCoverImage($opportunity->getId(), $coverImage);
        $this->assertNotNull($opportunity->getExtraFields()['coverImage']);
        $this->assertNotNull($opportunity->getUpdatedAt());
    }

    public function testUpdateCoverImageValidationFails(): void
    {
        $coverImage = ImageTestFixtures::getImageMoreThan2mb();

        $opportunity = $this->entityManager
            ->getRepository(Opportunity::class)
            ->find('d1068a81-c006-4358-8846-a95ef252c881');

        $this->assertNull($opportunity->getExtraFields()['coverImage'] ?? null);
        $this->assertNull($opportunity->getUpdatedAt());

        $this->expectException(ValidatorException::class);

        $this->service->updateCoverImage($opportunity->getId(), $coverImage);

        $this->assertNull($opportunity->getExtraFields()['coverImage'] ?? null);
        $this->assertNull($opportunity->getUpdatedAt());
    }
}
