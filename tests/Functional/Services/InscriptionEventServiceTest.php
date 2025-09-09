<?php

declare(strict_types=1);

namespace App\Tests\Functional\Services;

use App\DataFixtures\Entity\EventFixtures;
use App\DataFixtures\Entity\InscriptionEventFixtures;
use App\Entity\InscriptionEvent;
use App\Enum\InscriptionEventStatusEnum;
use App\Exception\InscriptionEvent\InscriptionEventResourceNotFoundException;
use App\Repository\UserRepository;
use App\Service\Interface\InscriptionEventServiceInterface;
use App\Tests\AbstractApiTestCase;
use Symfony\Component\Uid\Uuid;

class InscriptionEventServiceTest extends AbstractApiTestCase
{
    private InscriptionEventServiceInterface $service;

    protected function setUp(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $userRepository = $container->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('alessandrofeitoza@example.com');
        $client->loginUser($testUser);

        $this->service = $container->get(InscriptionEventServiceInterface::class);
    }

    public function testFindAnInscriptionEvent(): void
    {
        $result = $this->service->get(Uuid::fromString(EventFixtures::EVENT_ID_1), Uuid::fromString(InscriptionEventFixtures::INSCRIPTION_EVENT_ID_18));

        self::assertInstanceOf(InscriptionEvent::class, $result);
    }

    public function testListMyInscriptionEvent(): void
    {
        $result = $this->service->listMyInscriptions();
        self::assertIsArray($result);
        foreach ($result as $inscriptionEvent) {
            self::assertInstanceOf(InscriptionEvent::class, $inscriptionEvent);
        }
    }

    public function testNotFindAnInscriptionEvent(): void
    {
        self::expectException(InscriptionEventResourceNotFoundException::class);

        $this->service->get(Uuid::fromString(EventFixtures::EVENT_ID_1), Uuid::v4());
    }

    public function testFindInscriptionsForAnEvent(): void
    {
        $result = $this->service->list(Uuid::fromString(EventFixtures::EVENT_ID_1));

        $expectedResult = [
            [
                'name' => 'Raquel',
                'status' => 1,
            ],
            [
                'name' => 'Talyson',
                'status' => 1,
            ],
        ];

        foreach ($expectedResult as $index => $expectedItem) {
            /* @var InscriptionEvent $actualInscription */
            $actualInscription = $result[$index];

            self::assertEquals($expectedItem['name'], $actualInscription->getAgent()->getName(), 'The name of agent do not match.');
            self::assertEquals($expectedItem['status'], $actualInscription->getStatus(), 'The status do not match.');
        }
    }

    public function testSuspendAnInscription(): void
    {
        $inscription = $this->service->get(Uuid::fromString(EventFixtures::EVENT_ID_1), Uuid::fromString(InscriptionEventFixtures::INSCRIPTION_EVENT_ID_18));
        self::assertEquals($inscription->getStatus(), InscriptionEventStatusEnum::ACTIVE->value, 'The status should be active.');

        $this->service->suspend(Uuid::fromString(EventFixtures::EVENT_ID_1), Uuid::fromString(InscriptionEventFixtures::INSCRIPTION_EVENT_ID_18));

        $inscription = $this->service->get(Uuid::fromString(EventFixtures::EVENT_ID_1), Uuid::fromString(InscriptionEventFixtures::INSCRIPTION_EVENT_ID_18));
        self::assertEquals($inscription->getStatus(), InscriptionEventStatusEnum::SUSPENDED->value, 'The status should be suspended.');
    }

    public function testCheckInAnInscription(): void
    {
        $inscription = $this->service->get(Uuid::fromString(EventFixtures::EVENT_ID_1), Uuid::fromString(InscriptionEventFixtures::INSCRIPTION_EVENT_ID_18));
        self::assertEquals($inscription->getStatus(), InscriptionEventStatusEnum::ACTIVE->value, 'The status should be active.');

        $this->service->checkIn(Uuid::fromString(EventFixtures::EVENT_ID_1), Uuid::fromString(InscriptionEventFixtures::INSCRIPTION_EVENT_ID_18));

        $inscription = $this->service->get(Uuid::fromString(EventFixtures::EVENT_ID_1), Uuid::fromString(InscriptionEventFixtures::INSCRIPTION_EVENT_ID_18));
        self::assertEquals($inscription->getStatus(), InscriptionEventStatusEnum::CONFIRMED->value, 'The status should be confirmed.');
    }
}
