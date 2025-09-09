<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\DataFixtures\Entity\SpaceTypeFixtures;
use App\Entity\SpaceType;
use App\Tests\AbstractApiTestCase;
use App\Tests\Fixtures\SpaceTypeTestFixtures;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class SpaceTypeApiControllerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/space-types';

    public function testGetListSpaceTypes(): void
    {
        $client = static::apiClient();
        $client->request(Request::METHOD_GET, self::BASE_URL);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
        $this->assertNotEmpty($response);
    }

    public function testGetOneSpaceTypeItemWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, SpaceTypeFixtures::SPACE_TYPE_ID_1);
        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $spaceType = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(SpaceType::class, SpaceTypeFixtures::SPACE_TYPE_ID_1);

        $this->assertResponseBodySame([
            'id' => SpaceTypeFixtures::SPACE_TYPE_ID_1,
            'name' => $spaceType->getName(),
        ]);
    }

    public function testGetAnSpaceTypeWhenNotFound(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_GET, sprintf('%s/%s', self::BASE_URL, Uuid::v4()->toRfc4122()));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested SpaceType was not found.',
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
                'description' => 'The requested SpaceType was not found.',
            ],
        ]);
    }

    public function testDeleteASpaceTypeItemWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, SpaceTypeFixtures::SPACE_TYPE_ID_2);

        $client->request(Request::METHOD_DELETE, $url);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $client->request(Request::METHOD_GET, $url);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testDeleteAnSpaceTypeWhenNotFound(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_DELETE, sprintf('%s/%s', self::BASE_URL, Uuid::v4()->toRfc4122()));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested SpaceType was not found.',
            ],
        ]);
    }

    public function testCreateASpaceTypeItemWithSuccess(): void
    {
        $requestBody = SpaceTypeTestFixtures::dataCreate();
        $client = static::apiClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode($requestBody));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $spaceType = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(SpaceType::class, $requestBody['id']);

        $this->assertNotNull($spaceType);
        $this->assertResponseBodySame([
            'id' => $spaceType->getId()->toRfc4122(),
            'name' => $spaceType->getName(),
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
        $requestBody = SpaceTypeTestFixtures::dataCreate();

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

    public function testUpdateASpaceTypeItemWithSuccess(): void
    {
        $requestBody = SpaceTypeTestFixtures::dataUpdate();
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, SpaceTypeFixtures::SPACE_TYPE_ID_1);
        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $spaceType = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(SpaceType::class, SpaceTypeFixtures::SPACE_TYPE_ID_1);

        $this->assertNotNull($spaceType);
        $this->assertResponseBodySame([
            'id' => $spaceType->getId()->toRfc4122(),
            'name' => $spaceType->getName(),
        ]);
    }

    public function testUpdateASpaceTypeWhenNotFound(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_PATCH, sprintf('%s/%s', self::BASE_URL, Uuid::v4()->toRfc4122()), content: json_encode(SpaceTypeTestFixtures::dataUpdate()));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested SpaceType was not found.',
            ],
        ]);
    }

    #[DataProvider('provideValidationUpdateCases')]
    public function testValidationUpdate(array $requestBody, array $expectedErrors): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, SpaceTypeFixtures::SPACE_TYPE_ID_1);

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
