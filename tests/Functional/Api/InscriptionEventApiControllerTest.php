<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\DataFixtures\Entity\AgentFixtures;
use App\DataFixtures\Entity\EventFixtures;
use App\DataFixtures\Entity\InscriptionEventFixtures;
use App\Entity\InscriptionEvent;
use App\Tests\AbstractApiTestCase;
use App\Tests\Fixtures\InscriptionEventTestFixtures;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final class InscriptionEventApiControllerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/events/{event}/inscriptions';

    public function testGetACollectionOfInscriptions(): void
    {
        $url = str_replace('{event}', EventFixtures::EVENT_ID_1, self::BASE_URL);

        $client = static::apiClient();
        $client->request(Request::METHOD_GET, $url);

        $response = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertCount(2, json_decode($response));
        $this->assertResponseBodySame([
            [
                'id' => InscriptionEventFixtures::INSCRIPTION_EVENT_ID_18,
                'agent' => ['id' => AgentFixtures::AGENT_ID_6],
                'event' => ['id' => EventFixtures::EVENT_ID_1],
                'status' => 'active',
                'createdAt' => '2024-09-01T13:00:00+00:00',
                'updatedAt' => null,
                'deletedAt' => null,
            ],
            [
                'id' => InscriptionEventFixtures::INSCRIPTION_EVENT_ID_16,
                'agent' => ['id' => AgentFixtures::AGENT_ID_5],
                'event' => ['id' => EventFixtures::EVENT_ID_1],
                'status' => 'active',
                'createdAt' => '2024-09-01T11:00:00+00:00',
                'updatedAt' => null,
                'deletedAt' => null,
            ],
        ]);
    }

    public function testGetAnInscriptionEventItemWithSuccess(): void
    {
        $url = str_replace('{event}', EventFixtures::EVENT_ID_7, self::BASE_URL);
        $url = sprintf('%s/%s', $url, InscriptionEventFixtures::INSCRIPTION_EVENT_ID_6);

        $client = static::apiClient();
        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseBodySame([
            'id' => InscriptionEventFixtures::INSCRIPTION_EVENT_ID_6,
            'agent' => ['id' => AgentFixtures::AGENT_ID_2],
            'event' => ['id' => EventFixtures::EVENT_ID_7],
            'status' => 'active',
            'createdAt' => '2024-09-12T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ]);
    }

    public function testGetAResourceWhenNotFound(): void
    {
        $url = str_replace('{event}', EventFixtures::EVENT_ID_1, self::BASE_URL);

        $client = static::apiClient();
        $client->request(Request::METHOD_GET, sprintf('%s/%s', $url, Uuid::v4()->toRfc4122()));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested InscriptionEvent was not found.',
            ],
        ]);
    }

    public function testCanCreateWithCompleteRequestBody(): void
    {
        $requestBody = InscriptionEventTestFixtures::complete();

        $client = self::apiClient();

        $url = str_replace('{event}', $requestBody['event'], self::BASE_URL);

        $client->request(Request::METHOD_POST, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $inscriptionEvent = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(InscriptionEvent::class, $requestBody['id']);

        $this->assertResponseBodySame([
            'id' => $requestBody['id'],
            'agent' => ['id' => AgentFixtures::AGENT_ID_3],
            'event' => ['id' => EventFixtures::EVENT_ID_4],
            'status' => 'active',
            'createdAt' => $inscriptionEvent->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => null,
            'deletedAt' => null,
        ]);
    }

    #[DataProvider('provideValidationCreateCases')]
    public function testValidationCreate(array $requestBody, array $expectedErrors): void
    {
        $url = str_replace('{event}', EventFixtures::EVENT_ID_6, self::BASE_URL);

        $client = self::apiClient();
        $client->request(Request::METHOD_POST, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationCreateCases(): array
    {
        $requestBody = InscriptionEventTestFixtures::partial();

        return [
            'id is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['id' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'id', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'agent should exists' => [
                'requestBody' => array_merge($requestBody, ['agent' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'agent', 'message' => 'This id does not exist.'],
                ],
            ],
            'agent is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['agent' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'agent', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'status should be string' => [
                'requestBody' => array_merge($requestBody, ['status' => 1]),
                'expectedErrors' => [
                    ['field' => 'status', 'message' => 'This value should be of type string.'],
                ],
            ],
            'status is not valid' => [
                'requestBody' => array_merge($requestBody, ['status' => 'status-not-valid']),
                'expectedErrors' => [
                    ['field' => 'status', 'message' => 'The value you selected is not a valid choice.'],
                ],
            ],
        ];
    }

    public function testCannotCreateIfInscriptionAlreadyExists(): void
    {
        $requestBody = InscriptionEventTestFixtures::complete();
        $requestBody['agent'] = AgentFixtures::AGENT_ID_1;

        $client = self::apiClient();

        $url = str_replace('{event}', $requestBody['event'], self::BASE_URL);

        $client->request(Request::METHOD_POST, $url, content: json_encode($requestBody));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'error_general',
            'error_details' => [
                'description' => 'The agent has already signed up for this event.',
            ],
        ]);
    }

    public function testDeleteAResourceWhenNotFound(): void
    {
        $client = static::apiClient();

        $url = str_replace('{event}', InscriptionEventFixtures::INSCRIPTION_EVENT_ID_6, self::BASE_URL);

        $client->request(Request::METHOD_DELETE, sprintf('%s/%s', $url, Uuid::v4()->toRfc4122()));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested InscriptionEvent was not found.',
            ],
        ]);
    }

    public function testDeleteAResourceWithSuccess(): void
    {
        $url = str_replace('{event}', EventFixtures::EVENT_ID_8, self::BASE_URL);
        $url = sprintf('%s/%s', $url, InscriptionEventFixtures::INSCRIPTION_EVENT_ID_7);

        $client = static::apiClient();
        $client->request(Request::METHOD_DELETE, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testCanUpdateOneInscriptionEvent(): void
    {
        $requestBody = InscriptionEventTestFixtures::complete();
        unset($requestBody['id']);

        $url = str_replace('{event}', EventFixtures::EVENT_ID_5, self::BASE_URL);
        $url = sprintf('%s/%s', $url, InscriptionEventFixtures::INSCRIPTION_EVENT_ID_4);

        $client = self::apiClient();
        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $event = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(InscriptionEvent::class, InscriptionEventFixtures::INSCRIPTION_EVENT_ID_4);

        $this->assertResponseBodySame([
            'id' => InscriptionEventFixtures::INSCRIPTION_EVENT_ID_4,
            'agent' => ['id' => AgentFixtures::AGENT_ID_3],
            'event' => ['id' => EventFixtures::EVENT_ID_5],
            'status' => 'active',
            'createdAt' => $event->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => $event->getUpdatedAt()->format(DateTimeInterface::ATOM),
            'deletedAt' => null,
        ]);
    }

    public function testUpdateAResourceWhenNotFound(): void
    {
        $requestBody = InscriptionEventTestFixtures::complete();
        unset($requestBody['id']);

        $url = str_replace('{event}', EventFixtures::EVENT_ID_5, self::BASE_URL);
        $url = sprintf('%s/%s', $url, Uuid::v4()->toRfc4122());

        $client = self::apiClient();
        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested InscriptionEvent was not found.',
            ],
        ]);
    }

    #[DataProvider('provideValidationUpdateCases')]
    public function testValidationUpdate(array $requestBody, array $expectedErrors): void
    {
        $url = str_replace('{event}', EventFixtures::EVENT_ID_5, self::BASE_URL);
        $url = sprintf('%s/%s', $url, InscriptionEventFixtures::INSCRIPTION_EVENT_ID_4);

        $client = self::apiClient();
        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationUpdateCases(): array
    {
        $requestBody = InscriptionEventTestFixtures::partial();

        return [
            'agent should exists' => [
                'requestBody' => array_merge($requestBody, ['agent' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'agent', 'message' => 'This id does not exist.'],
                ],
            ],
            'agent is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['agent' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'agent', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'status should be string' => [
                'requestBody' => array_merge($requestBody, ['status' => 1]),
                'expectedErrors' => [
                    ['field' => 'status', 'message' => 'This value should be of type string.'],
                ],
            ],
            'status is not valid' => [
                'requestBody' => array_merge($requestBody, ['status' => 'status-not-valid']),
                'expectedErrors' => [
                    ['field' => 'status', 'message' => 'The value you selected is not a valid choice.'],
                ],
            ],
        ];
    }
}
