<?php

declare(strict_types=1);

namespace App\DataFixtures\Entity;

use App\Entity\Agent;
use App\Entity\Event;
use App\Entity\InscriptionEvent;
use App\Enum\InscriptionEventStatusEnum;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class InscriptionEventFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public const string INSCRIPTION_EVENT_ID_PREFIX = 'inscription-event';
    public const string INSCRIPTION_EVENT_ID_1 = '7f3822a0-7db2-4824-a41f-64981040b47a';
    public const string INSCRIPTION_EVENT_ID_2 = '44851481-e44e-4412-9ef4-b37ac2437eca';
    public const string INSCRIPTION_EVENT_ID_3 = '5f528b4b-fe19-4bb4-86b9-6b9cc2300a85';
    public const string INSCRIPTION_EVENT_ID_4 = '6ef87190-cc90-4155-9615-0bf3c680ac04';
    public const string INSCRIPTION_EVENT_ID_5 = '21f7cfbc-87f2-4b53-b66d-a5bfa265312a';
    public const string INSCRIPTION_EVENT_ID_6 = '0fffbea2-d3ba-4191-9608-7709f65fe14f';
    public const string INSCRIPTION_EVENT_ID_7 = 'dcf8f21a-452e-4206-a7b8-c881586897bf';
    public const string INSCRIPTION_EVENT_ID_8 = '240d87eb-9845-4e75-8b47-7443ac2fc28b';
    public const string INSCRIPTION_EVENT_ID_9 = '9b96112b-83a6-4974-9f8e-7e975b012747';
    public const string INSCRIPTION_EVENT_ID_10 = 'e80e9570-c210-49dd-938b-6b7960d49922';
    public const string INSCRIPTION_EVENT_ID_11 = '866daec1-973b-490c-8de0-1b0e27045e8d';
    public const string INSCRIPTION_EVENT_ID_12 = '36f20f24-d58a-42f5-874f-3304280de735';
    public const string INSCRIPTION_EVENT_ID_13 = 'f9455dfa-49b1-4ee7-9657-8a628cd5e2b9';
    public const string INSCRIPTION_EVENT_ID_14 = 'cfe01e6a-b6ab-4cf8-845d-2c59a765da21';
    public const string INSCRIPTION_EVENT_ID_15 = 'f77d8c47-083b-4e05-ab87-d1804a42937d';
    public const string INSCRIPTION_EVENT_ID_16 = '0f813e5b-3578-40ce-a46a-e12d3589b60b';
    public const string INSCRIPTION_EVENT_ID_17 = 'af1e5807-54f3-49e4-9aa2-1ba1e09f568b';
    public const string INSCRIPTION_EVENT_ID_18 = '1c14ceb6-5af8-47ee-abeb-b98499c06ed7';
    public const string INSCRIPTION_EVENT_ID_19 = '8374e25b-b993-4eb6-84ef-1814230083b2';
    public const string INSCRIPTION_EVENT_ID_20 = '85758b69-545c-4171-a915-71f97d286f29';
    public const string INSCRIPTION_EVENT_ID_21 = '8fe253b5-f991-4f87-92de-5ebdee8858f5';
    public const string INSCRIPTION_EVENT_ID_22 = 'ef75dfd9-f4d5-4452-9f25-227cb53ce0e5';
    public const string INSCRIPTION_EVENT_ID_23 = '64a01fdd-6b59-4c06-bb01-ac4d2c652432';
    public const string INSCRIPTION_EVENT_ID_24 = '0a1037cf-61c0-4c85-87f0-e3eb046737bb';
    public const string INSCRIPTION_EVENT_ID_25 = '959611fa-3dce-4fa6-bae4-64958a7e6f4a';

    public const array DATA = [
        [
            'id' => self::INSCRIPTION_EVENT_ID_1,
            'agent' => AgentFixtures::AGENT_ID_1,
            'event' => EventFixtures::EVENT_ID_2,
            'status' => InscriptionEventStatusEnum::CANCELLED->name,
            'createdAt' => '2024-09-04T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_2,
            'agent' => AgentFixtures::AGENT_ID_1,
            'event' => EventFixtures::EVENT_ID_3,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-06T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_3,
            'agent' => AgentFixtures::AGENT_ID_1,
            'event' => EventFixtures::EVENT_ID_4,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-08T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_4,
            'agent' => AgentFixtures::AGENT_ID_1,
            'event' => EventFixtures::EVENT_ID_5,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-10T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_5,
            'agent' => AgentFixtures::AGENT_ID_1,
            'event' => EventFixtures::EVENT_ID_6,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-11T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_6,
            'agent' => AgentFixtures::AGENT_ID_2,
            'event' => EventFixtures::EVENT_ID_7,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-12T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_7,
            'agent' => AgentFixtures::AGENT_ID_2,
            'event' => EventFixtures::EVENT_ID_8,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-13T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_8,
            'agent' => AgentFixtures::AGENT_ID_2,
            'event' => EventFixtures::EVENT_ID_9,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-14T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_9,
            'agent' => AgentFixtures::AGENT_ID_3,
            'event' => EventFixtures::EVENT_ID_7,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-12T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_10,
            'agent' => AgentFixtures::AGENT_ID_3,
            'event' => EventFixtures::EVENT_ID_8,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-13T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_11,
            'agent' => AgentFixtures::AGENT_ID_3,
            'event' => EventFixtures::EVENT_ID_9,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-14T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_12,
            'agent' => AgentFixtures::AGENT_ID_3,
            'event' => EventFixtures::EVENT_ID_10,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-15T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_13,
            'agent' => AgentFixtures::AGENT_ID_4,
            'event' => EventFixtures::EVENT_ID_8,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-13T13:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_14,
            'agent' => AgentFixtures::AGENT_ID_4,
            'event' => EventFixtures::EVENT_ID_9,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-14T13:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_15,
            'agent' => AgentFixtures::AGENT_ID_5,
            'event' => EventFixtures::EVENT_ID_10,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-15T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_16,
            'agent' => AgentFixtures::AGENT_ID_5,
            'event' => EventFixtures::EVENT_ID_1,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-01T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_17,
            'agent' => AgentFixtures::AGENT_ID_5,
            'event' => EventFixtures::EVENT_ID_2,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-04T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_18,
            'agent' => AgentFixtures::AGENT_ID_6,
            'event' => EventFixtures::EVENT_ID_1,
            'status' => InscriptionEventStatusEnum::ACTIVE,
            'createdAt' => '2024-09-01T13:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_19,
            'agent' => AgentFixtures::AGENT_ID_6,
            'event' => EventFixtures::EVENT_ID_2,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-04T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_20,
            'agent' => AgentFixtures::AGENT_ID_7,
            'event' => EventFixtures::EVENT_ID_3,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-06T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_21,
            'agent' => AgentFixtures::AGENT_ID_8,
            'event' => EventFixtures::EVENT_ID_3,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-06T13:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_22,
            'agent' => AgentFixtures::AGENT_ID_9,
            'event' => EventFixtures::EVENT_ID_3,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-06T14:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_23,
            'agent' => AgentFixtures::AGENT_ID_10,
            'event' => EventFixtures::EVENT_ID_4,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-08T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_24,
            'agent' => AgentFixtures::AGENT_ID_10,
            'event' => EventFixtures::EVENT_ID_5,
            'status' => InscriptionEventStatusEnum::ACTIVE,
            'createdAt' => '2024-09-10T12:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_EVENT_ID_25,
            'agent' => AgentFixtures::AGENT_ID_11,
            'event' => EventFixtures::EVENT_ID_2,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
            'createdAt' => '2024-09-04T14:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
    ];

    public const array DATA_UPDATED = [
        [
            'id' => self::INSCRIPTION_EVENT_ID_1,
            'agent' => AgentFixtures::AGENT_ID_1,
            'event' => EventFixtures::EVENT_ID_2,
            'status' => InscriptionEventStatusEnum::ACTIVE->name,
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
            EventFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $this->createInscriptionEvents($manager);
        $this->updateInscriptionEvents($manager);
        $this->manualLogout();
    }

    private function createInscriptionEvents(ObjectManager $manager): void
    {
        foreach (self::DATA as $inscriptionEventData) {
            $inscriptionEvent = $this->mountInscriptionEvent($inscriptionEventData);

            $this->manualLoginByAgent($inscriptionEventData['agent']);

            $this->setReference(sprintf('%s-%s', self::INSCRIPTION_EVENT_ID_PREFIX, $inscriptionEventData['id']), $inscriptionEvent);

            $manager->persist($inscriptionEvent);
        }

        $manager->flush();
    }

    private function updateInscriptionEvents(ObjectManager $manager): void
    {
        foreach (self::DATA_UPDATED as $inscriptionEventData) {
            $inscriptionEventObj = $this->getReference(sprintf('%s-%s', self::INSCRIPTION_EVENT_ID_PREFIX, $inscriptionEventData['id']), InscriptionEvent::class);

            $inscriptionEvent = $this->mountInscriptionEvent($inscriptionEventData, ['object_to_populate' => $inscriptionEventObj]);

            $this->manualLoginByAgent($inscriptionEventData['agent']);

            $manager->persist($inscriptionEvent);
        }

        $manager->flush();
    }

    private function mountInscriptionEvent(mixed $inscriptionEventData, array $context = []): InscriptionEvent
    {
        /** @var InscriptionEvent $inscriptionEvent */
        $inscriptionEvent = $this->serializer->denormalize($inscriptionEventData, InscriptionEvent::class, context: $context);

        $inscriptionEvent->setAgent($this->getReference(sprintf('%s-%s', AgentFixtures::AGENT_ID_PREFIX, $inscriptionEventData['agent']), Agent::class));
        $inscriptionEvent->setEvent($this->getReference(sprintf('%s-%s', EventFixtures::EVENT_ID_PREFIX, $inscriptionEventData['event']), Event::class));

        return $inscriptionEvent;
    }
}
