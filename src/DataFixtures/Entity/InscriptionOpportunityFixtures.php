<?php

declare(strict_types=1);

namespace App\DataFixtures\Entity;

use App\Entity\Agent;
use App\Entity\InscriptionOpportunity;
use App\Entity\Opportunity;
use App\Enum\InscriptionOpportunityStatusEnum;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class InscriptionOpportunityFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public const string INSCRIPTION_OPPORTUNITY_ID_PREFIX = 'inscription-opportunity';
    public const string INSCRIPTION_OPPORTUNITY_ID_1 = '91590354-87aa-4798-8b03-8731f4d5b478';
    public const string INSCRIPTION_OPPORTUNITY_ID_2 = 'f1d56881-7e32-4b41-9b3e-f8cd746a03c2';
    public const string INSCRIPTION_OPPORTUNITY_ID_3 = '7b5ae65c-ac7f-4c98-a33a-e3f39ddf5abd';
    public const string INSCRIPTION_OPPORTUNITY_ID_4 = '90e20f1a-0bb7-464a-82f1-01375dd32167';
    public const string INSCRIPTION_OPPORTUNITY_ID_5 = '01d1d4de-1503-47e5-8cbc-3da947e32e3a';
    public const string INSCRIPTION_OPPORTUNITY_ID_6 = '2b61a0ca-c690-450a-9986-297780471c82';
    public const string INSCRIPTION_OPPORTUNITY_ID_7 = 'e0beee7e-d4c7-48e8-8e3d-dcd9fbad5752';
    public const string INSCRIPTION_OPPORTUNITY_ID_8 = 'f23c6e73-9caf-4f22-88bb-acd328c4bb68';
    public const string INSCRIPTION_OPPORTUNITY_ID_9 = '5fd49a23-96c6-4629-8a5d-b8ab205837d2';
    public const string INSCRIPTION_OPPORTUNITY_ID_10 = '94f3b687-3353-4155-aa7f-5986c363b5ae';
    public const string INSCRIPTION_OPPORTUNITY_ID_11 = '3d06ba1d-bd0d-4e71-8a9b-4fac13ac38a1';
    public const string INSCRIPTION_OPPORTUNITY_ID_12 = '37e2201a-1c88-4b9e-bd9c-67148ba7cc89';
    public const string INSCRIPTION_OPPORTUNITY_ID_13 = 'ccd32a74-96e6-437a-8cc3-e2ca617b3352';
    public const string INSCRIPTION_OPPORTUNITY_ID_14 = '9d909e9d-f657-4fbb-acd9-0d232b71005e';
    public const string INSCRIPTION_OPPORTUNITY_ID_15 = '4c0ae4b9-97f9-488d-bbc0-73f79f53f191';
    public const string INSCRIPTION_OPPORTUNITY_ID_16 = '9014eb8f-fd2e-4c9a-9cbf-367d8cce7aed';
    public const string INSCRIPTION_OPPORTUNITY_ID_17 = '65d8e32f-f123-4b6a-98cb-d21f6e6adcbe';
    public const string INSCRIPTION_OPPORTUNITY_ID_18 = '45353ecd-b034-41d7-9fd4-baa813d7db17';
    public const string INSCRIPTION_OPPORTUNITY_ID_19 = '34b60531-4b3f-4329-a2d3-b1542fc2d19a';
    public const string INSCRIPTION_OPPORTUNITY_ID_20 = 'bc3a677d-8d93-4c1b-90be-b3239c790607';
    public const string INSCRIPTION_OPPORTUNITY_ID_21 = '297b6178-f68f-4149-908d-f3070ff9a1da';
    public const string INSCRIPTION_OPPORTUNITY_ID_22 = 'efff4fba-817b-403f-b84b-e7eaa1c0d451';
    public const string INSCRIPTION_OPPORTUNITY_ID_23 = 'f2fc7093-18d2-462a-b16f-2ffe1d613f34';
    public const string INSCRIPTION_OPPORTUNITY_ID_24 = '89990f84-1629-4ce5-97f5-2b0e8f3251eb';
    public const string INSCRIPTION_OPPORTUNITY_ID_25 = '82b57d49-225a-4ada-a3d3-a0ed0c1842f0';

    public const array DATA = [
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_1,
            'agent' => AgentFixtures::AGENT_ID_1,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_2,
            'status' => InscriptionOpportunityStatusEnum::INACTIVE->name,
            'createdAt' => '2024-09-04T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_2,
            'agent' => AgentFixtures::AGENT_ID_1,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_3,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-06T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_3,
            'agent' => AgentFixtures::AGENT_ID_1,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_4,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-08T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_4,
            'agent' => AgentFixtures::AGENT_ID_1,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_5,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-10T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_5,
            'agent' => AgentFixtures::AGENT_ID_1,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_6,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-11T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_6,
            'agent' => AgentFixtures::AGENT_ID_2,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_7,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-12T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_7,
            'agent' => AgentFixtures::AGENT_ID_2,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_8,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-13T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_8,
            'agent' => AgentFixtures::AGENT_ID_2,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_9,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-14T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_9,
            'agent' => AgentFixtures::AGENT_ID_3,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_7,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-12T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_10,
            'agent' => AgentFixtures::AGENT_ID_3,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_8,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-13T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_11,
            'agent' => AgentFixtures::AGENT_ID_3,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_9,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-14T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_12,
            'agent' => AgentFixtures::AGENT_ID_3,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_10,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-15T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_13,
            'agent' => AgentFixtures::AGENT_ID_4,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_8,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-13T13:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_14,
            'agent' => AgentFixtures::AGENT_ID_4,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_9,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-14T13:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_15,
            'agent' => AgentFixtures::AGENT_ID_5,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_10,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-15T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_16,
            'agent' => AgentFixtures::AGENT_ID_5,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_1,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-01T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_17,
            'agent' => AgentFixtures::AGENT_ID_5,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_2,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-04T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_18,
            'agent' => AgentFixtures::AGENT_ID_6,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_1,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE,
            'createdAt' => '2024-09-01T13:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_19,
            'agent' => AgentFixtures::AGENT_ID_6,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_2,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-04T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_20,
            'agent' => AgentFixtures::AGENT_ID_7,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_3,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-06T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_21,
            'agent' => AgentFixtures::AGENT_ID_8,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_3,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-06T13:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_22,
            'agent' => AgentFixtures::AGENT_ID_9,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_3,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-06T14:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_23,
            'agent' => AgentFixtures::AGENT_ID_10,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_4,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-08T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_24,
            'agent' => AgentFixtures::AGENT_ID_10,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_5,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE,
            'createdAt' => '2024-09-10T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_25,
            'agent' => AgentFixtures::AGENT_ID_11,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_2,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-04T14:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
    ];

    public const array DATA_UPDATED = [
        [
            'id' => self::INSCRIPTION_OPPORTUNITY_ID_1,
            'agent' => AgentFixtures::AGENT_ID_1,
            'opportunity' => OpportunityFixtures::OPPORTUNITY_ID_2,
            'status' => InscriptionOpportunityStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-04T11:00:00+00:00',
            'updatedAt' => '2024-09-04T11:30:00+00:00',
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
            OpportunityFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $this->createInscriptionOpportunities($manager);
        $this->updateInscriptionOpportunities($manager);
        $this->manualLogout();
    }

    private function createInscriptionOpportunities(ObjectManager $manager): void
    {
        foreach (self::DATA as $inscriptionOpportunityData) {
            $inscriptionOpportunity = $this->mountInscriptionOpportunity($inscriptionOpportunityData);

            $this->manualLoginByAgent($inscriptionOpportunityData['agent']);

            $this->setReference(sprintf('%s-%s', self::INSCRIPTION_OPPORTUNITY_ID_PREFIX, $inscriptionOpportunityData['id']), $inscriptionOpportunity);

            $manager->persist($inscriptionOpportunity);
        }

        $manager->flush();
    }

    private function updateInscriptionOpportunities(ObjectManager $manager): void
    {
        foreach (self::DATA_UPDATED as $inscriptionOpportunityData) {
            $inscriptionOpportunityObj = $this->getReference(sprintf('%s-%s', self::INSCRIPTION_OPPORTUNITY_ID_PREFIX, $inscriptionOpportunityData['id']), InscriptionOpportunity::class);

            $inscriptionOpportunity = $this->mountInscriptionOpportunity($inscriptionOpportunityData, ['object_to_populate' => $inscriptionOpportunityObj]);

            $this->manualLoginByAgent($inscriptionOpportunityData['agent']);

            $manager->persist($inscriptionOpportunity);
        }

        $manager->flush();
    }

    private function mountInscriptionOpportunity(mixed $inscriptionOpportunityData, array $context = []): InscriptionOpportunity
    {
        /** @var InscriptionOpportunity $inscriptionOpportunity */
        $inscriptionOpportunity = $this->serializer->denormalize($inscriptionOpportunityData, InscriptionOpportunity::class, context: $context);

        $inscriptionOpportunity->setAgent($this->getReference(sprintf('%s-%s', AgentFixtures::AGENT_ID_PREFIX, $inscriptionOpportunityData['agent']), Agent::class));
        $inscriptionOpportunity->setOpportunity($this->getReference(sprintf('%s-%s', OpportunityFixtures::OPPORTUNITY_ID_PREFIX, $inscriptionOpportunityData['opportunity']), Opportunity::class));

        return $inscriptionOpportunity;
    }
}
