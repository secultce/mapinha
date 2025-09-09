<?php

declare(strict_types=1);

namespace App\DataFixtures\Entity;

use App\Entity\Event;
use App\Entity\EventSchedule;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class EventScheduleFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public const string EVENT_SCHEDULE_ID_PREFIX = 'event-schedule';
    public const string EVENT_SCHEDULE_ID_1 = '04d90e89-8ed1-48b6-a6ee-7932b39b213c';
    public const string EVENT_SCHEDULE_ID_2 = '75a7f933-33e4-40cc-813f-98232cc4a161';
    public const string EVENT_SCHEDULE_ID_3 = '8d8c95d7-949f-4bec-9b47-b9f3a6cad16f';
    public const string EVENT_SCHEDULE_ID_4 = '83f43602-1e07-4e15-95f8-0b126145b66a';
    public const string EVENT_SCHEDULE_ID_5 = '28845b44-281f-4c44-8bfd-18928b60925d';
    public const string EVENT_SCHEDULE_ID_6 = 'ba160d31-efc2-462f-95d6-aefcd993d1b9';
    public const string EVENT_SCHEDULE_ID_7 = '14e25c94-e315-45e0-9494-c4b266af47a8';
    public const string EVENT_SCHEDULE_ID_8 = '8becfd91-7b9e-4bac-a085-516ed0eb16d4';
    public const string EVENT_SCHEDULE_ID_9 = '84144717-c7fd-4648-9de2-038a6b9555d2';
    public const string EVENT_SCHEDULE_ID_10 = 'cccfbf79-0af2-403e-b788-c60d3705461f';

    public const array EVENT_SCHEDULES = [
        [
            'id' => self::EVENT_SCHEDULE_ID_1,
            'event' => EventFixtures::EVENT_ID_1,
            'startHour' => '2024-07-10T08:30:00+00:00',
            'endHour' => '2024-07-10T12:00:00+00:00',
        ],
        [
            'id' => self::EVENT_SCHEDULE_ID_2,
            'event' => EventFixtures::EVENT_ID_1,
            'startHour' => '2024-07-11T08:00:00+00:00',
            'endHour' => '2024-07-11T12:00:00+00:00',
        ],
        [
            'id' => self::EVENT_SCHEDULE_ID_3,
            'event' => EventFixtures::EVENT_ID_1,
            'startHour' => '2024-07-11T14:00:00+00:00',
            'endHour' => '2024-07-11T18:00:00+00:00',
        ],
        [
            'id' => self::EVENT_SCHEDULE_ID_4,
            'event' => EventFixtures::EVENT_ID_2,
            'startHour' => '2024-07-17T08:12:00+00:00',
            'endHour' => '2024-07-17T15:12:00+00:00',
        ],
        [
            'id' => self::EVENT_SCHEDULE_ID_5,
            'event' => EventFixtures::EVENT_ID_2,
            'startHour' => '2024-07-18T10:20:00+00:00',
            'endHour' => '2024-07-18T16:20:00+00:00',
        ],
        [
            'id' => self::EVENT_SCHEDULE_ID_6,
            'event' => EventFixtures::EVENT_ID_6,
            'startHour' => '2024-08-10T11:26:00+00:00',
            'endHour' => '2024-08-10T17:26:00+00:00',
        ],
        [
            'id' => self::EVENT_SCHEDULE_ID_7,
            'event' => EventFixtures::EVENT_ID_7,
            'startHour' => '2024-08-11T10:54:00+00:00',
            'endHour' => '2024-08-11T15:54:00+00:00',
        ],
        [
            'id' => self::EVENT_SCHEDULE_ID_8,
            'event' => EventFixtures::EVENT_ID_8,
            'startHour' => '2024-08-12T07:24:00+00:00',
            'endHour' => '2024-08-12T14:24:00+00:00',
        ],
        [
            'id' => self::EVENT_SCHEDULE_ID_9,
            'event' => EventFixtures::EVENT_ID_9,
            'startHour' => '2024-08-13T06:25:00+00:00',
            'endHour' => '2024-08-13T20:25:00+00:00',
        ],
        [
            'id' => self::EVENT_SCHEDULE_ID_10,
            'event' => EventFixtures::EVENT_ID_10,
            'startHour' => '2024-08-14T10:00:00+00:00',
        ],
    ];

    public const array EVENT_CREATED_BY = [
        self::EVENT_SCHEDULE_ID_1 => AgentFixtures::AGENT_ID_1,
        self::EVENT_SCHEDULE_ID_2 => AgentFixtures::AGENT_ID_1,
        self::EVENT_SCHEDULE_ID_3 => AgentFixtures::AGENT_ID_1,
        self::EVENT_SCHEDULE_ID_4 => AgentFixtures::AGENT_ID_1,
        self::EVENT_SCHEDULE_ID_5 => AgentFixtures::AGENT_ID_1,
        self::EVENT_SCHEDULE_ID_6 => AgentFixtures::AGENT_ID_3,
        self::EVENT_SCHEDULE_ID_7 => AgentFixtures::AGENT_ID_4,
        self::EVENT_SCHEDULE_ID_8 => AgentFixtures::AGENT_ID_4,
        self::EVENT_SCHEDULE_ID_9 => AgentFixtures::AGENT_ID_5,
        self::EVENT_SCHEDULE_ID_10 => AgentFixtures::AGENT_ID_5,
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
        $this->createEventSchedules($manager);
        $this->manualLogout();
    }

    private function createEventSchedules(ObjectManager $manager): void
    {
        foreach (self::EVENT_SCHEDULES as $eventScheduleData) {
            $eventSchedule = $this->mountEventSchedule($eventScheduleData);

            $this->setReference(sprintf('%s-%s', self::EVENT_SCHEDULE_ID_PREFIX, $eventScheduleData['id']), $eventSchedule);

            $this->manualLoginByAgent(self::EVENT_CREATED_BY[$eventScheduleData['id']]);

            $manager->persist($eventSchedule);
        }

        $manager->flush();
    }

    private function mountEventSchedule(array $eventScheduleData, array $context = []): EventSchedule
    {
        $eventSchedule = $this->serializer->denormalize($eventScheduleData, EventSchedule::class, context: $context);

        $eventSchedule->setEvent($this->getReference(sprintf('%s-%s', EventFixtures::EVENT_ID_PREFIX, $eventScheduleData['event']), Event::class));

        return $eventSchedule;
    }
}
