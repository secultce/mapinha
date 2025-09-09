<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\DataFixtures\Entity\EventActivityFixtures;
use App\DataFixtures\Entity\EventFixtures;
use App\Entity\EventActivity;
use App\Tests\AbstractApiTestCase;
use App\Tests\Fixtures\EventActivityTestFixtures;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class EventActivityApiControllerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/events/{event}/activities';

    public function testCreate(): void
    {
        $client = static::apiClient();

        $requestBody = EventActivityTestFixtures::data();

        $url = str_replace('{event}', EventFixtures::EVENT_ID_3, self::BASE_URL);

        $client->request(Request::METHOD_POST, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $eventActivity = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(EventActivity::class, $requestBody['id']);

        $this->assertResponseBodySame([
            'id' => $eventActivity->getId()->toRfc4122(),
            'event' => [
                'id' => EventFixtures::EVENT_ID_3,
            ],
            'title' => $eventActivity->getTitle(),
            'description' => $eventActivity->getDescription(),
            'startDate' => $eventActivity->getStartDate()->format(DateTimeInterface::ATOM),
            'endDate' => $eventActivity->getEndDate()->format(DateTimeInterface::ATOM),
        ]);
    }

    #[DataProvider('provideValidationCreateCases')]
    public function testValidationCreate(array $requestBody, array $expectedErrors): void
    {
        $client = static::apiClient();

        $url = str_replace('{event}', EventFixtures::EVENT_ID_3, self::BASE_URL);

        $client->request(Request::METHOD_POST, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationCreateCases(): array
    {
        $requestBody = EventActivityTestFixtures::data();

        return [
            'missing required fields' => [
                'requestBody' => [],
                'expectedErrors' => [
                    [
                        'field' => 'id',
                        'message' => 'This value should not be blank.',
                    ],
                    [
                        'field' => 'title',
                        'message' => 'This value should not be blank.',
                    ],
                    [
                        'field' => 'startDate',
                        'message' => 'This value should not be blank.',
                    ],
                ],
            ],
            'id is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['id' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'id', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'title is not a string' => [
                'requestBody' => array_merge($requestBody, ['title' => 123]),
                'expectedErrors' => [
                    ['field' => 'title', 'message' => 'This value should be of type string.'],
                ],
            ],
            'title is too short' => [
                'requestBody' => array_merge($requestBody, ['title' => 'a']),
                'expectedErrors' => [
                    [
                        'field' => 'title',
                        'message' => 'This value is too short. It should have 2 characters or more.',
                    ],
                ],
            ],
            'title is too long' => [
                'requestBody' => array_merge($requestBody, ['title' => str_repeat('a', 256)]),
                'expectedErrors' => [
                    [
                        'field' => 'title',
                        'message' => 'This value is too long. It should have 100 characters or less.',
                    ],
                ],
            ],
            'description is not a string' => [
                'requestBody' => array_merge($requestBody, ['description' => 123]),
                'expectedErrors' => [
                    ['field' => 'description', 'message' => 'This value should be of type string.'],
                ],
            ],
            'startDate is not a valid date' => [
                'requestBody' => array_merge($requestBody, ['startDate' => 'invalid-date']),
                'expectedErrors' => [
                    ['field' => 'startDate', 'message' => 'This value is not a valid datetime.'],
                ],
            ],
            'endDate is not a valid date' => [
                'requestBody' => array_merge($requestBody, ['endDate' => 'invalid-date']),
                'expectedErrors' => [
                    ['field' => 'endDate', 'message' => 'This value is not a valid datetime.'],
                ],
            ],
        ];
    }

    public function testGetACollectionActivitiesOfEvents(): void
    {
        $url = str_replace('{event}', EventFixtures::EVENT_ID_3, self::BASE_URL);

        $client = static::apiClient();
        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertJsonContains([
            'id' => EventActivityFixtures::EVENT_ACTIVITY_ID_3,
            'event' => [
                'id' => EventFixtures::EVENT_ID_3,
            ],
            'title' => 'Abertura do músical',
            'description' => 'Abertura do músical',
            'startDate' => '2024-07-16T17:22:00+00:00',
            'endDate' => '2024-07-17T17:22:00+00:00',
        ]);
    }

    public function testGetAnEventItem(): void
    {
        $client = static::apiClient();

        $url = str_replace('{event}', EventFixtures::EVENT_ID_3, self::BASE_URL);
        $url = sprintf('%s/%s', $url, EventActivityFixtures::EVENT_ACTIVITY_ID_3);

        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertResponseBodySame([
            'id' => EventActivityFixtures::EVENT_ACTIVITY_ID_3,
            'event' => [
                'id' => EventFixtures::EVENT_ID_3,
            ],
            'title' => 'Abertura do músical',
            'description' => 'Abertura do músical',
            'startDate' => '2024-07-16T17:22:00+00:00',
            'endDate' => '2024-07-17T17:22:00+00:00',
        ]);
    }

    public function testGetAResourceWhenNotFound(): void
    {
        $client = static::apiClient();

        $url = str_replace('{event}', EventFixtures::EVENT_ID_4, self::BASE_URL);

        $client->request(Request::METHOD_GET, sprintf('%s/%s', $url, Uuid::v4()->toRfc4122()));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested EventActivity was not found.',
            ],
        ]);
    }

    public function testDeleteAResourceWhenNotFound(): void
    {
        $client = static::apiClient();

        $url = str_replace('{event}', EventFixtures::EVENT_ID_4, self::BASE_URL);

        $client->request(Request::METHOD_DELETE, sprintf('%s/%s', $url, Uuid::v4()->toRfc4122()));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested EventActivity was not found.',
            ],
        ]);
    }

    public function testDeleteAnEventItemWithSuccess(): void
    {
        $client = static::apiClient();

        $url = str_replace('{event}', EventFixtures::EVENT_ID_4, self::BASE_URL);
        $url = sprintf('%s/%s', $url, EventActivityFixtures::EVENT_ACTIVITY_ID_4);

        $client->request(Request::METHOD_DELETE, $url);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $client->request(Request::METHOD_GET, $url);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testUpdate(): void
    {
        $client = static::apiClient();

        $requestBody = EventActivityTestFixtures::data();

        $url = str_replace('{event}', EventFixtures::EVENT_ID_3, self::BASE_URL);
        $url = sprintf('%s/%s', $url, EventActivityFixtures::EVENT_ACTIVITY_ID_3);

        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseIsSuccessful();

        $eventActivity = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(EventActivity::class, $requestBody['id']);

        $this->assertResponseBodySame([
            'id' => $eventActivity->getId()->toRfc4122(),
            'event' => [
                'id' => EventFixtures::EVENT_ID_3,
            ],
            'title' => $eventActivity->getTitle(),
            'description' => $eventActivity->getDescription(),
            'startDate' => $eventActivity->getStartDate()->format(DateTimeInterface::ATOM),
            'endDate' => $eventActivity->getEndDate()->format(DateTimeInterface::ATOM),
        ]);
    }

    #[DataProvider('provideValidationUpdateCases')]
    public function testValidationUpdate(array $requestBody, array $expectedErrors): void
    {
        $client = static::apiClient();

        $url = str_replace('{event}', EventFixtures::EVENT_ID_3, self::BASE_URL);
        $url = sprintf('%s/%s', $url, EventActivityFixtures::EVENT_ACTIVITY_ID_3);

        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationUpdateCases(): array
    {
        $requestBody = EventActivityTestFixtures::data();

        return [
            'title is not a string' => [
                'requestBody' => array_merge($requestBody, ['title' => 123]),
                'expectedErrors' => [
                    ['field' => 'title', 'message' => 'This value should be of type string.'],
                ],
            ],
            'title is too short' => [
                'requestBody' => array_merge($requestBody, ['title' => 'a']),
                'expectedErrors' => [
                    [
                        'field' => 'title',
                        'message' => 'This value is too short. It should have 2 characters or more.',
                    ],
                ],
            ],
            'title is too long' => [
                'requestBody' => array_merge($requestBody, ['title' => str_repeat('a', 256)]),
                'expectedErrors' => [
                    [
                        'field' => 'title',
                        'message' => 'This value is too long. It should have 100 characters or less.',
                    ],
                ],
            ],
            'description is not a string' => [
                'requestBody' => array_merge($requestBody, ['description' => 123]),
                'expectedErrors' => [
                    ['field' => 'description', 'message' => 'This value should be of type string.'],
                ],
            ],
            'startDate is not a valid date' => [
                'requestBody' => array_merge($requestBody, ['startDate' => 'invalid-date']),
                'expectedErrors' => [
                    ['field' => 'startDate', 'message' => 'This value is not a valid datetime.'],
                ],
            ],
            'endDate is not a valid date' => [
                'requestBody' => array_merge($requestBody, ['endDate' => 'invalid-date']),
                'expectedErrors' => [
                    ['field' => 'endDate', 'message' => 'This value is not a valid datetime.'],
                ],
            ],
        ];
    }
}
