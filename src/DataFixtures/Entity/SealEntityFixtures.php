<?php

declare(strict_types=1);

namespace App\DataFixtures\Entity;

use App\Entity\Agent;
use App\Entity\SealEntity;
use App\Enum\AuthorizedByEnum;
use App\Enum\EntityEnum;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

final class SealEntityFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public const string SEAL_ENTITY_ID_PREFIX = 'seal-entity';
    public const string SEAL_ENTITY_ID_1 = 'b90135ac-1d4e-4fda-a088-0e6a125de631';
    public const string SEAL_ENTITY_ID_2 = '28fb5fcb-3a11-4923-8104-74f09d9b9af8';
    public const string SEAL_ENTITY_ID_3 = 'd7df5b70-150f-492d-bec6-f31a949e71b9';
    public const string SEAL_ENTITY_ID_4 = '74f692d5-b79d-4e2b-ab34-38366b4cc3a6';
    public const string SEAL_ENTITY_ID_5 = 'df869510-c32f-440f-9593-f922616cde55';
    public const string SEAL_ENTITY_ID_6 = '795f81fe-4ea1-41e8-9f96-3d96386dd6ce';
    public const string SEAL_ENTITY_ID_7 = '03bbb4bb-2913-4bbb-9432-e6c1fe1798de';
    public const string SEAL_ENTITY_ID_8 = '5a1176b1-9a75-4768-a6a5-25ca94abc062';
    public const string SEAL_ENTITY_ID_9 = '830ed895-b3d6-4808-a31a-7a3fcbaff877';
    public const string SEAL_ENTITY_ID_10 = '2223c404-f623-42ce-81be-c730b9612adc';

    public const array SEAL_ENTITIES = [
        [
            'id' => self::SEAL_ENTITY_ID_1,
            'entityId' => EventFixtures::EVENT_ID_1,
            'entity' => EntityEnum::EVENT,
            'authorizedBy' => AuthorizedByEnum::AGENT,
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'createdAt' => '2024-10-01T12:00:00+00:00',
        ],
        [
            'id' => self::SEAL_ENTITY_ID_2,
            'entityId' => EventFixtures::EVENT_ID_2,
            'entity' => EntityEnum::EVENT,
            'authorizedBy' => AuthorizedByEnum::AGENT,
            'createdBy' => AgentFixtures::AGENT_ID_2,
            'createdAt' => '2024-10-05T15:00:00+00:00',
        ],
        [
            'id' => self::SEAL_ENTITY_ID_3,
            'entityId' => EventFixtures::EVENT_ID_3,
            'entity' => EntityEnum::EVENT,
            'authorizedBy' => AuthorizedByEnum::AGENT,
            'createdBy' => AgentFixtures::AGENT_ID_3,
            'createdAt' => '2024-10-10T18:00:00+00:00',
        ],
        [
            'id' => self::SEAL_ENTITY_ID_4,
            'entityId' => EventFixtures::EVENT_ID_4,
            'entity' => EntityEnum::EVENT,
            'authorizedBy' => AuthorizedByEnum::ORGANIZATION,
            'createdBy' => AgentFixtures::AGENT_ID_4,
            'createdAt' => '2024-10-15T09:30:00+00:00',
        ],
        [
            'id' => self::SEAL_ENTITY_ID_5,
            'entityId' => EventFixtures::EVENT_ID_5,
            'entity' => EntityEnum::EVENT,
            'authorizedBy' => AuthorizedByEnum::ORGANIZATION,
            'createdBy' => AgentFixtures::AGENT_ID_5,
            'createdAt' => '2024-10-20T14:45:00+00:00',
        ],
        [
            'id' => self::SEAL_ENTITY_ID_6,
            'entityId' => EventFixtures::EVENT_ID_6,
            'entity' => EntityEnum::EVENT,
            'authorizedBy' => AuthorizedByEnum::AGENT,
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'createdAt' => '2024-10-25T16:00:00+00:00',
        ],
        [
            'id' => self::SEAL_ENTITY_ID_7,
            'entityId' => EventFixtures::EVENT_ID_7,
            'entity' => EntityEnum::EVENT,
            'authorizedBy' => AuthorizedByEnum::ORGANIZATION,
            'createdBy' => AgentFixtures::AGENT_ID_2,
            'createdAt' => '2024-11-01T10:20:00+00:00',
        ],
        [
            'id' => self::SEAL_ENTITY_ID_8,
            'entityId' => EventFixtures::EVENT_ID_8,
            'entity' => EntityEnum::EVENT,
            'authorizedBy' => AuthorizedByEnum::ORGANIZATION,
            'createdBy' => AgentFixtures::AGENT_ID_3,
            'createdAt' => '2024-11-05T11:15:00+00:00',
        ],
        [
            'id' => self::SEAL_ENTITY_ID_9,
            'entityId' => EventFixtures::EVENT_ID_9,
            'entity' => EntityEnum::EVENT,
            'authorizedBy' => AuthorizedByEnum::AGENT,
            'createdBy' => AgentFixtures::AGENT_ID_4,
            'createdAt' => '2024-11-10T12:00:00+00:00',
        ],
        [
            'id' => self::SEAL_ENTITY_ID_10,
            'entityId' => EventFixtures::EVENT_ID_10,
            'entity' => EntityEnum::EVENT,
            'authorizedBy' => AuthorizedByEnum::ORGANIZATION,
            'createdBy' => AgentFixtures::AGENT_ID_5,
            'createdAt' => '2024-11-15T14:00:00+00:00',
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
            SealFixtures::class,
            EventFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $this->createSealEntities($manager);
        $this->manualLogout();
    }

    private function createSealEntities(ObjectManager $manager): void
    {
        foreach (self::SEAL_ENTITIES as $sealEntityData) {
            $sealEntity = $this->mountSealEntity($sealEntityData);

            $this->setReference(sprintf('%s-%s', self::SEAL_ENTITY_ID_PREFIX, $sealEntityData['id']), $sealEntity);

            $this->manualLoginByAgent($sealEntityData['createdBy']);

            $manager->persist($sealEntity);
        }

        $manager->flush();
    }

    private function mountSealEntity(array $sealEntityData, array $context = []): SealEntity
    {
        $sealEntityData['entity'] = $sealEntityData['entity']->value;
        $sealEntityData['authorizedBy'] = $sealEntityData['authorizedBy']->value;
        $sealEntityData['entityId'] = Uuid::fromString($sealEntityData['entityId']);

        /** @var SealEntity $sealEntity */
        $sealEntity = $this->serializer->denormalize($sealEntityData, SealEntity::class, context: $context);

        $sealEntity->setCreatedBy($this->getReference(sprintf('%s-%s', AgentFixtures::AGENT_ID_PREFIX, $sealEntityData['createdBy']), Agent::class));
        $sealEntity->setEntityId($sealEntityData['entityId']);

        return $sealEntity;
    }
}
