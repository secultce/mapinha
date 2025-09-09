<?php

declare(strict_types=1);

namespace App\DataFixtures\Entity;

use App\Entity\EventType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class EventTypeFixtures extends AbstractFixture
{
    public const string EVENT_TYPE_ID_PREFIX = 'event_type';
    public const string EVENT_TYPE_ID_1 = '612de4d9-e822-433b-85de-dafb94b0860b';
    public const string EVENT_TYPE_ID_2 = 'ff5319c6-8802-4fb2-8c2b-c3a0ca8a521d';
    public const string EVENT_TYPE_ID_3 = 'afc080e7-7b13-480b-b3aa-be380835d3b5';
    public const string EVENT_TYPE_ID_4 = 'f127da56-e3db-4666-ad01-596dba5c89b5';
    public const string EVENT_TYPE_ID_5 = 'e1ba0a3b-7915-45d8-822b-fc8aebabe8fc';
    public const string EVENT_TYPE_ID_6 = 'efeea068-1251-4b98-a900-3b8bdd16ecef';

    public const array EVENT_TYPES = [
        [
            'id' => self::EVENT_TYPE_ID_1,
            'name' => 'Show',
        ],
        [
            'id' => self::EVENT_TYPE_ID_2,
            'name' => 'Congresso',
        ],
        [
            'id' => self::EVENT_TYPE_ID_3,
            'name' => 'Feira',
        ],
        [
            'id' => self::EVENT_TYPE_ID_4,
            'name' => 'Workshop',
        ],
        [
            'id' => self::EVENT_TYPE_ID_5,
            'name' => 'Festival',
        ],
        [
            'id' => self::EVENT_TYPE_ID_6,
            'name' => 'Exposição',
        ],
    ];

    public const array EVENT_TYPES_UPDATED = [
        [
            'id' => self::EVENT_TYPE_ID_1,
            'name' => 'Show Musical',
        ],
    ];

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected TokenStorageInterface $tokenStorage,
        private readonly SerializerInterface $serializer,
    ) {
        parent::__construct($entityManager, $tokenStorage);
    }

    public function load(ObjectManager $manager): void
    {
        $this->createEventType($manager);
        $this->updateEventType($manager);
        $this->manualLogout();
    }

    private function createEventType(ObjectManager $manager): void
    {
        foreach (self::EVENT_TYPES as $eventTypeData) {
            $eventType = $this->serializer->denormalize($eventTypeData, EventType::class);

            $this->setReference(sprintf('%s-%s', self::EVENT_TYPE_ID_PREFIX, $eventTypeData['id']), $eventType);

            $manager->persist($eventType);
        }

        $manager->flush();
    }

    private function updateEventType(ObjectManager $manager): void
    {
        foreach (self::EVENT_TYPES_UPDATED as $eventTypeData) {
            $eventTypeObj = $this->getReference(sprintf('%s-%s', self::EVENT_TYPE_ID_PREFIX, $eventTypeData['id']), EventType::class);

            $eventType = $this->serializer->denormalize($eventTypeData, EventType::class, context: ['object_to_populate' => $eventTypeObj]);

            $manager->persist($eventType);
        }

        $manager->flush();
    }
}
