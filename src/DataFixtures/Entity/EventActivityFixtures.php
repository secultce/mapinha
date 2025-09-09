<?php

declare(strict_types=1);

namespace App\DataFixtures\Entity;

use App\Entity\Event;
use App\Entity\EventActivity;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class EventActivityFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public const string EVENT_ACTIVITY_ID_PREFIX = 'event-activity';
    public const string EVENT_ACTIVITY_ID_1 = '04d90e89-8ed1-48b6-a6ee-7932b39b213c';
    public const string EVENT_ACTIVITY_ID_2 = '75a7f933-33e4-40cc-813f-98232cc4a161';
    public const string EVENT_ACTIVITY_ID_3 = '8d8c95d7-949f-4bec-9b47-b9f3a6cad16f';
    public const string EVENT_ACTIVITY_ID_4 = '83f43602-1e07-4e15-95f8-0b126145b66a';
    public const string EVENT_ACTIVITY_ID_5 = '28845b44-281f-4c44-8bfd-18928b60925d';
    public const string EVENT_ACTIVITY_ID_6 = 'ba160d31-efc2-462f-95d6-aefcd993d1b9';
    public const string EVENT_ACTIVITY_ID_7 = '14e25c94-e315-45e0-9494-c4b266af47a8';
    public const string EVENT_ACTIVITY_ID_8 = '8becfd91-7b9e-4bac-a085-516ed0eb16d4';
    public const string EVENT_ACTIVITY_ID_9 = '84144717-c7fd-4648-9de2-038a6b9555d2';
    public const string EVENT_ACTIVITY_ID_10 = 'cccfbf79-0af2-403e-b788-c60d3705461f';

    public const array EVENT_ACTIVITIES = [
        [
            'id' => self::EVENT_ACTIVITY_ID_1,
            'event' => EventFixtures::EVENT_ID_1,
            'title' => 'Abertura do modo',
            'description' => 'Abertura do modo',
            'startDate' => '2024-07-10T11:30:00+00:00',
            'endDate' => null,
        ],
        [
            'id' => self::EVENT_ACTIVITY_ID_2,
            'event' => EventFixtures::EVENT_ID_2,
            'title' => 'Abertura do pacote de 10 anos',
            'description' => 'Comemorando esse tempo de PHP e Rapadura',
            'startDate' => '2024-07-11T10:49:00+00:00',
            'endDate' => '2024-07-13T10:49:00+00:00',
        ],
        [
            'id' => self::EVENT_ACTIVITY_ID_3,
            'event' => EventFixtures::EVENT_ID_3,
            'title' => 'Abertura do músical',
            'description' => 'Abertura do músical',
            'startDate' => '2024-07-16T17:22:00+00:00',
            'endDate' => '2024-07-17T17:22:00+00:00',
        ],
        [
            'id' => self::EVENT_ACTIVITY_ID_4,
            'event' => EventFixtures::EVENT_ID_4,
            'title' => 'Abertura do evento',
            'description' => 'Mesa redonda com representação da comunidade',
            'startDate' => '2024-07-17T15:12:00+00:00',
            'endDate' => '2024-07-20T15:12:00+00:00',
        ],
        [
            'id' => self::EVENT_ACTIVITY_ID_5,
            'event' => EventFixtures::EVENT_ID_5,
            'title' => 'Abertura do evento',
            'description' => 'Exposição dos poetas do interior do Ceará',
            'startDate' => '2024-07-22T16:20:00+00:00',
            'endDate' => '2024-07-24T16:20:00+00:00',
        ],
        [
            'id' => self::EVENT_ACTIVITY_ID_6,
            'event' => EventFixtures::EVENT_ID_6,
            'title' => 'Abertura do evento',
            'description' => 'Apresentação da nova galeria',
            'startDate' => '2024-08-10T11:26:00+00:00',
            'endDate' => null,
        ],
        [
            'id' => self::EVENT_ACTIVITY_ID_7,
            'event' => EventFixtures::EVENT_ID_7,
            'title' => 'Abertura do evento',
            'description' => 'Teatro sobre o sertão',
            'startDate' => '2024-08-11T15:54:00+00:00',
            'endDate' => null,
        ],
        [
            'id' => self::EVENT_ACTIVITY_ID_8,
            'event' => EventFixtures::EVENT_ID_8,
            'title' => 'Abertura do evento',
            'description' => 'Um pouco sobre a história da rapadura',
            'startDate' => '2024-08-12T14:24:00+00:00',
            'endDate' => null,
        ],
        [
            'id' => self::EVENT_ACTIVITY_ID_9,
            'event' => EventFixtures::EVENT_ID_9,
            'title' => 'Abertura do evento cultural',
            'description' => 'Apresentação do evento cultural',
            'startDate' => '2024-08-13T20:25:00+00:00',
            'endDate' => null,
        ],
        [
            'id' => self::EVENT_ACTIVITY_ID_10,
            'event' => EventFixtures::EVENT_ID_10,
            'title' => 'Abertura do evento literário',
            'description' => 'Lendo até dar um nó',
            'startDate' => '2024-08-14T10:00:00+00:00',
            'endDate' => null,
        ],
    ];

    public const array EVENT_ACTIVITIES_UPDATED = [
        [
            'id' => self::EVENT_ACTIVITY_ID_1,
            'event' => EventFixtures::EVENT_ID_1,
            'title' => 'Abertura do modo',
            'description' => 'Abertura do modo',
            'createdAt' => '2024-07-10T11:30:00+00:00',
            'endDate' => '2024-07-12T11:35:00+00:00',
        ],
    ];

    public const array EVENT_CREATED_BY = [
        self::EVENT_ACTIVITY_ID_1 => AgentFixtures::AGENT_ID_1,
        self::EVENT_ACTIVITY_ID_2 => AgentFixtures::AGENT_ID_1,
        self::EVENT_ACTIVITY_ID_3 => AgentFixtures::AGENT_ID_2,
        self::EVENT_ACTIVITY_ID_4 => AgentFixtures::AGENT_ID_2,
        self::EVENT_ACTIVITY_ID_5 => AgentFixtures::AGENT_ID_3,
        self::EVENT_ACTIVITY_ID_6 => AgentFixtures::AGENT_ID_3,
        self::EVENT_ACTIVITY_ID_7 => AgentFixtures::AGENT_ID_4,
        self::EVENT_ACTIVITY_ID_8 => AgentFixtures::AGENT_ID_4,
        self::EVENT_ACTIVITY_ID_9 => AgentFixtures::AGENT_ID_5,
        self::EVENT_ACTIVITY_ID_10 => AgentFixtures::AGENT_ID_5,
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
            EventFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $this->createEventActivities($manager);
        $this->updateActivities($manager);
        $this->manualLogout();
    }

    private function createEventActivities(ObjectManager $manager): void
    {
        foreach (self::EVENT_ACTIVITIES as $eventActivityData) {
            $eventActivity = $this->mountEventActivity($eventActivityData);

            $this->setReference(sprintf('%s-%s', self::EVENT_ACTIVITY_ID_PREFIX, $eventActivityData['id']), $eventActivity);

            $this->manualLoginByAgent(self::EVENT_CREATED_BY[$eventActivityData['id']]);

            $manager->persist($eventActivity);
        }

        $manager->flush();
    }

    private function updateActivities(ObjectManager $manager): void
    {
        foreach (self::EVENT_ACTIVITIES_UPDATED as $eventActivityData) {
            $eventActivityObj = $this->getReference(sprintf('%s-%s', self::EVENT_ACTIVITY_ID_PREFIX, $eventActivityData['id']), EventActivity::class);

            $eventActivity = $this->mountEventActivity($eventActivityData, ['object_to_populate' => $eventActivityObj]);

            $this->manualLoginByAgent(self::EVENT_CREATED_BY[$eventActivityData['id']]);

            $manager->persist($eventActivity);
        }

        $manager->flush();
    }

    private function mountEventActivity(array $eventActivityData, array $context = []): EventActivity
    {
        /** @var EventActivity $eventActivity */
        $eventActivity = $this->serializer->denormalize($eventActivityData, EventActivity::class, context: $context);

        $eventActivity->setEvent($this->getReference(sprintf('%s-%s', EventFixtures::EVENT_ID_PREFIX, $eventActivityData['event']), Event::class));

        return $eventActivity;
    }
}
