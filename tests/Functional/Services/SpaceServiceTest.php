<?php

declare(strict_types=1);

namespace App\Tests\Functional\Services;

use App\DataFixtures\Entity\SpaceFixtures;
use App\Service\Interface\SpaceServiceInterface;
use App\Tests\AbstractKernelTestCase;
use Symfony\Component\Uid\Uuid;

final class SpaceServiceTest extends AbstractKernelTestCase
{
    private SpaceServiceInterface $service;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->service = self::getSpaceService();
    }

    public function testTogglePublish(): void
    {
        self::loginUser();

        $space = $this->service->get(Uuid::fromRfc4122(SpaceFixtures::SPACE_ID_8));
        self::assertFalse($space->isDraft());

        $this->service->togglePublish(Uuid::fromRfc4122(SpaceFixtures::SPACE_ID_8));

        $space = $this->service->get(Uuid::fromRfc4122(SpaceFixtures::SPACE_ID_8));
        self::assertTrue($space->isDraft());
    }

    private function getSpaceService(): SpaceServiceInterface
    {
        return self::getContainer()->get(SpaceServiceInterface::class);
    }
}
