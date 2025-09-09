<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\DataFixtures\Entity\AgentFixtures;
use App\DataFixtures\Entity\InitiativeFixtures;
use App\DataFixtures\Entity\SpaceFixtures;
use App\Entity\Initiative;
use App\Tests\AbstractApiTestCase;
use App\Tests\Fixtures\ImageTestFixtures;
use App\Tests\Fixtures\InitiativeTestFixtures;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class InitiativeApiControllerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/initiatives';

    public function testGet(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_GET, self::BASE_URL);
        $response = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertCount(count(InitiativeFixtures::INITIATIVES), json_decode($response));

        $this->assertJsonContains([
            'id' => InitiativeFixtures::INITIATIVE_ID_1,
            'name' => 'Vozes do Sertão',
            'image' => null,
            'parent' => null,
            'space' => [
                'id' => SpaceFixtures::SPACE_ID_4,
            ],
            'organizationFrom' => null,
            'organizationTo' => null,
            'createdBy' => [
                'id' => AgentFixtures::AGENT_ID_1,
            ],
            'createdAt' => '2024-07-10T11:30:00+00:00',
            'updatedAt' => '2024-07-10T12:20:00+00:00',
            'deletedAt' => null,
        ]);
    }

    public function testGetAnInitiativeItemWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, InitiativeFixtures::INITIATIVE_ID_1);

        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseBodySame([
            'id' => InitiativeFixtures::INITIATIVE_ID_1,
            'name' => 'Vozes do Sertão',
            'image' => null,
            'parent' => null,
            'space' => [
                'id' => SpaceFixtures::SPACE_ID_4,
            ],
            'organizationFrom' => null,
            'organizationTo' => null,
            'createdBy' => [
                'id' => AgentFixtures::AGENT_ID_1,
            ],
            'extraFields' => [
                'culturalLanguage' => 'Musical',
                'period' => [
                    'startDate' => '2024-08-01',
                    'endDate' => '2024-08-31',
                ],
                'shortDescription' => 'Vozes do Sertão é um festival de música que reúne artistas de todo o Brasil para celebrar a cultura nordestina.',
            ],
            'createdAt' => '2024-07-10T11:30:00+00:00',
            'updatedAt' => '2024-07-10T12:20:00+00:00',
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
                'description' => 'The requested Initiative was not found.',
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
                'description' => 'The requested Initiative was not found.',
            ],
        ]);
    }

    public function testDeleteAnInitiativeItemWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, InitiativeFixtures::INITIATIVE_ID_4);

        $client->request(Request::METHOD_DELETE, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testCanCreateWithPartialRequestBody(): void
    {
        $requestBody = InitiativeTestFixtures::partial();

        $client = self::apiClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $initiative = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Initiative::class, $requestBody['id']);

        $this->assertResponseBodySame([
            'id' => $requestBody['id'],
            'name' => $requestBody['name'],
            'image' => null,
            'parent' => null,
            'space' => null,
            'organizationFrom' => null,
            'organizationTo' => null,
            'createdBy' => ['id' => AgentFixtures::AGENT_ID_1],
            'extraFields' => null,
            'createdAt' => $initiative->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => null,
            'deletedAt' => null,
        ]);
    }

    public function testCanCreateWithCompleteRequestBody(): void
    {
        $requestBody = InitiativeTestFixtures::complete();

        $client = self::apiClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $initiative = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Initiative::class, $requestBody['id']);

        $this->assertResponseBodySame([
            'id' => $requestBody['id'],
            'name' => $requestBody['name'],
            'image' => null,
            'parent' => [
                'id' => $requestBody['parent'],
                'name' => 'Raízes e Tradições',
                'image' => $initiative->getParent()->getImage(),
                'space' => null,
                'organizationFrom' => null,
                'organizationTo' => null,
                'createdBy' => ['id' => AgentFixtures::AGENT_ID_1],
                'extraFields' => [
                    'culturalLanguage' => 'Cultural',
                    'period' => [
                        'startDate' => '2024-08-15',
                        'endDate' => '2024-09-15',
                    ],
                    'shortDescription' => 'Raízes e Tradições é uma exposição que reúne artesãos de todo o Brasil para celebrar a cultura nordestina.',
                ],
                'createdAt' => '2024-07-11T10:49:00+00:00',
                'updatedAt' => null,
                'deletedAt' => null,
            ],
            'space' => [
                'id' => $requestBody['space'],
            ],
            'organizationFrom' => null,
            'organizationTo' => null,
            'createdBy' => ['id' => AgentFixtures::AGENT_ID_1],
            'extraFields' => $requestBody['extraFields'],
            'createdAt' => $initiative->getCreatedAt()->format(DateTimeInterface::ATOM),
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
        $requestBody = InitiativeTestFixtures::partial();

        return [
            'missing required fields' => [
                'requestBody' => [],
                'expectedErrors' => [
                    [
                        'field' => 'id',
                        'message' => 'This value should not be blank.',
                    ],
                    [
                        'field' => 'name',
                        'message' => 'This value should not be blank.',
                    ],
                    [
                        'field' => 'createdBy',
                        'message' => 'This value should not be blank.',
                    ],
                ],
            ],
            'id is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['id' => 'invalid-uuid']),
                'expectedErrors' => [
                    [
                        'field' => 'id',
                        'message' => 'This value is not a valid UUID.',
                    ],
                ],
            ],
            'name is not a string' => [
                'requestBody' => array_merge($requestBody, ['name' => 123]),
                'expectedErrors' => [
                    [
                        'field' => 'name',
                        'message' => 'This value should be of type string.',
                    ],
                ],
            ],
            'name is too short' => [
                'requestBody' => array_merge($requestBody, ['name' => 'a']),
                'expectedErrors' => [
                    [
                        'field' => 'name',
                        'message' => 'This value is too short. It should have 2 characters or more.',
                    ],
                ],
            ],
            'name is too long' => [
                'requestBody' => array_merge($requestBody, ['name' => str_repeat('a', 101)]),
                'expectedErrors' => [
                    [
                        'field' => 'name',
                        'message' => 'This value is too long. It should have 100 characters or less.',
                    ],
                ],
            ],
            'createdBy should exist' => [
                'requestBody' => array_merge($requestBody, ['createdBy' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    [
                        'field' => 'createdBy',
                        'message' => 'This id does not exist.',
                    ],
                ],
            ],
            'createdBy is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['createdBy' => 'invalid-uuid']),
                'expectedErrors' => [
                    [
                        'field' => 'createdBy',
                        'message' => 'This value is not a valid UUID.',
                    ],
                ],
            ],
            'parent should exist' => [
                'requestBody' => array_merge($requestBody, ['parent' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    [
                        'field' => 'parent',
                        'message' => 'This id does not exist.',
                    ],
                ],
            ],
            'parent is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['parent' => 'invalid-uuid']),
                'expectedErrors' => [
                    [
                        'field' => 'parent',
                        'message' => 'This value is not a valid UUID.',
                    ],
                ],
            ],
            'space should exist' => [
                'requestBody' => array_merge($requestBody, ['space' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    [
                        'field' => 'space',
                        'message' => 'This id does not exist.',
                    ],
                ],
            ],
            'space is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['space' => 'invalid-uuid']),
                'expectedErrors' => [
                    [
                        'field' => 'space',
                        'message' => 'This value is not a valid UUID.',
                    ],
                ],
            ],
            'extraFields should be a valid JSON' => [
                'requestBody' => array_merge($requestBody, ['extraFields' => 'invalid-json']),
                'expectedErrors' => [
                    [
                        'field' => 'extraFields',
                        'message' => 'This value should be of type json object.',
                    ],
                ],
            ],
        ];
    }

    public function testCanUpdate(): void
    {
        $requestBody = InitiativeTestFixtures::complete();
        unset($requestBody['id']);

        $url = sprintf('%s/%s', self::BASE_URL, InitiativeFixtures::INITIATIVE_ID_8);

        $client = self::apiClient();

        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $initiative = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Initiative::class, InitiativeFixtures::INITIATIVE_ID_8);

        $this->assertResponseBodySame([
            'id' => InitiativeFixtures::INITIATIVE_ID_8,
            'name' => $requestBody['name'],
            'image' => null,
            'parent' => [
                'id' => InitiativeFixtures::INITIATIVE_ID_2,
                'name' => 'Raízes e Tradições',
                'image' => $initiative->getParent()->getImage(),
                'space' => null,
                'organizationFrom' => null,
                'organizationTo' => null,
                'createdBy' => ['id' => AgentFixtures::AGENT_ID_1],
                'extraFields' => [
                    'culturalLanguage' => 'Cultural',
                    'period' => [
                        'startDate' => '2024-08-15',
                        'endDate' => '2024-09-15',
                    ],
                    'shortDescription' => 'Raízes e Tradições é uma exposição que reúne artesãos de todo o Brasil para celebrar a cultura nordestina.',
                ],
                'createdAt' => '2024-07-11T10:49:00+00:00',
                'updatedAt' => null,
                'deletedAt' => null,
            ],
            'space' => [
                'id' => SpaceFixtures::SPACE_ID_4,
            ],
            'organizationFrom' => null,
            'organizationTo' => null,
            'createdBy' => ['id' => AgentFixtures::AGENT_ID_1],
            'extraFields' => $requestBody['extraFields'],
            'createdAt' => $initiative->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => $initiative->getUpdatedAt()->format(DateTimeInterface::ATOM),
            'deletedAt' => null,
        ]);
    }

    #[DataProvider('provideValidationUpdateCases')]
    public function testValidationUpdate(array $requestBody, array $expectedErrors): void
    {
        $client = self::apiClient();
        $url = sprintf('%s/%s', self::BASE_URL, InitiativeFixtures::INITIATIVE_ID_8);
        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationUpdateCases(): array
    {
        $requestBody = InitiativeTestFixtures::partial();

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
            'parent should exists' => [
                'requestBody' => array_merge($requestBody, ['parent' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'parent', 'message' => 'This id does not exist.'],
                ],
            ],
            'parent is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['parent' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'parent', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'createdBy should exists' => [
                'requestBody' => array_merge($requestBody, ['createdBy' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'createdBy', 'message' => 'This id does not exist.'],
                ],
            ],
            'createdBy is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['createdBy' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'createdBy', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'space should exists' => [
                'requestBody' => array_merge($requestBody, ['space' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'space', 'message' => 'This id does not exist.'],
                ],
            ],
            'space is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['space' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'space', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'extraFields should be a valid JSON' => [
                'requestBody' => array_merge($requestBody, ['extraFields' => 'invalid-json']),
                'expectedErrors' => [
                    ['field' => 'extraFields', 'message' => 'This value should be of type json object.'],
                ],
            ],
        ];
    }

    public function testCanUpdateImageWithMultipartFormData(): void
    {
        $file = ImageTestFixtures::getImageValid();

        $url = sprintf('%s/%s/images', self::BASE_URL, InitiativeFixtures::INITIATIVE_ID_1);

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

        /* @var Initiative $initiative */
        $initiative = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Initiative::class, InitiativeFixtures::INITIATIVE_ID_1);

        $this->assertResponseBodySame([
            'id' => InitiativeFixtures::INITIATIVE_ID_1,
            'name' => 'Vozes do Sertão',
            'image' => $initiative->getImage(),
            'parent' => null,
            'space' => [
                'id' => SpaceFixtures::SPACE_ID_4,
            ],
            'organizationFrom' => null,
            'organizationTo' => null,
            'createdBy' => [
                'id' => AgentFixtures::AGENT_ID_1,
            ],
            'extraFields' => [
                'culturalLanguage' => 'Musical',
                'period' => [
                    'startDate' => '2024-08-01',
                    'endDate' => '2024-08-31',
                ],
                'shortDescription' => 'Vozes do Sertão é um festival de música que reúne artistas de todo o Brasil para celebrar a cultura nordestina.',
            ],
            'createdAt' => $initiative->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => $initiative->getUpdatedAt()->format(DateTimeInterface::ATOM),
            'deletedAt' => null,
        ]);
    }

    #[DataProvider('provideValidationUpdateImageCases')]
    public function testValidationUpdateImage(array $requestBody, $file, array $expectedErrors): void
    {
        $url = sprintf('%s/%s/images', self::BASE_URL, InitiativeFixtures::INITIATIVE_ID_5);

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
        $requestBody = InitiativeTestFixtures::partial();
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
