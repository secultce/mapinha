<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\DataFixtures\Entity\AgentFixtures;
use App\DataFixtures\Entity\SealFixtures;
use App\Entity\Seal;
use App\Tests\AbstractApiTestCase;
use App\Tests\Fixtures\SealTestFixtures;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class SealApiControllerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/seals';

    public function testCanCreateWithPartialRequestBody(): void
    {
        $requestBody = SealTestFixtures::partial();

        $client = static::apiClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        /** @var Seal $seal */
        $seal = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Seal::class, $requestBody['id']);

        $this->assertResponseBodySame([
            'id' => $requestBody['id'],
            'name' => $requestBody['name'],
            'description' => $requestBody['description'],
            'active' => $requestBody['active'],
            'createdBy' => ['id' => $requestBody['createdBy']],
            'createdAt' => $seal->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => null,
            'deletedAt' => null,
        ]);
    }

    public function testCanCreateWithCompleteRequestBody(): void
    {
        $requestBody = SealTestFixtures::complete();

        $client = static::apiClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        /** @var Seal $seal */
        $seal = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Seal::class, $requestBody['id']);

        $this->assertResponseBodySame([
            'id' => $requestBody['id'],
            'name' => $requestBody['name'],
            'description' => $requestBody['description'],
            'active' => $requestBody['active'],
            'createdBy' => ['id' => $requestBody['createdBy']],
            'expirationDate' => $seal->getExpirationDate()->format(DateTimeInterface::ATOM),
            'createdAt' => $seal->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => null,
            'deletedAt' => null,
        ]);
    }

    #[DataProvider('provideValidationCreateCases')]
    public function testValidationCreate(array $requestBody, array $expectedErrors): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationCreateCases(): array
    {
        $requestBody = SealTestFixtures::partial();

        return [
            'missing required fields' => [
                'requestBody' => [],
                'expectedErrors' => [
                    ['field' => 'id', 'message' => 'This value should not be blank.'],
                    ['field' => 'name', 'message' => 'This value should not be blank.'],
                    ['field' => 'description', 'message' => 'This value should not be blank.'],
                ],
            ],
            'id is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['id' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'id', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'name is not a string' => [
                'requestBody' => array_merge($requestBody, ['name' => 123]),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value should be of type string.'],
                ],
            ],
            'name is too short' => [
                'requestBody' => array_merge($requestBody, ['name' => 'a']),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'name is too long' => [
                'requestBody' => array_merge($requestBody, ['name' => str_repeat('a', 101)]),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too long. It should have 100 characters or less.'],
                ],
            ],
            'description is not a string' => [
                'requestBody' => array_merge($requestBody, ['description' => 123]),
                'expectedErrors' => [
                    ['field' => 'description', 'message' => 'This value should be of type string.'],
                ],
            ],
            'description is too short' => [
                'requestBody' => array_merge($requestBody, ['description' => 'a']),
                'expectedErrors' => [
                    ['field' => 'description', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'description is too long' => [
                'requestBody' => array_merge($requestBody, ['description' => str_repeat('a', 256)]),
                'expectedErrors' => [
                    ['field' => 'description', 'message' => 'This value is too long. It should have 255 characters or less.'],
                ],
            ],
            'active should be a boolean' => [
                'requestBody' => array_merge($requestBody, ['active' => 123]),
                'expectedErrors' => [
                    ['field' => 'active', 'message' => 'This value should be of type boolean.'],
                ],
            ],
            'createdBy should exist' => [
                'requestBody' => array_merge($requestBody, ['createdBy' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'createdBy', 'message' => 'This id does not exist.'],
                ],
            ],
            'expirationDate should be a string' => [
                'requestBody' => array_merge($requestBody, ['expirationDate' => 123]),
                'expectedErrors' => [
                    ['field' => 'expirationDate', 'message' => 'This value should be of type string.'],
                ],
            ],
        ];
    }

    public function testCanUpdate(): void
    {
        $requestBody = [
            'name' => 'Selo de teste',
            'description' => 'Descrição do selo',
        ];

        $url = sprintf('%s/%s', self::BASE_URL, SealFixtures::SEAL_ID_2);

        $client = self::apiClient();

        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        /** @var Seal $seal */
        $seal = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Seal::class, SealFixtures::SEAL_ID_2);

        $this->assertResponseBodySame([
            'id' => SealFixtures::SEAL_ID_2,
            'name' => $requestBody['name'],
            'description' => $requestBody['description'],
            'active' => $seal->isActive(),
            'createdBy' => ['id' => $seal->getCreatedBy()->getId()->toRfc4122()],
            'expirationDate' => $seal->getExpirationDate()->format(DateTimeInterface::ATOM),
            'createdAt' => $seal->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => $seal->getUpdatedAt()->format(DateTimeInterface::ATOM),
            'deletedAt' => null,
        ]);
    }

    #[DataProvider('provideValidationUpdateCases')]
    public function testValidationUpdate(array $requestBody, array $expectedErrors): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, SealFixtures::SEAL_ID_2);
        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationUpdateCases(): array
    {
        $requestBody = SealTestFixtures::partial();

        return [
            'name is not a string' => [
                'requestBody' => array_merge($requestBody, ['name' => 123]),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value should be of type string.'],
                ],
            ],
            'name is too short' => [
                'requestBody' => array_merge($requestBody, ['name' => 'a']),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'name is too long' => [
                'requestBody' => array_merge($requestBody, ['name' => str_repeat('a', 101)]),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too long. It should have 100 characters or less.'],
                ],
            ],
            'description is not a string' => [
                'requestBody' => array_merge($requestBody, ['description' => 123]),
                'expectedErrors' => [
                    ['field' => 'description', 'message' => 'This value should be of type string.'],
                ],
            ],
            'description is too short' => [
                'requestBody' => array_merge($requestBody, ['description' => 'a']),
                'expectedErrors' => [
                    ['field' => 'description', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'description is too long' => [
                'requestBody' => array_merge($requestBody, ['description' => str_repeat('a', 256)]),
                'expectedErrors' => [
                    ['field' => 'description', 'message' => 'This value is too long. It should have 255 characters or less.'],
                ],
            ],
            'active should be a boolean' => [
                'requestBody' => array_merge($requestBody, ['active' => 123]),
                'expectedErrors' => [
                    ['field' => 'active', 'message' => 'This value should be of type boolean.'],
                ],
            ],
            'createdBy should exist' => [
                'requestBody' => array_merge($requestBody, ['createdBy' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'createdBy', 'message' => 'This id does not exist.'],
                ],
            ],
            'expirationDate should be a string' => [
                'requestBody' => array_merge($requestBody, ['expirationDate' => 123]),
                'expectedErrors' => [
                    ['field' => 'expirationDate', 'message' => 'This value should be of type string.'],
                ],
            ],
            'expirationDate should be a valid date' => [
                'requestBody' => array_merge($requestBody, ['expirationDate' => '123']),
                'expectedErrors' => [
                    ['field' => 'expirationDate', 'message' => 'This value is not a valid date.'],
                ],
            ],
        ];
    }

    public function testGet(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_GET, self::BASE_URL);
        $response = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertCount(10, json_decode($response));

        /* @var Seal $seal */
        $seal = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Seal::class, SealFixtures::SEAL_ID_9);

        $this->assertJsonContains([
            'id' => SealFixtures::SEAL_ID_9,
            'name' => 'Artes e Ofícios',
            'description' => 'Selo que valoriza projetos relacionados a artesanato e cultura manual.',
            'active' => false,
            'createdBy' => [
                'id' => AgentFixtures::AGENT_ID_2,
            ],
            'expirationDate' => $seal->getExpirationDate()->format(DateTimeInterface::ATOM),
            'createdAt' => $seal->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => null,
            'deletedAt' => null,
        ]);
    }

    public function testGetASealItemWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, SealFixtures::SEAL_ID_3);

        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $seal = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Seal::class, SealFixtures::SEAL_ID_3);

        $this->assertResponseBodySame([
            'id' => SealFixtures::SEAL_ID_3,
            'name' => 'Sustentabilidade',
            'description' => 'Selo para iniciativas comprometidas com práticas sustentáveis.',
            'active' => false,
            'createdBy' => [
                'id' => AgentFixtures::AGENT_ID_3,
            ],
            'expirationDate' => $seal->getExpirationDate()->format(DateTimeInterface::ATOM),
            'createdAt' => $seal->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => null,
            'deletedAt' => null,
        ]);
    }

    public function testGetAResourceWhenNotFound(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_GET, sprintf('%s/%s', self::BASE_URL, Uuid::v4()->toRfc4122()));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested Seal was not found.',
            ],
        ]);
    }

    public function testDeleteAResourceWhenNotFound(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_DELETE, sprintf('%s/%s', self::BASE_URL, Uuid::v4()->toRfc4122()));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested Seal was not found.',
            ],
        ]);
    }

    public function testDeleteASealItemWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, SealFixtures::SEAL_ID_2);

        $client->request(Request::METHOD_DELETE, $url);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $client->request(Request::METHOD_GET, $url);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
