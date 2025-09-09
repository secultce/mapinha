<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\DataFixtures\Entity\CulturalLanguageFixtures;
use App\Entity\CulturalLanguage;
use App\Tests\AbstractApiTestCase;
use App\Tests\Fixtures\CulturalLanguageTestFixtures;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class CulturalLanguageApiControllerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/cultural-languages';

    public function testGetListCulturalLanguages(): void
    {
        $client = static::apiClient();
        $client->request(Request::METHOD_GET, self::BASE_URL);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
        $this->assertNotEmpty($response);
    }

    public function testGetOneCulturalLanguageWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_1);
        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $culturalLanguage = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(CulturalLanguage::class, CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_1);

        $this->assertResponseBodySame([
            'id' => CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_1,
            'name' => $culturalLanguage->getName(),
            'description' => $culturalLanguage->getDescription(),
        ]);
    }

    public function testGetAnCulturalLanguageWhenNotFound(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_GET, sprintf('%s/%s', self::BASE_URL, Uuid::v4()->toRfc4122()));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested CulturalLanguage was not found.',
            ],
        ]);
    }

    public function testDeleteCulturalLanguageWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_2);
        $client->request(Request::METHOD_DELETE, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $client->request(Request::METHOD_GET, $url);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testDeleteACulturalLanguageWhenNotFound(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_DELETE, sprintf('%s/%s', self::BASE_URL, Uuid::v4()->toRfc4122()));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested CulturalLanguage was not found.',
            ],
        ]);
    }

    public function testCreateCulturalLanguageWithSuccess(): void
    {
        $requestBody = CulturalLanguageTestFixtures::complete();

        $client = static::apiClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode($requestBody));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $culturalLanguage = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(CulturalLanguage::class, $requestBody['id']);

        $this->assertNotNull($culturalLanguage);
        $this->assertResponseBodySame([
            'id' => $culturalLanguage->getId()->toRfc4122(),
            'name' => $culturalLanguage->getName(),
            'description' => $culturalLanguage->getDescription(),
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
        $requestBody = CulturalLanguageTestFixtures::complete();

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
            'name is too short' => [
                'requestBody' => array_merge($requestBody, ['name' => 'a']),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'name is too long' => [
                'requestBody' => array_merge($requestBody, ['name' => str_repeat('a', 51)]),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too long. It should have 50 characters or less.'],
                ],
            ],
        ];
    }

    public function testUpdateCulturalLanguageWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_4);

        $client->request(Request::METHOD_PATCH, $url, content: json_encode([
            'name' => 'Update Cultural Language',
            'description' => 'Update Description',
        ]));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $culturalLanguage = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(CulturalLanguage::class, CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_4);

        $this->assertResponseBodySame([
            'id' => CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_4,
            'name' => $culturalLanguage->getName(),
            'description' => $culturalLanguage->getDescription(),
        ]);
    }

    public function testUpdateCulturalLanguageWhenNotFound(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_PATCH, sprintf('%s/%s', self::BASE_URL, Uuid::v4()->toRfc4122()), content: json_encode([
            'name' => 'Update Cultural Language',
            'description' => 'Update Description',
        ]));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested CulturalLanguage was not found.',
            ],
        ]);
    }

    #[DataProvider('provideValidationUpdateCases')]
    public function testValidationUpdate(array $requestBody, array $expectedErrors): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_4);

        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationUpdateCases(): array
    {
        $requestBody = CulturalLanguageTestFixtures::complete();

        return [
            'missing required fields' => [
                'requestBody' => [],
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value should not be blank.'],
                ],
            ],
            'name is too short' => [
                'requestBody' => array_merge($requestBody, ['name' => 'a']),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'name is too long' => [
                'requestBody' => array_merge($requestBody, ['name' => str_repeat('a', 51)]),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too long. It should have 50 characters or less.'],
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
        ];
    }
}
