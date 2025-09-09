<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\DataFixtures\Entity\ArchitecturalAccessibilityFixtures;
use App\Entity\ArchitecturalAccessibility;
use App\Tests\AbstractApiTestCase;
use App\Tests\Fixtures\ArchitecturalAccessibilityTestFixtures;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class ArchitecturalAccessibilityApiControllerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/architectural-accessibilities';

    public function testCreateArchitecturalAccessibilityWithSuccess(): void
    {
        $requestBody = ArchitecturalAccessibilityTestFixtures::dataCreate();

        $client = static::apiClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode($requestBody));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $architecturalAccessibility = $client->getContainer()->get(EntityManagerInterface::class)
            ->getRepository(ArchitecturalAccessibility::class)
            ->findOneBy(['name' => $requestBody['name']]);

        $this->assertNotNull($architecturalAccessibility);
        $this->assertResponseBodySame([
            'id' => $architecturalAccessibility->getId()->toRfc4122(),
            'name' => $architecturalAccessibility->getName(),
            'description' => $architecturalAccessibility->getDescription(),
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
        $requestBody = ArchitecturalAccessibilityTestFixtures::dataCreate();

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
                'requestBody' => array_merge($requestBody, ['name' => str_repeat('a', 51)]),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too long. It should have 50 characters or less.'],
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
        ];
    }

    public function testGetListArchitecturalAccessibilities(): void
    {
        $client = static::apiClient();
        $client->request(Request::METHOD_GET, self::BASE_URL);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
        $this->assertNotEmpty($response);
    }

    public function testGetOneArchitecturalAccessibilityItemWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_1);
        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $architecturalAccessibility = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(ArchitecturalAccessibility::class, ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_1);

        $this->assertResponseBodySame([
            'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_1,
            'name' => $architecturalAccessibility->getName(),
            'description' => $architecturalAccessibility->getDescription(),
        ]);
    }

    public function testGetAnArchitecturalAccessibilityWhenNotFound(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_GET, sprintf('%s/%s', self::BASE_URL, Uuid::v4()->toRfc4122()));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested ArchitecturalAccessibility was not found.',
            ],
        ]);
    }

    public function testDeleteAnArchitecturalAccessibilityItemWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_8);

        $client->request(Request::METHOD_DELETE, $url);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $client->request(Request::METHOD_GET, $url);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testDeleteAnArchitecturalAccessibilityWhenNotFound(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_DELETE, sprintf('%s/%s', self::BASE_URL, Uuid::v4()->toRfc4122()));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested ArchitecturalAccessibility was not found.',
            ],
        ]);
    }

    public function testUpdateArchitecturalAccessibilityWithSuccess(): void
    {
        $requestBody = ArchitecturalAccessibilityTestFixtures::dataUpdate();

        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_5);

        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $architecturalAccessibility = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(ArchitecturalAccessibility::class, ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_5);

        $this->assertResponseBodySame([
            'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_5,
            'name' => $architecturalAccessibility->getName(),
            'description' => $architecturalAccessibility->getDescription(),
        ]);
    }

    #[DataProvider('provideValidationUpdateCases')]
    public function testValidationUpdate(array $requestBody, array $expectedErrors): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_5);

        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationUpdateCases(): array
    {
        $requestBody = ArchitecturalAccessibilityTestFixtures::dataUpdate();

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
                'requestBody' => array_merge($requestBody, ['name' => str_repeat('a', 51)]),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too long. It should have 50 characters or less.'],
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
        ];
    }
}
