<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\DataFixtures\Entity\AgentFixtures;
use App\DataFixtures\Entity\OrganizationFixtures;
use App\Entity\Organization;
use App\Tests\AbstractApiTestCase;
use App\Tests\Fixtures\ImageTestFixtures;
use App\Tests\Fixtures\OrganizationTestFixtures;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class OrganizationApiControllerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/organizations';

    public function testCanCreateWithPartialRequestBody(): void
    {
        $requestBody = OrganizationTestFixtures::partial();

        $client = self::apiClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $organization = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Organization::class, $requestBody['id']);

        $this->assertResponseBodySame([
            'id' => $requestBody['id'],
            'name' => $requestBody['name'],
            'description' => null,
            'type' => 'Empresa',
            'image' => null,
            'agents' => [],
            'owner' => ['id' => AgentFixtures::AGENT_ID_1],
            'createdBy' => ['id' => AgentFixtures::AGENT_ID_1],
            'extraFields' => null,
            'createdAt' => $organization->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => null,
            'deletedAt' => null,
        ]);
    }

    public function testCanCreateWithCompleteRequestBody(): void
    {
        $requestBody = OrganizationTestFixtures::complete();

        $client = self::apiClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $organization = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Organization::class, $requestBody['id']);

        $this->assertResponseBodySame([
            'id' => $requestBody['id'],
            'name' => $requestBody['name'],
            'description' => 'Test Organization',
            'type' => 'Empresa',
            'image' => null,
            'agents' => array_map(fn ($id) => ['id' => $id], $requestBody['agents']),
            'owner' => ['id' => AgentFixtures::AGENT_ID_1],
            'createdBy' => ['id' => AgentFixtures::AGENT_ID_1],
            'extraFields' => [
                'instagram' => '@organizationtest',
            ],
            'createdAt' => $organization->getCreatedAt()->format(DateTimeInterface::ATOM),
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
        $requestBody = OrganizationTestFixtures::partial();

        return [
            'missing required fields' => [
                'requestBody' => [],
                'expectedErrors' => [
                    ['field' => 'id', 'message' => 'This value should not be blank.'],
                    ['field' => 'name', 'message' => 'This value should not be blank.'],
                ],
            ],
            'id is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['id' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'id', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'name should be string' => [
                'requestBody' => array_merge($requestBody, ['name' => 123]),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value should be of type string.'],
                ],
            ],
            'name too short' => [
                'requestBody' => array_merge($requestBody, ['name' => 'a']),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'name too long' => [
                'requestBody' => array_merge($requestBody, ['name' => str_repeat('a', 101)]),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too long. It should have 100 characters or less.'],
                ],
            ],
            'description should be string' => [
                'requestBody' => array_merge($requestBody, ['description' => 123]),
                'expectedErrors' => [
                    ['field' => 'description', 'message' => 'This value should be of type string.'],
                ],
            ],
            'description too long' => [
                'requestBody' => array_merge($requestBody, ['description' => str_repeat('a', 256)]),
                'expectedErrors' => [
                    ['field' => 'description', 'message' => 'This value is too long. It should have 255 characters or less.'],
                ],
            ],
            'createdBy should exists' => [
                'requestBody' => array_merge($requestBody, ['createdBy' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'createdBy', 'message' => 'This id does not exist.'],
                ],
            ],
            'owner should exists' => [
                'requestBody' => array_merge($requestBody, ['owner' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'owner', 'message' => 'This id does not exist.'],
                ],
            ],
            'extra fields should be a valid json object' => [
                'requestBody' => array_merge($requestBody, ['extraFields' => 'invalid']),
                'expectedErrors' => [
                    ['field' => 'extraFields', 'message' => 'This value should be of type json object.'],
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
        $this->assertCount(count(OrganizationFixtures::ORGANIZATIONS), json_decode($response));

        $organization = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Organization::class, OrganizationFixtures::ORGANIZATION_ID_1);

        $this->assertJsonContains([
            'id' => OrganizationFixtures::ORGANIZATION_ID_1,
            'name' => $organization->getName(),
            'description' => $organization->getDescription(),
            'type' => $organization->getType(),
            'image' => $organization->getImage(),
            'agents' => [
                [
                    'id' => AgentFixtures::AGENT_ID_1,
                ],
            ],
            'owner' => [
                'id' => AgentFixtures::AGENT_ID_1,
            ],
            'createdBy' => [
                'id' => AgentFixtures::AGENT_ID_1,
            ],
            'createdAt' => $organization->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => $organization->getUpdatedAt()->format(DateTimeInterface::ATOM),
            'deletedAt' => null,
        ]);
    }

    public function testGetAnOrganizationItemWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, OrganizationFixtures::ORGANIZATION_ID_3);

        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $organization = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Organization::class, OrganizationFixtures::ORGANIZATION_ID_3);

        $this->assertResponseBodySame([
            'id' => OrganizationFixtures::ORGANIZATION_ID_3,
            'name' => $organization->getName(),
            'description' => $organization->getDescription(),
            'type' => $organization->getType(),
            'image' => $organization->getImage(),
            'agents' => [
                [
                    'id' => AgentFixtures::AGENT_ID_3,
                ],
            ],
            'owner' => [
                'id' => AgentFixtures::AGENT_ID_3,
            ],
            'createdBy' => [
                'id' => AgentFixtures::AGENT_ID_3,
            ],
            'extraFields' => [
                'cnpj' => '04.117.525/0001-62',
                'email' => 'secretaria@igrejaderussas.org.br',
                'phone' => '(85) 99999-0003',
                'site' => 'https://www.igrejaderussas.org.br',
            ],
            'createdAt' => $organization->getCreatedAt()->format(DateTimeInterface::ATOM),
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
                'description' => 'The requested Organization was not found.',
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
                'description' => 'The requested Organization was not found.',
            ],
        ]);
    }

    public function testDeleteAnOrganizationItemWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, OrganizationFixtures::ORGANIZATION_ID_6);

        $client->request(Request::METHOD_DELETE, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testCanUpdate(): void
    {
        $requestBody = OrganizationTestFixtures::complete();
        unset($requestBody['id']);

        $url = sprintf('%s/%s', self::BASE_URL, OrganizationFixtures::ORGANIZATION_ID_4);
        $client = self::apiClient();

        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $organization = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Organization::class, OrganizationFixtures::ORGANIZATION_ID_4);

        $this->assertResponseBodySame([
            'id' => OrganizationFixtures::ORGANIZATION_ID_4,
            'name' => $requestBody['name'],
            'description' => $requestBody['description'],
            'type' => 'Empresa',
            'image' => $organization->getImage(),
            'agents' => array_map(fn ($id) => ['id' => $id], $requestBody['agents']),
            'owner' => ['id' => AgentFixtures::AGENT_ID_1],
            'createdBy' => ['id' => AgentFixtures::AGENT_ID_1],
            'extraFields' => $requestBody['extraFields'],
            'createdAt' => $organization->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => $organization->getUpdatedAt()->format(DateTimeInterface::ATOM),
            'deletedAt' => null,
        ]);
    }

    #[DataProvider('provideValidationUpdateCases')]
    public function testValidationUpdate(array $requestBody, array $expectedErrors): void
    {
        $client = self::apiClient();
        $url = sprintf('%s/%s', self::BASE_URL, OrganizationFixtures::ORGANIZATION_ID_3);
        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationUpdateCases(): array
    {
        $requestBody = OrganizationTestFixtures::partial();

        return [
            'name should be string' => [
                'requestBody' => array_merge($requestBody, ['name' => 123]),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value should be of type string.'],
                ],
            ],
            'name too short' => [
                'requestBody' => array_merge($requestBody, ['name' => 'a']),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'name too long' => [
                'requestBody' => array_merge($requestBody, ['name' => str_repeat('a', 101)]),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too long. It should have 100 characters or less.'],
                ],
            ],
            'description should be string' => [
                'requestBody' => array_merge($requestBody, ['description' => 123]),
                'expectedErrors' => [
                    ['field' => 'description', 'message' => 'This value should be of type string.'],
                ],
            ],
            'description too long' => [
                'requestBody' => array_merge($requestBody, ['description' => str_repeat('a', 256)]),
                'expectedErrors' => [
                    ['field' => 'description', 'message' => 'This value is too long. It should have 255 characters or less.'],
                ],
            ],
            'createdBy should exists' => [
                'requestBody' => array_merge($requestBody, ['createdBy' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'createdBy', 'message' => 'This id does not exist.'],
                ],
            ],
            'owner should exists' => [
                'requestBody' => array_merge($requestBody, ['owner' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'owner', 'message' => 'This id does not exist.'],
                ],
            ],
            'agents should exists' => [
                'requestBody' => array_merge($requestBody, [
                    'agents' => [
                        Uuid::v4()->toRfc4122(),
                        Uuid::v4()->toRfc4122(),
                    ],
                ]),
                'expectedErrors' => [
                    ['field' => 'agents[0]', 'message' => 'This id does not exist.'],
                    ['field' => 'agents[1]', 'message' => 'This id does not exist.'],
                ],
            ],
            'agents should be valid UUIDs' => [
                'requestBody' => array_merge($requestBody, [
                    'agents' => [
                        'invalid-identifier',
                        'invalid-identifier',
                    ],
                ]),
                'expectedErrors' => [
                    ['field' => 'agents[0]', 'message' => 'This value is not a valid UUID.'],
                    ['field' => 'agents[1]', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'extra fields should be a valid json object' => [
                'requestBody' => array_merge($requestBody, ['extraFields' => 'invalid']),
                'expectedErrors' => [
                    ['field' => 'extraFields', 'message' => 'This value should be of type json object.'],
                ],
            ],
        ];
    }

    public function testCanUpdateImageWithMultipartFormData(): void
    {
        $file = ImageTestFixtures::getImageValid();

        $url = sprintf('%s/%s/images', self::BASE_URL, OrganizationFixtures::ORGANIZATION_ID_10);

        $client = self::apiClient();
        $client->request(
            Request::METHOD_POST,
            $url,
            files: ['image' => $file],
            server: [
                'CONTENT_TYPE' => 'multipart/form-data',
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $organization = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Organization::class, OrganizationFixtures::ORGANIZATION_ID_10);

        $this->assertResponseBodySame([
            'id' => OrganizationFixtures::ORGANIZATION_ID_10,
            'name' => $organization->getName(),
            'description' => $organization->getDescription(),
            'type' => $organization->getType(),
            'image' => $organization->getImage(),
            'agents' => [
                [
                    'id' => AgentFixtures::AGENT_ID_1,
                ],
            ],
            'owner' => [
                'id' => AgentFixtures::AGENT_ID_1,
            ],
            'createdBy' => [
                'id' => AgentFixtures::AGENT_ID_1,
            ],
            'extraFields' => [
                'cnpj' => '00.000.000/0001-10',
                'email' => 'acr@example.com',
                'phone' => '(85) 99999-0010',
                'site' => 'https://www.acr.com.br',
            ],
            'createdAt' => $organization->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => $organization->getUpdatedAt()->format(DateTimeInterface::ATOM),
            'deletedAt' => null,
        ]);
    }

    #[DataProvider('provideValidationUpdateImageCases')]
    public function testValidationUpdateImage(array $requestBody, $file, array $expectedErrors): void
    {
        $url = sprintf('%s/%s/images', self::BASE_URL, OrganizationFixtures::ORGANIZATION_ID_7);

        $client = self::apiClient();
        $client->request(
            Request::METHOD_POST,
            $url,
            files: ['image' => $file],
            server: [
                'CONTENT_TYPE' => 'multipart/form-data',
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationUpdateImageCases(): array
    {
        $requestBody = OrganizationTestFixtures::partial();
        unset($requestBody['id']);

        return [
            'image not supported' => [
                'requestBody' => $requestBody,
                'file' => ImageTestFixtures::getGif(),
                'expectedErrors' => [
                    ['field' => 'image', 'message' => 'The mime type of the file is invalid ("image/gif"). Allowed mime types are "image/png", "image/jpg", "image/jpeg".'],
                ],
            ],
            'image size' => [
                'requestBody' => $requestBody,
                'file' => ImageTestFixtures::getImageMoreThan2mb(),
                'expectedErrors' => [
                    ['field' => 'image', 'message' => 'The file is too large (2.5 MB). Allowed maximum size is 2 MB.'],
                ],
            ],
        ];
    }
}
