<?php

declare(strict_types=1);

namespace App\DataFixtures\Entity;

use App\Entity\AccountEvent;
use App\Entity\User;
use App\Enum\AccountEventTypeEnum;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class AccountEventFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public const string ACCOUNT_EVENT_ID_PREFIX = 'account-event';
    public const string ACCOUNT_EVENT_ID_1 = 'd0e8a8e7-f98b-4237-a855-94d34099b9be';
    public const string ACCOUNT_EVENT_ID_2 = '721ed4a3-a6aa-44e9-b419-08106e394670';
    public const string ACCOUNT_EVENT_ID_3 = '2df60408-453f-4d7c-96c7-209dddf51fcf';
    public const string ACCOUNT_EVENT_ID_4 = '1211e4ba-b068-4806-a224-966d23326a92';
    public const string ACCOUNT_EVENT_ID_5 = '5f9de07f-428f-477d-ac85-a27654f76c1c';
    public const string ACCOUNT_EVENT_ID_6 = 'ea3ed300-c2d1-47b0-9493-f10bf54fc2b9';
    public const string ACCOUNT_EVENT_ID_7 = '81e86ca1-45ae-42e0-b6e8-bf953112d2c9';
    public const string ACCOUNT_EVENT_ID_8 = '199da5e2-52b1-49ad-9d26-ab5e992973d1';
    public const string ACCOUNT_EVENT_ID_9 = '07657f0a-f038-4499-9990-0bde77029d13';
    public const string ACCOUNT_EVENT_ID_10 = '86b6f35a-cef4-4eca-8d35-6f1d0586f056';

    public const array ACCOUNT_EVENTS = [
        [
            'id' => self::ACCOUNT_EVENT_ID_1,
            'user' => UserFixtures::USER_ID_1,
            'type' => AccountEventTypeEnum::ACTIVATION->value,
            'token' => '30f523ec-977d-4141-b90b-94e54b18df55',
            'expirationAt' => '2024-07-10T11:30:00+00:00',
        ],
        [
            'id' => self::ACCOUNT_EVENT_ID_2,
            'user' => UserFixtures::USER_ID_2,
            'type' => AccountEventTypeEnum::ACTIVATION->value,
            'token' => '086e7476-f2c3-453e-81c6-ee8424b1bb01',
            'expirationAt' => '2024-07-10T11:30:00+00:00',
        ],
        [
            'id' => self::ACCOUNT_EVENT_ID_3,
            'user' => UserFixtures::USER_ID_3,
            'type' => AccountEventTypeEnum::ACTIVATION->value,
            'token' => '0c77b375-2201-4425-9f42-adf54688e2c9',
            'expirationAt' => '2024-07-10T11:30:00+00:00',
        ],
        [
            'id' => self::ACCOUNT_EVENT_ID_4,
            'user' => UserFixtures::USER_ID_4,
            'type' => AccountEventTypeEnum::ACTIVATION->value,
            'token' => 'ef9dbab8-7ad2-4ca5-92fb-9f0d9fb3f5aa',
            'expirationAt' => '2024-07-10T11:30:00+00:00',
        ],
        [
            'id' => self::ACCOUNT_EVENT_ID_5,
            'user' => UserFixtures::USER_ID_5,
            'type' => AccountEventTypeEnum::ACTIVATION->value,
            'token' => 'ea8006ba-bcbf-49f7-9687-25ec6ee4b56e',
            'expirationAt' => '2024-07-10T11:30:00+00:00',
        ],
        [
            'id' => self::ACCOUNT_EVENT_ID_6,
            'user' => UserFixtures::USER_ID_6,
            'type' => AccountEventTypeEnum::ACTIVATION->value,
            'token' => 'b5e4b230-c878-4700-964a-41546e4b1b62',
            'expirationAt' => '2024-07-10T11:30:00+00:00',
        ],
        [
            'id' => self::ACCOUNT_EVENT_ID_7,
            'user' => UserFixtures::USER_ID_7,
            'type' => AccountEventTypeEnum::ACTIVATION->value,
            'token' => 'cd8c7139-11d0-4e0f-ab96-3ded02fdc2fa',
            'expirationAt' => '2024-07-10T11:30:00+00:00',
        ],
        [
            'id' => self::ACCOUNT_EVENT_ID_8,
            'user' => UserFixtures::USER_ID_8,
            'type' => AccountEventTypeEnum::ACTIVATION->value,
            'token' => '5700888c-af24-4a83-a0fb-c1f0c2c97958',
            'expirationAt' => '2024-07-10T11:30:00+00:00',
        ],
        [
            'id' => self::ACCOUNT_EVENT_ID_9,
            'user' => UserFixtures::USER_ID_9,
            'type' => AccountEventTypeEnum::ACTIVATION->value,
            'token' => 'd725775b-e6a0-4e9f-a263-52f14397705b',
            'expirationAt' => '2024-07-10T11:30:00+00:00',
        ],
        [
            'id' => self::ACCOUNT_EVENT_ID_10,
            'user' => UserFixtures::USER_ID_10,
            'type' => AccountEventTypeEnum::ACTIVATION->value,
            'token' => '25033756-96e3-4aff-9123-bb54d9e1f0ea',
            'expirationAt' => '2024-07-10T11:30:00+00:00',
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
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $this->createAccountEvents($manager);
    }

    private function mountAccountEvent(array $accountEventData, array $context = []): AccountEvent
    {
        /** @var AccountEvent $accountEvent */
        $accountEvent = $this->serializer->denormalize($accountEventData, AccountEvent::class, context: $context);

        $accountEvent->setUser($this->getReference(sprintf('%s-%s', UserFixtures::USER_ID_PREFIX, $accountEventData['user']), User::class));

        return $accountEvent;
    }

    private function createAccountEvents(ObjectManager $manager): void
    {
        foreach (self::ACCOUNT_EVENTS as $accountEventData) {
            $accountEvent = $this->mountAccountEvent($accountEventData);

            $this->setReference(sprintf('%s-%s', self::ACCOUNT_EVENT_ID_PREFIX, $accountEventData['id']), $accountEvent);

            $manager->persist($accountEvent);
        }

        $manager->flush();
    }
}
