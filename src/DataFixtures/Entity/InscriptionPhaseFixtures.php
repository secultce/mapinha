<?php

declare(strict_types=1);

namespace App\DataFixtures\Entity;

use App\Entity\Agent;
use App\Entity\InscriptionPhase;
use App\Entity\Phase;
use App\Enum\InscriptionPhaseStatusEnum;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class InscriptionPhaseFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public const string INSCRIPTION_PHASE_ID_PREFIX = 'inscription-phase';
    public const string INSCRIPTION_PHASE_ID_1 = 'dca40549-5c79-45a3-bf80-c7afb70c5f01';
    public const string INSCRIPTION_PHASE_ID_2 = 'cbbaee75-91ee-4b7a-b904-dcfe72be61a8';
    public const string INSCRIPTION_PHASE_ID_3 = '3e6a0bcf-7998-456b-a9ec-bb221ce0606c';
    public const string INSCRIPTION_PHASE_ID_4 = '5249d77c-6aa2-4c42-9024-c176388612ed';
    public const string INSCRIPTION_PHASE_ID_5 = 'bfb7ba79-14fb-4900-9d0e-6c7d5a8603f8';
    public const string INSCRIPTION_PHASE_ID_6 = '982d5d02-1c75-4fe0-ac86-76f7e3a93cb8';
    public const string INSCRIPTION_PHASE_ID_7 = '574f57ee-385a-4725-9ca2-f0b63062fa66';
    public const string INSCRIPTION_PHASE_ID_8 = '80e9c707-ea67-4e96-bf08-80b351e4b3ae';
    public const string INSCRIPTION_PHASE_ID_9 = 'e496703e-d4cb-42f5-bb77-21c8f14e1d29';
    public const string INSCRIPTION_PHASE_ID_10 = 'b6ba53f1-5796-45c1-ba8b-8717bf05ebfa';
    public const string INSCRIPTION_PHASE_ID_11 = 'd43c9034-b672-4c71-a01c-f97f1a12711e';
    public const string INSCRIPTION_PHASE_ID_12 = '3b9f8c02-f60f-48a0-b6d7-e2b70daed122';
    public const string INSCRIPTION_PHASE_ID_13 = '266e39a7-fd83-463b-ba41-319bfa261dda';
    public const string INSCRIPTION_PHASE_ID_14 = 'b9f0c44b-7ee2-4714-8342-9f5bdbee9903';
    public const string INSCRIPTION_PHASE_ID_15 = '882795aa-fc55-4fe7-a071-9e9638293798';
    public const string INSCRIPTION_PHASE_ID_16 = 'ec8adccb-7b18-4700-b988-b42b7b46fc5a';
    public const string INSCRIPTION_PHASE_ID_17 = '0619cd27-d5c0-4cb2-a77d-8feb0f0947c6';
    public const string INSCRIPTION_PHASE_ID_18 = 'f28856ce-b7f9-454d-9c6a-ec052d05d25f';
    public const string INSCRIPTION_PHASE_ID_19 = '5b5deace-507d-44aa-a0b3-24de33b96613';
    public const string INSCRIPTION_PHASE_ID_20 = 'c9d7e462-b972-4148-a388-a303b176f3da';
    public const string INSCRIPTION_PHASE_ID_21 = '6b94ea4e-112e-4e58-8355-63bf4085c011';
    public const string INSCRIPTION_PHASE_ID_22 = 'b6864200-3335-463c-b85c-8d189fdcb78a';
    public const string INSCRIPTION_PHASE_ID_23 = '991c923c-ef94-4d45-9186-ece678f5cbca';
    public const string INSCRIPTION_PHASE_ID_24 = 'fb8dae3c-bb1c-49aa-b803-18c352eea026';
    public const string INSCRIPTION_PHASE_ID_25 = '22dde907-9b47-4e3a-a778-18cd9823fb1f';
    public const string INSCRIPTION_PHASE_ID_26 = '146c414d-01bd-4fd1-8032-5bd748043e74';
    public const string INSCRIPTION_PHASE_ID_27 = 'eb7ea8a8-d480-40ce-a64f-fb5ed51f7f36';
    public const string INSCRIPTION_PHASE_ID_28 = '7e19e26b-2a0c-4fb0-89d7-0f699dccfeb7';
    public const string INSCRIPTION_PHASE_ID_29 = '9ba3ca85-5648-4bde-8231-aa1031947037';
    public const string INSCRIPTION_PHASE_ID_30 = '5308e0ee-fd87-43dd-bad5-d2266be682cc';
    public const string INSCRIPTION_PHASE_ID_31 = 'e26ee3f3-05b6-4803-850d-7c455ee8f2c9';
    public const string INSCRIPTION_PHASE_ID_32 = '9caf1e11-90c8-4549-be81-3768db0b7b7b';
    public const string INSCRIPTION_PHASE_ID_33 = 'eae945ad-11c4-4ba8-8440-3f458682b68a';
    public const string INSCRIPTION_PHASE_ID_34 = '7b825d4b-10fa-43b7-aac9-5b091d4cf282';
    public const string INSCRIPTION_PHASE_ID_35 = 'd3893076-af0c-41af-9b2f-59fd92909f4d';
    public const string INSCRIPTION_PHASE_ID_36 = '8fbe26d8-2f1b-417b-a866-e463a0a972f4';
    public const string INSCRIPTION_PHASE_ID_37 = 'cb75c01c-6675-43bb-9682-80c6610e6839';
    public const string INSCRIPTION_PHASE_ID_38 = 'b74a94f5-6ad9-4826-876d-8e524e61139a';
    public const string INSCRIPTION_PHASE_ID_39 = '5f1c212b-985c-41ba-a30a-b1d19ff25ce4';

    public const array DATA = [
        [
            'id' => self::INSCRIPTION_PHASE_ID_1,
            'agent' => AgentFixtures::AGENT_ID_5,
            'phase' => PhaseFixtures::PHASE_ID_1,
            'status' => InscriptionPhaseStatusEnum::INACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-01T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_2,
            'agent' => AgentFixtures::AGENT_ID_6,
            'phase' => PhaseFixtures::PHASE_ID_1,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-01T13:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_3,
            'agent' => AgentFixtures::AGENT_ID_5,
            'phase' => PhaseFixtures::PHASE_ID_2,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-02T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_4,
            'agent' => AgentFixtures::AGENT_ID_6,
            'phase' => PhaseFixtures::PHASE_ID_2,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-02T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_5,
            'agent' => AgentFixtures::AGENT_ID_5,
            'phase' => PhaseFixtures::PHASE_ID_3,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-03T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_6,
            'agent' => AgentFixtures::AGENT_ID_6,
            'phase' => PhaseFixtures::PHASE_ID_3,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-03T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_7,
            'agent' => AgentFixtures::AGENT_ID_1,
            'phase' => PhaseFixtures::PHASE_ID_4,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-04T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_8,
            'agent' => AgentFixtures::AGENT_ID_5,
            'phase' => PhaseFixtures::PHASE_ID_4,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-04T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_9,
            'agent' => AgentFixtures::AGENT_ID_6,
            'phase' => PhaseFixtures::PHASE_ID_4,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-04T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_10,
            'agent' => AgentFixtures::AGENT_ID_11,
            'phase' => PhaseFixtures::PHASE_ID_4,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-04T14:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_11,
            'agent' => AgentFixtures::AGENT_ID_1,
            'phase' => PhaseFixtures::PHASE_ID_5,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-05T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_12,
            'agent' => AgentFixtures::AGENT_ID_5,
            'phase' => PhaseFixtures::PHASE_ID_5,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-05T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_13,
            'agent' => AgentFixtures::AGENT_ID_6,
            'phase' => PhaseFixtures::PHASE_ID_5,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-05T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_14,
            'agent' => AgentFixtures::AGENT_ID_11,
            'phase' => PhaseFixtures::PHASE_ID_5,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-05T14:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_15,
            'agent' => AgentFixtures::AGENT_ID_1,
            'phase' => PhaseFixtures::PHASE_ID_6,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-06T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_16,
            'agent' => AgentFixtures::AGENT_ID_7,
            'phase' => PhaseFixtures::PHASE_ID_6,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-06T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_17,
            'agent' => AgentFixtures::AGENT_ID_8,
            'phase' => PhaseFixtures::PHASE_ID_6,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-06T13:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_18,
            'agent' => AgentFixtures::AGENT_ID_9,
            'phase' => PhaseFixtures::PHASE_ID_6,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-06T14:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_19,
            'agent' => AgentFixtures::AGENT_ID_1,
            'phase' => PhaseFixtures::PHASE_ID_7,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-07T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_20,
            'agent' => AgentFixtures::AGENT_ID_7,
            'phase' => PhaseFixtures::PHASE_ID_7,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-07T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_21,
            'agent' => AgentFixtures::AGENT_ID_8,
            'phase' => PhaseFixtures::PHASE_ID_7,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-07T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_22,
            'agent' => AgentFixtures::AGENT_ID_9,
            'phase' => PhaseFixtures::PHASE_ID_7,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-07T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_23,
            'agent' => AgentFixtures::AGENT_ID_1,
            'phase' => PhaseFixtures::PHASE_ID_8,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-08T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_24,
            'agent' => AgentFixtures::AGENT_ID_10,
            'phase' => PhaseFixtures::PHASE_ID_8,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-08T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_25,
            'agent' => AgentFixtures::AGENT_ID_1,
            'phase' => PhaseFixtures::PHASE_ID_9,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-09T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_26,
            'agent' => AgentFixtures::AGENT_ID_10,
            'phase' => PhaseFixtures::PHASE_ID_9,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-09T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_27,
            'agent' => AgentFixtures::AGENT_ID_1,
            'phase' => PhaseFixtures::PHASE_ID_10,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-10T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_28,
            'agent' => AgentFixtures::AGENT_ID_10,
            'phase' => PhaseFixtures::PHASE_ID_10,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-10T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_29,
            'agent' => AgentFixtures::AGENT_ID_1,
            'phase' => PhaseFixtures::PHASE_ID_11,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-11T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_30,
            'agent' => AgentFixtures::AGENT_ID_2,
            'phase' => PhaseFixtures::PHASE_ID_12,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-12T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_31,
            'agent' => AgentFixtures::AGENT_ID_3,
            'phase' => PhaseFixtures::PHASE_ID_12,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-12T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_32,
            'agent' => AgentFixtures::AGENT_ID_2,
            'phase' => PhaseFixtures::PHASE_ID_13,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-13T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_33,
            'agent' => AgentFixtures::AGENT_ID_3,
            'phase' => PhaseFixtures::PHASE_ID_13,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-13T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_34,
            'agent' => AgentFixtures::AGENT_ID_4,
            'phase' => PhaseFixtures::PHASE_ID_13,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-13T13:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_35,
            'agent' => AgentFixtures::AGENT_ID_2,
            'phase' => PhaseFixtures::PHASE_ID_14,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-14T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_36,
            'agent' => AgentFixtures::AGENT_ID_3,
            'phase' => PhaseFixtures::PHASE_ID_14,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-14T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_37,
            'agent' => AgentFixtures::AGENT_ID_4,
            'phase' => PhaseFixtures::PHASE_ID_14,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-14T13:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_38,
            'agent' => AgentFixtures::AGENT_ID_3,
            'phase' => PhaseFixtures::PHASE_ID_15,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-15T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_ID_39,
            'agent' => AgentFixtures::AGENT_ID_5,
            'phase' => PhaseFixtures::PHASE_ID_15,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'extraFields' => [],
            'createdAt' => '2024-09-15T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
    ];

    public const array DATA_UPDATED = [
        [
            'id' => self::INSCRIPTION_PHASE_ID_1,
            'agent' => AgentFixtures::AGENT_ID_1,
            'phase' => PhaseFixtures::PHASE_ID_1,
            'status' => InscriptionPhaseStatusEnum::ACTIVE->value,
            'createdAt' => '2024-07-10T11:30:00+00:00',
            'updatedAt' => '2024-07-10T12:20:00+00:00',
            'deletedAt' => null,
        ],
    ];

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected TokenStorageInterface $tokenStorage,
        private readonly SerializerInterface $serializer,
    ) {
        parent::__construct($entityManager, $tokenStorage);
    }

    public function getDependencies(): array
    {
        return [
            AgentFixtures::class,
            PhaseFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $this->createInscriptionPhases($manager);
        $this->updateInscriptionPhases($manager);
        $this->manualLogout();
    }

    private function createInscriptionPhases(ObjectManager $manager): void
    {
        foreach (self::DATA as $inscriptionPhaseData) {
            $inscriptionPhase = $this->mountInscriptionPhase($inscriptionPhaseData);

            $this->manualLoginByAgent($inscriptionPhaseData['agent']);

            $this->setReference(sprintf('%s-%s', self::INSCRIPTION_PHASE_ID_PREFIX, $inscriptionPhaseData['id']), $inscriptionPhase);

            $manager->persist($inscriptionPhase);
        }

        $manager->flush();
    }

    private function updateInscriptionPhases(ObjectManager $manager): void
    {
        foreach (self::DATA_UPDATED as $inscriptionPhaseData) {
            $inscriptionPhaseObj = $this->getReference(sprintf('%s-%s', self::INSCRIPTION_PHASE_ID_PREFIX, $inscriptionPhaseData['id']), InscriptionPhase::class);

            $inscriptionPhase = $this->mountInscriptionPhase($inscriptionPhaseData, ['object_to_populate' => $inscriptionPhaseObj]);

            $this->manualLoginByAgent($inscriptionPhaseData['agent']);

            $manager->persist($inscriptionPhase);
        }

        $manager->flush();
    }

    private function mountInscriptionPhase(mixed $inscriptionPhaseData, array $context = []): InscriptionPhase
    {
        /** @var InscriptionPhase $inscriptionPhase */
        $inscriptionPhase = $this->serializer->denormalize($inscriptionPhaseData, InscriptionPhase::class, context: $context);

        $inscriptionPhase->setAgent($this->getReference(sprintf('%s-%s', AgentFixtures::AGENT_ID_PREFIX, $inscriptionPhaseData['agent']), Agent::class));
        $inscriptionPhase->setPhase($this->getReference(sprintf('%s-%s', PhaseFixtures::PHASE_ID_PREFIX, $inscriptionPhaseData['phase']), Phase::class));

        return $inscriptionPhase;
    }
}
