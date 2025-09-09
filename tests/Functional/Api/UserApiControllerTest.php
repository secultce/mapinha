<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\DataFixtures\Entity\AgentFixtures;
use App\DataFixtures\Entity\UserFixtures;
use App\Entity\User;
use App\Enum\UserStatusEnum;
use App\Tests\AbstractApiTestCase;
use App\Tests\Fixtures\UserTestFixtures;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserApiControllerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/users';

    private ?ParameterBagInterface $parameterBag = null;

    protected function setUp(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $this->parameterBag = $container->get(ParameterBagInterface::class);
    }

    public function testCanCreateWithPartialRequestBody(): void
    {
        $requestBody = UserTestFixtures::partial();

        $client = self::apiClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $user = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(User::class, $requestBody['id']);

        $this->assertResponseBodySame([
            'id' => $requestBody['id'],
            'firstname' => $requestBody['firstname'],
            'lastname' => $requestBody['lastname'],
            'socialName' => null,
            'image' => null,
            'agents' => [
                [
                    'id' => $user->getAgents()->first()->getId()->toRfc4122(),
                ],
            ],
            'status' => UserStatusEnum::ACTIVE->value,
            'createdAt' => $user->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => null,
            'deletedAt' => null,
        ]);
    }

    public function testCanCreateWithCompleteRequestBody(): void
    {
        $requestBody = UserTestFixtures::complete();

        $client = self::apiClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        /* @var User $user */
        $user = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(User::class, $requestBody['id']);

        $this->assertResponseBodySame([
            'id' => $requestBody['id'],
            'firstname' => $requestBody['firstname'],
            'lastname' => $requestBody['lastname'],
            'socialName' => $requestBody['socialName'],
            'image' => $user->getImage(),
            'agents' => [
                [
                    'id' => $user->getAgents()->first()->getId()->toRfc4122(),
                ],
            ],
            'status' => UserStatusEnum::ACTIVE->value,
            'createdAt' => $user->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => null,
            'deletedAt' => null,
        ]);
    }

    #[DataProvider('provideValidationCreateCases')]
    public function testValidationCreate(array $requestBody, array $expectedErrors): void
    {
        $client = self::apiClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationCreateCases(): array
    {
        $requestBody = UserTestFixtures::partial();

        return [
            'missing required fields' => [
                'requestBody' => [],
                'expectedErrors' => [
                    ['field' => 'id', 'message' => 'This value should not be blank.'],
                    ['field' => 'firstname', 'message' => 'This value should not be blank.'],
                    ['field' => 'lastname', 'message' => 'This value should not be blank.'],
                    ['field' => 'email', 'message' => 'This value should not be blank.'],
                    ['field' => 'password', 'message' => 'This value should not be blank.'],
                ],
            ],
            'id is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['id' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'id', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            ...self::getValidations($requestBody),
        ];
    }

    public function testCanUpdate(): void
    {
        $requestBody = UserTestFixtures::complete();
        unset($requestBody['id']);

        $url = sprintf('%s/%s', self::BASE_URL, UserFixtures::USER_ID_5);
        $client = self::apiClient();

        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $user = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(User::class, UserFixtures::USER_ID_5);

        $this->assertResponseBodySame([
            'id' => UserFixtures::USER_ID_5,
            'firstname' => $requestBody['firstname'],
            'lastname' => $requestBody['lastname'],
            'socialName' => $requestBody['socialName'],
            'image' => $user->getImage(),
            'agents' => [
                ['id' => AgentFixtures::AGENT_ID_5],
            ],
            'status' => UserStatusEnum::ACTIVE->value,
            'createdAt' => $user->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => $user->getUpdatedAt()->format(DateTimeInterface::ATOM),
            'deletedAt' => null,
        ]);
    }

    public function testCanUpdateImage(): void
    {
        $requestBody = UserTestFixtures::complete();
        unset($requestBody['id']);

        $client = self::apiClient();

        /* @var User $user */
        $userCreated = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(User::class, UserFixtures::USER_ID_5);

        $firstImage = str_replace($this->parameterBag->get('app.url.storage'), '', $userCreated->getImage());
        file_exists($firstImage);

        $url = sprintf('%s/%s', self::BASE_URL, UserFixtures::USER_ID_5);
        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $userUpdated = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(User::class, UserFixtures::USER_ID_5);

        $this->assertResponseBodySame([
            'id' => UserFixtures::USER_ID_5,
            'firstname' => $requestBody['firstname'],
            'lastname' => $requestBody['lastname'],
            'socialName' => $requestBody['socialName'],
            'image' => $userUpdated->getImage(),
            'agents' => [
                ['id' => AgentFixtures::AGENT_ID_5],
            ],
            'status' => UserStatusEnum::ACTIVE->value,
            'createdAt' => $userUpdated->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => $userUpdated->getUpdatedAt()->format(DateTimeInterface::ATOM),
            'deletedAt' => null,
        ]);
    }

    #[DataProvider('provideValidationUpdateCases')]
    public function testValidationUpdate(array $requestBody, array $expectedErrors): void
    {
        $client = self::apiClient();
        $url = sprintf('%s/%s', self::BASE_URL, UserFixtures::USER_ID_6);
        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationUpdateCases(): array
    {
        $requestBody = UserTestFixtures::partial();
        $isUpdate = true;

        return self::getValidations($requestBody, $isUpdate);
    }

    private static function getValidations(array $requestBody, bool $isUpdate = false): array
    {
        if ($isUpdate) {
            unset($requestBody['id']);
        }

        return [
            'firstname should be string' => [
                'requestBody' => array_merge($requestBody, ['firstname' => 123]),
                'expectedErrors' => [
                    ['field' => 'firstname', 'message' => 'This value should be of type string.'],
                ],
            ],
            'firstname too short' => [
                'requestBody' => array_merge($requestBody, ['firstname' => 'a']),
                'expectedErrors' => [
                    ['field' => 'firstname', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'firstname too long' => [
                'requestBody' => array_merge($requestBody, ['firstname' => str_repeat('a', 51)]),
                'expectedErrors' => [
                    ['field' => 'firstname', 'message' => 'This value is too long. It should have 50 characters or less.'],
                ],
            ],
            'lastname should be string' => [
                'requestBody' => array_merge($requestBody, ['lastname' => 123]),
                'expectedErrors' => [
                    ['field' => 'lastname', 'message' => 'This value should be of type string.'],
                ],
            ],
            'lastname too short' => [
                'requestBody' => array_merge($requestBody, ['lastname' => 'a']),
                'expectedErrors' => [
                    ['field' => 'lastname', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'lastname too long' => [
                'requestBody' => array_merge($requestBody, ['lastname' => str_repeat('a', 51)]),
                'expectedErrors' => [
                    ['field' => 'lastname', 'message' => 'This value is too long. It should have 50 characters or less.'],
                ],
            ],
            'socialName should be string' => [
                'requestBody' => array_merge($requestBody, ['socialName' => 123]),
                'expectedErrors' => [
                    ['field' => 'socialName', 'message' => 'This value should be of type string.'],
                ],
            ],
            'socialName too long' => [
                'requestBody' => array_merge($requestBody, ['socialName' => str_repeat('a', 101)]),
                'expectedErrors' => [
                    ['field' => 'socialName', 'message' => 'This value is too long. It should have 100 characters or less.'],
                ],
            ],
            'email invalid' => [
                'requestBody' => array_merge($requestBody, ['email' => 'user.test']),
                'expectedErrors' => [
                    ['field' => 'email', 'message' => 'This value is not a valid email address.'],
                ],
            ],
            'password too weak' => [
                'requestBody' => array_merge($requestBody, ['password' => '123456']),
                'expectedErrors' => [
                    ['field' => 'password', 'message' => 'password.too_weak'],
                ],
            ],
        ];
    }
}
