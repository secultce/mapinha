<?php

declare(strict_types=1);

namespace App\DataFixtures\Entity;

use App\Entity\User;
use App\Enum\UserRolesEnum;
use App\Enum\UserStatusEnum;
use App\Service\Interface\FileServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class UserFixtures extends AbstractFixture
{
    public const string USER_ID_PREFIX = 'user';
    public const string USER_ID_1 = '2604e656-57dc-4e1c-9fa8-efdf4a00b203';
    public const string USER_ID_2 = '01813ac3-814b-45a1-ba19-b3ba771ea1b1';
    public const string USER_ID_3 = 'cd393563-759d-4754-b7ce-13c09a7581b9';
    public const string USER_ID_4 = '62196dd2-6efb-43f3-a75c-ec5dec346d5e';
    public const string USER_ID_5 = '4ed47ceb-8415-4710-a072-3de52843c616';
    public const string USER_ID_6 = 'd860ac77-553e-4fcd-9ef7-151a7c76c621';
    public const string USER_ID_7 = 'bc62551f-aa65-46b4-8e3a-0661d821dd30';
    public const string USER_ID_8 = 'a5e60016-2712-4651-bea8-3a91941e7f16';
    public const string USER_ID_9 = 'fc3c6632-cbff-4dfd-9fb3-1560fec620cf';
    public const string USER_ID_10 = 'b4f5e01c-e826-45bf-975d-1ac34cfe6e8c';

    public const string DEFAULT_PASSWORD = 'Aurora@2024';

    public const array USERS = [
        [
            'id' => self::USER_ID_1,
            'firstname' => 'Fro',
            'lastname' => ' Alessandro Feitoza',
            'socialName' => 'Alessandro Feitoza',
            'email' => 'alessandrofeitoza@example.com',
            'image' => null,
            'status' => UserStatusEnum::ACTIVE->value,
            'roles' => [UserRolesEnum::ROLE_ADMIN->value],
            'createdAt' => '2024-07-10T11:30:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::USER_ID_2,
            'firstname' => 'Henrique',
            'lastname' => 'Lopes Lima',
            'socialName' => 'Henrique Lima',
            'email' => 'henriquelopeslima@example.com',
            'image' => null,
            'status' => UserStatusEnum::ACTIVE->value,
            'roles' => [UserRolesEnum::ROLE_ADMIN->value],
            'createdAt' => '2024-07-11T10:49:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::USER_ID_3,
            'firstname' => 'Anna Kelly',
            'lastname' => 'Moura Balbino',
            'socialName' => 'Kelly Moura',
            'email' => 'kellymoura@example.com',
            'image' => null,
            'status' => UserStatusEnum::ACTIVE->value,
            'roles' => [UserRolesEnum::ROLE_USER->value],
            'createdAt' => '2024-07-16T17:22:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::USER_ID_4,
            'firstname' => 'Sara Jenifer',
            'lastname' => 'Camilo',
            'socialName' => 'Sara Camilo',
            'email' => 'saracamilo@example.com',
            'image' => null,
            'status' => UserStatusEnum::ACTIVE->value,
            'roles' => [UserRolesEnum::ROLE_USER->value],
            'createdAt' => '2024-07-17T15:12:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::USER_ID_5,
            'firstname' => 'Talyson',
            'lastname' => 'Soares',
            'socialName' => null,
            'email' => 'talysonsoares@example.com',
            'image' => null,
            'status' => UserStatusEnum::ACTIVE->value,
            'roles' => [UserRolesEnum::ROLE_USER->value],
            'createdAt' => '2024-07-22T16:20:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::USER_ID_6,
            'firstname' => 'Raquel',
            'lastname' => 'Ben Labão',
            'socialName' => null,
            'email' => 'raquelbenlabao@example.com',
            'image' => null,
            'status' => UserStatusEnum::ACTIVE->value,
            'roles' => [UserRolesEnum::ROLE_USER->value],
            'createdAt' => '2024-08-10T11:26:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::USER_ID_7,
            'firstname' => 'Lucas',
            'lastname' => 'Pamplona',
            'socialName' => 'Pampleno',
            'email' => 'lucaspamplona@example.com',
            'image' => null,
            'status' => UserStatusEnum::ACTIVE->value,
            'roles' => [UserRolesEnum::ROLE_USER->value],
            'createdAt' => '2024-08-11T15:54:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::USER_ID_8,
            'firstname' => 'Maria',
            'lastname' => 'de Betânia',
            'socialName' => null,
            'email' => 'mariadebetania@example.com',
            'image' => null,
            'status' => UserStatusEnum::ACTIVE->value,
            'roles' => [UserRolesEnum::ROLE_USER->value],
            'createdAt' => '2024-08-12T14:24:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::USER_ID_9,
            'firstname' => 'Abner',
            'lastname' => 'Carvalho',
            'socialName' => 'Abner C.',
            'email' => 'abnercarvalho@example.com',
            'image' => null,
            'status' => UserStatusEnum::AWAITING_CONFIRMATION->value,
            'roles' => [UserRolesEnum::ROLE_USER->value],
            'createdAt' => '2024-08-13T20:25:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::USER_ID_10,
            'firstname' => 'Paulo',
            'lastname' => 'de Tarso',
            'socialName' => null,
            'email' => 'paulodetarso@example.com',
            'image' => null,
            'status' => UserStatusEnum::ACTIVE->value,
            'roles' => [UserRolesEnum::ROLE_ADMIN->value],
            'createdAt' => '2024-08-14T10:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
    ];

    public const array USERS_UPDATED = [
        [
            'id' => self::USER_ID_1,
            'firstname' => 'Francisco',
            'lastname' => ' Alessandro Feitoza',
            'socialName' => 'Alessandro Feitoza',
            'email' => 'alessandrofeitoza@example.com',
            'image' => null,
            'status' => UserStatusEnum::ACTIVE->value,
            'roles' => [UserRolesEnum::ROLE_ADMIN->value],
            'createdAt' => '2024-07-10T11:30:00+00:00',
            'updatedAt' => '2024-07-10T11:35:00+00:00',
            'deletedAt' => null,
        ],
    ];

    private string $password;

    public function __construct(
        EntityManagerInterface $entityManager,
        TokenStorageInterface $tokenStorage,
        private readonly SerializerInterface $serializer,
        private readonly PasswordHasherFactoryInterface $passwordHasherFactory,
        private readonly FileServiceInterface $fileService,
        private readonly ParameterBagInterface $parameterBag,
    ) {
        parent::__construct($entityManager, $tokenStorage);

        $this->password = $this->passwordHasherFactory->getPasswordHasher(User::class)->hash(self::DEFAULT_PASSWORD);
    }

    public function load(ObjectManager $manager): void
    {
        $this->createUsers($manager);
        $this->updateUsers($manager);
        $this->manualLogout();
    }

    private function createUsers(ObjectManager $manager): void
    {
        $counter = 0;

        foreach (self::USERS as $userData) {
            if (5 > $counter) {
                $file = $this->fileService->uploadImage($this->parameterBag->get('app.dir.user.profile'), ImageFixtures::getUserImage());
                $userData['image'] = $file;
            }

            /** @var User $user */
            $user = $this->serializer->denormalize($userData, User::class);
            $user->setPassword($this->password);
            $this->setReference(sprintf('%s-%s', self::USER_ID_PREFIX, $userData['id']), $user);

            $manager->persist($user);
            $counter++;
        }

        $manager->flush();
    }

    private function updateUsers(ObjectManager $manager): void
    {
        foreach (self::USERS_UPDATED as $userData) {
            unset($userData['image']);

            $userObj = $this->getReference(sprintf('%s-%s', self::USER_ID_PREFIX, $userData['id']), User::class);

            /** @var User $user */
            $user = $this->serializer->denormalize($userData, User::class, context: ['object_to_populate' => $userObj]);

            $this->manualLogin($user->getId()->toRfc4122());

            $manager->persist($user);
        }

        $manager->flush();
        $this->manualLogout();
    }
}
