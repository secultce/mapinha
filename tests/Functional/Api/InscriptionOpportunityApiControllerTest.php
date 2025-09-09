<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\DataFixtures\Entity\AgentFixtures;
use App\DataFixtures\Entity\InscriptionOpportunityFixtures;
use App\DataFixtures\Entity\OpportunityFixtures;
use App\Entity\InscriptionOpportunity;
use App\Tests\AbstractApiTestCase;
use App\Tests\Fixtures\InscriptionOpportunityTestFixtures;
use App\Tests\Fixtures\OpportunityTestFixtures;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class InscriptionOpportunityApiControllerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/opportunities/{opportunity}/inscriptions';

    public function testCanCreateWithCompleteRequestBody(): void
    {
        $requestBody = InscriptionOpportunityTestFixtures::complete();

        $client = self::apiClient();

        $url = str_replace('{opportunity}', $requestBody['opportunity'], self::BASE_URL);

        $client->request(Request::METHOD_POST, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $inscriptionOpportunity = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(InscriptionOpportunity::class, $requestBody['id']);

        $this->assertResponseBodySame([
            'id' => $requestBody['id'],
            'agent' => ['id' => AgentFixtures::AGENT_ID_2],
            'organization' => null,
            'opportunity' => ['id' => OpportunityFixtures::OPPORTUNITY_ID_10],
            'status' => 'active',
            'createdAt' => $inscriptionOpportunity->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => null,
            'deletedAt' => null,
        ]);
    }

    #[DataProvider('provideValidationCreateCases')]
    public function testValidationCreate(array $requestBody, array $expectedErrors): void
    {
        $url = str_replace('{opportunity}', OpportunityFixtures::OPPORTUNITY_ID_6, self::BASE_URL);

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
        $requestBody = InscriptionOpportunityTestFixtures::partial();

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

    public function testGetACollectionOfInscriptionForOneOwnedOpportunity(): void
    {
        $url = str_replace('{opportunity}', OpportunityFixtures::OPPORTUNITY_ID_2, self::BASE_URL);

        $client = static::apiClient();
        $client->request(Request::METHOD_GET, $url);

        $response = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertCount(4, json_decode($response));
        $this->assertResponseBodySame([
            [
                'id' => InscriptionOpportunityFixtures::INSCRIPTION_OPPORTUNITY_ID_25,
                'agent' => ['id' => AgentFixtures::AGENT_ID_11],
                'organization' => null,
                'opportunity' => ['id' => OpportunityFixtures::OPPORTUNITY_ID_2],
                'status' => 'active',
                'createdAt' => '2024-09-04T14:00:00+00:00',
                'updatedAt' => null,
                'deletedAt' => null,
            ],
            [
                'id' => InscriptionOpportunityFixtures::INSCRIPTION_OPPORTUNITY_ID_19,
                'agent' => ['id' => AgentFixtures::AGENT_ID_6],
                'organization' => null,
                'opportunity' => ['id' => OpportunityFixtures::OPPORTUNITY_ID_2],
                'status' => 'active',
                'createdAt' => '2024-09-04T12:00:00+00:00',
                'updatedAt' => null,
                'deletedAt' => null,
            ],
            [
                'id' => InscriptionOpportunityFixtures::INSCRIPTION_OPPORTUNITY_ID_1,
                'agent' => ['id' => AgentFixtures::AGENT_ID_1],
                'organization' => null,
                'opportunity' => ['id' => OpportunityFixtures::OPPORTUNITY_ID_2],
                'status' => 'active',
                'createdAt' => '2024-09-04T11:00:00+00:00',
                'updatedAt' => '2024-09-04T11:30:00+00:00',
                'deletedAt' => null,
            ],
            [
                'id' => InscriptionOpportunityFixtures::INSCRIPTION_OPPORTUNITY_ID_17,
                'agent' => ['id' => AgentFixtures::AGENT_ID_5],
                'organization' => null,
                'opportunity' => ['id' => OpportunityFixtures::OPPORTUNITY_ID_2],
                'status' => 'active',
                'createdAt' => '2024-09-04T11:00:00+00:00',
                'updatedAt' => null,
                'deletedAt' => null,
            ],
        ]);
    }

    public function testCannotGetACollectionOfInscriptionForOneNoOwnedOpportunity(): void
    {
        $url = str_replace('{opportunity}', OpportunityFixtures::OPPORTUNITY_ID_2, self::BASE_URL);

        $client = static::apiClient(user: 'saracamilo@example.com');
        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseBodySame([]);
    }

    public function testGetAnInscriptionOpportunityItemWithSuccess(): void
    {
        $url = str_replace('{opportunity}', OpportunityFixtures::OPPORTUNITY_ID_7, self::BASE_URL);
        $url = sprintf('%s/%s', $url, InscriptionOpportunityFixtures::INSCRIPTION_OPPORTUNITY_ID_6);

        $client = static::apiClient();
        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseBodySame([
            'id' => InscriptionOpportunityFixtures::INSCRIPTION_OPPORTUNITY_ID_6,
            'agent' => ['id' => AgentFixtures::AGENT_ID_2],
            'organization' => null,
            'opportunity' => ['id' => OpportunityFixtures::OPPORTUNITY_ID_7],
            'status' => 'active',
            'createdAt' => '2024-09-12T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ]);
    }

    public function testGetAnInscriptionOpportunityItemFromAnotherUser(): void
    {
        $url = str_replace('{opportunity}', OpportunityFixtures::OPPORTUNITY_ID_7, self::BASE_URL);
        $url = sprintf('%s/%s', $url, InscriptionOpportunityFixtures::INSCRIPTION_OPPORTUNITY_ID_6);

        $client = static::apiClient(user: 'alessandrofeitoza@example.com');
        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested InscriptionOpportunity was not found.',
            ],
        ]);
    }

    public function testGetAResourceWhenNotFound(): void
    {
        $url = str_replace('{opportunity}', OpportunityFixtures::OPPORTUNITY_ID_1, self::BASE_URL);

        $client = static::apiClient();
        $client->request(Request::METHOD_GET, sprintf('%s/%s', $url, Uuid::v4()->toRfc4122()));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested InscriptionOpportunity was not found.',
            ],
        ]);
    }

    public function testDeleteAResourceWhenNotFound(): void
    {
        $client = static::apiClient();

        $url = str_replace('{opportunity}', InscriptionOpportunityFixtures::INSCRIPTION_OPPORTUNITY_ID_6, self::BASE_URL);

        $client->request(Request::METHOD_DELETE, sprintf('%s/%s', $url, Uuid::v4()->toRfc4122()));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested InscriptionOpportunity was not found.',
            ],
        ]);
    }

    public function testDeleteAResourceWithSuccess(): void
    {
        $url = str_replace('{opportunity}', OpportunityFixtures::OPPORTUNITY_ID_8, self::BASE_URL);
        $url = sprintf('%s/%s', $url, InscriptionOpportunityFixtures::INSCRIPTION_OPPORTUNITY_ID_7);

        $client = static::apiClient();
        $client->request(Request::METHOD_DELETE, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testCanUpdateOneInscriptionOpportunity(): void
    {
        $requestBody = OpportunityTestFixtures::complete();
        unset($requestBody['id']);

        $url = str_replace('{opportunity}', OpportunityFixtures::OPPORTUNITY_ID_8, self::BASE_URL);
        $url = sprintf('%s/%s', $url, InscriptionOpportunityFixtures::INSCRIPTION_OPPORTUNITY_ID_7);

        $client = self::apiClient();
        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $opportunity = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(InscriptionOpportunity::class, InscriptionOpportunityFixtures::INSCRIPTION_OPPORTUNITY_ID_7);

        $this->assertResponseBodySame([
            'id' => InscriptionOpportunityFixtures::INSCRIPTION_OPPORTUNITY_ID_7,
            'agent' => ['id' => AgentFixtures::AGENT_ID_2],
            'organization' => null,
            'opportunity' => ['id' => OpportunityFixtures::OPPORTUNITY_ID_8],
            'status' => 'active',
            'createdAt' => $opportunity->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => $opportunity->getUpdatedAt()->format(DateTimeInterface::ATOM),
            'deletedAt' => null,
        ]);
    }

    #[DataProvider('provideValidationUpdateCases')]
    public function testValidationUpdate(array $requestBody, array $expectedErrors): void
    {
        $url = str_replace('{opportunity}', OpportunityFixtures::OPPORTUNITY_ID_8, self::BASE_URL);
        $url = sprintf('%s/%s', $url, InscriptionOpportunityFixtures::INSCRIPTION_OPPORTUNITY_ID_7);

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
        $requestBody = InscriptionOpportunityTestFixtures::partial();

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
