<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\DataFixtures\Entity\ActivityAreaFixtures;
use App\Entity\ActivityArea;
use App\Tests\AbstractApiTestCase;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class ActivityAreaApiControllerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/activity-areas';

    public function testCreateActivityAreaWithSuccess(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode([
            'id' => Uuid::v4()->toRfc4122(),
            'name' => 'Test Activity Area',
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $activityArea = $client->getContainer()->get(EntityManagerInterface::class)
            ->getRepository(ActivityArea::class)
            ->findOneBy(['name' => 'Test Activity Area']);

        $this->assertNotNull($activityArea);
        $this->assertResponseBodySame([
            'id' => $activityArea->getId()->toRfc4122(),
            'name' => $activityArea->getName(),
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
        $requestBody = [
            'id' => Uuid::v4()->toRfc4122(),
            'name' => 'Test Activity Area',
        ];

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
                'requestBody' => array_merge($requestBody, ['name' => str_repeat('a', 21)]),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too long. It should have 20 characters or less.'],
                ],
            ],
        ];
    }

    public function testGetListActivityAreas(): void
    {
        $client = static::apiClient();
        $client->request(Request::METHOD_GET, self::BASE_URL);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
        $this->assertNotEmpty($response);
    }

    public function testGetOneActivityAreaItemWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, ActivityAreaFixtures::ACTIVITY_AREA_ID_1);
        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $activityArea = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(ActivityArea::class, ActivityAreaFixtures::ACTIVITY_AREA_ID_1);

        $this->assertResponseBodySame([
            'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_1,
            'name' => $activityArea->getName(),
        ]);
    }

    public function testGetAnActivityAreaWhenNotFound(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_GET, sprintf('%s/%s', self::BASE_URL, Uuid::v4()->toRfc4122()));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested ActivityArea was not found.',
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
                'description' => 'The requested ActivityArea was not found.',
            ],
        ]);
    }

    public function testDeleteAActivityAreaItemWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, ActivityAreaFixtures::ACTIVITY_AREA_ID_2);

        $client->request(Request::METHOD_DELETE, $url);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $client->request(Request::METHOD_GET, $url);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testDeleteAnActivityAreaWhenNotFound(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_DELETE, sprintf('%s/%s', self::BASE_URL, Uuid::v4()->toRfc4122()));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested ActivityArea was not found.',
            ],
        ]);
    }

    public function testUpdateActivityAreaWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, ActivityAreaFixtures::ACTIVITY_AREA_ID_3);

        $client->request(Request::METHOD_PATCH, $url, content: json_encode([
            'name' => 'Update Activity Area',
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $activityArea = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(ActivityArea::class, ActivityAreaFixtures::ACTIVITY_AREA_ID_3);

        $this->assertResponseBodySame([
            'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_3,
            'name' => $activityArea->getName(),
        ]);
    }

    #[DataProvider('provideValidationUpdateCases')]
    public function testValidationUpdate(array $requestBody, array $expectedErrors): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, ActivityAreaFixtures::ACTIVITY_AREA_ID_3);

        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationUpdateCases(): array
    {
        return [
            'name is not a string' => [
                'requestBody' => ['name' => 123],
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value should be of type string.'],
                ],
            ],
            'name is too short' => [
                'requestBody' => ['name' => 'a'],
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'name is too long' => [
                'requestBody' => ['name' => str_repeat('a', 21)],
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too long. It should have 20 characters or less.'],
                ],
            ],
        ];
    }
}
