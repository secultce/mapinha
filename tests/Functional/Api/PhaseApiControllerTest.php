<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\DataFixtures\Entity\AgentFixtures;
use App\DataFixtures\Entity\OpportunityFixtures;
use App\DataFixtures\Entity\PhaseFixtures;
use App\Entity\Phase;
use App\Tests\AbstractApiTestCase;
use App\Tests\Fixtures\PhaseTestFixtures;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class PhaseApiControllerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/opportunities/{opportunity}/phases';

    public function testCanCreateWithPartialRequestBody(): void
    {
        $requestBody = PhaseTestFixtures::partial();

        $client = static::apiClient();

        $url = str_replace('{opportunity}', OpportunityFixtures::OPPORTUNITY_ID_1, self::BASE_URL);

        $client->request(Request::METHOD_POST, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        /** @var Phase $phase */
        $phase = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Phase::class, $requestBody['id']);

        $this->assertResponseBodySame([
            'id' => $requestBody['id'],
            'name' => $requestBody['name'],
            'description' => null,
            'startDate' => null,
            'endDate' => null,
            'status' => true,
            'sequence' => 4,
            'createdBy' => ['id' => AgentFixtures::AGENT_ID_2],
            'opportunity' => ['id' => $requestBody['opportunity']],
            'reviewers' => [],
            'criteria' => [],
            'extraFields' => null,
            'createdAt' => $phase->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => null,
            'deletedAt' => null,
        ]);
    }

    public function testCanCreateWithCompleteRequestBody(): void
    {
        $requestBody = PhaseTestFixtures::complete();

        $client = static::apiClient();

        $url = str_replace('{opportunity}', OpportunityFixtures::OPPORTUNITY_ID_1, self::BASE_URL);

        $client->request(Request::METHOD_POST, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        /** @var Phase $phase */
        $phase = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Phase::class, $requestBody['id']);

        $this->assertResponseBodySame([
            'id' => $requestBody['id'],
            'name' => $requestBody['name'],
            'description' => $requestBody['description'],
            'startDate' => $phase->getStartDate()->format(DateTimeInterface::ATOM),
            'endDate' => $phase->getEndDate()->format(DateTimeInterface::ATOM),
            'status' => $requestBody['status'],
            'sequence' => 4,
            'createdBy' => ['id' => AgentFixtures::AGENT_ID_2],
            'opportunity' => ['id' => $requestBody['opportunity']],
            'reviewers' => [
                ['id' => AgentFixtures::AGENT_ID_3],
                ['id' => AgentFixtures::AGENT_ID_4],
            ],
            'criteria' => [
                'communication-skill' => '0 a 10',
                'post-graduate' => 'sim ou nao',
            ],
            'extraFields' => $requestBody['extraFields'],
            'createdAt' => $phase->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => null,
            'deletedAt' => null,
        ]);
    }

    #[DataProvider('provideValidationCreateCases')]
    public function testValidationCreate(array $requestBody, array $expectedErrors): void
    {
        $client = static::apiClient();

        $url = str_replace('{opportunity}', OpportunityFixtures::OPPORTUNITY_ID_1, self::BASE_URL);

        $client->request(Request::METHOD_POST, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationCreateCases(): array
    {
        $requestBody = PhaseTestFixtures::partial();

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
                'requestBody' => array_merge($requestBody, ['name' => str_repeat('a', 101)]),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too long. It should have 100 characters or less.'],
                ],
            ],
            'extraFields should be a valid JSON' => [
                'requestBody' => array_merge($requestBody, ['extraFields' => 'invalid-json']),
                'expectedErrors' => [
                    ['field' => 'extraFields', 'message' => 'This value should be of type json object.'],
                ],
            ],
            'reviewers is not an array' => [
                'requestBody' => array_merge($requestBody, ['reviewers' => 'not-an-array']),
                'expectedErrors' => [
                    ['field' => 'reviewers', 'message' => 'This value should be of type array.'],
                ],
            ],
            'reviewers contains an invalid UUID' => [
                'requestBody' => array_merge($requestBody, ['id' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'id', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'reviewers contains a non-existent agent' => [
                'requestBody' => array_merge($requestBody, ['reviewers' => [Uuid::v4()->toRfc4122()]]),
                'expectedErrors' => [
                    ['field' => 'reviewers[0]', 'message' => 'This id does not exist.'],
                ],
            ],
            'reviewer is inscribed and cannot be assigned' => [
                'requestBody' => array_merge($requestBody, ['reviewers' => [AgentFixtures::AGENT_ID_1]]),
                'expectedErrors' => [
                    ['field' => 'reviewers', 'message' => 'The agent 0cc8c682-b0cd-4cb3-bd9d-41a9161b3566 is registered on the opportunity and cannot be a reviewer.'],
                ],
            ],
            'criteria is not a valid JSON' => [
                'requestBody' => array_merge($requestBody, ['criteria' => 'invalid-json']),
                'expectedErrors' => [
                    ['field' => 'criteria', 'message' => 'This value should be of type json object.'],
                ],
            ],
            'criteria is null' => [
                'requestBody' => array_merge($requestBody, ['criteria' => null]),
                'expectedErrors' => [
                    ['field' => 'criteria', 'message' => 'This value should not be null.'],
                ],
            ],
        ];
    }

    public function testGetOnePhaseShouldBeSuccess(): void
    {
        $client = static::apiClient();

        $url = str_replace('{opportunity}', OpportunityFixtures::OPPORTUNITY_ID_1, self::BASE_URL);

        $client->request(Request::METHOD_GET, $url);
        $response = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertCount(3, json_decode($response));
        $this->assertResponseBodySame([
            [
                'id' => PhaseFixtures::PHASE_ID_1,
                'name' => 'Fase de submissão',
                'description' => 'Fase inicial do Concurso de Cordelistas',
                'startDate' => '2024-07-12T00:00:00+00:00',
                'endDate' => '2024-07-17T00:00:00+00:00',
                'status' => true,
                'sequence' => 1,
                'createdBy' => ['id' => AgentFixtures::AGENT_ID_1],
                'opportunity' => ['id' => OpportunityFixtures::OPPORTUNITY_ID_1],
                'reviewers' => [
                    ['id' => AgentFixtures::AGENT_ID_2],
                    ['id' => AgentFixtures::AGENT_ID_3],
                ],
                'criteria' => [
                    'communication-skill' => '0 a 10',
                    'post-graduate' => 'sim ou nao',
                ],
                'createdAt' => '2024-09-01T10:00:00+00:00',
                'updatedAt' => '2024-09-01T10:30:00+00:00',
                'deletedAt' => null,
            ],
            [
                'id' => PhaseFixtures::PHASE_ID_2,
                'name' => 'Fase de validação',
                'description' => 'Fase de validação do Concurso de Cordelistas',
                'startDate' => '2024-07-18T00:00:00+00:00',
                'endDate' => '2024-07-22T00:00:00+00:00',
                'status' => true,
                'sequence' => 2,
                'createdBy' => ['id' => AgentFixtures::AGENT_ID_1],
                'opportunity' => ['id' => OpportunityFixtures::OPPORTUNITY_ID_1],
                'reviewers' => [
                    ['id' => AgentFixtures::AGENT_ID_2],
                    ['id' => AgentFixtures::AGENT_ID_3],
                ],
                'criteria' => [
                    'communication-skill' => '0 a 10',
                    'post-graduate' => 'sim ou nao',
                ],
                'createdAt' => '2024-09-02T10:00:00+00:00',
                'updatedAt' => null,
                'deletedAt' => null,
            ],
            [
                'id' => PhaseFixtures::PHASE_ID_3,
                'name' => 'Fase de recurso',
                'description' => 'Fase de recurso do Concurso de Cordelistas',
                'startDate' => '2024-07-23T00:00:00+00:00',
                'endDate' => '2024-07-26T00:00:00+00:00',
                'status' => true,
                'sequence' => 3,
                'createdBy' => ['id' => AgentFixtures::AGENT_ID_1],
                'opportunity' => ['id' => OpportunityFixtures::OPPORTUNITY_ID_1],
                'reviewers' => [
                    ['id' => AgentFixtures::AGENT_ID_2],
                    ['id' => AgentFixtures::AGENT_ID_3],
                ],
                'criteria' => [
                    'communication-skill' => '0 a 10',
                    'post-graduate' => 'sim ou nao',
                ],
                'createdAt' => '2024-09-03T10:00:00+00:00',
                'updatedAt' => null,
                'deletedAt' => null,
            ],
        ]);
    }

    public function testGetAnPhaseItemWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', str_replace('{opportunity}', OpportunityFixtures::OPPORTUNITY_ID_1, self::BASE_URL), PhaseFixtures::PHASE_ID_3);

        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseBodySame([
            'id' => PhaseFixtures::PHASE_ID_3,
            'name' => 'Fase de recurso',
            'description' => 'Fase de recurso do Concurso de Cordelistas',
            'startDate' => '2024-07-23T00:00:00+00:00',
            'endDate' => '2024-07-26T00:00:00+00:00',
            'status' => true,
            'sequence' => 3,
            'createdBy' => ['id' => AgentFixtures::AGENT_ID_1],
            'opportunity' => ['id' => OpportunityFixtures::OPPORTUNITY_ID_1],
            'reviewers' => [
                ['id' => AgentFixtures::AGENT_ID_2],
                ['id' => AgentFixtures::AGENT_ID_3],
            ],
            'criteria' => [
                'communication-skill' => '0 a 10',
                'post-graduate' => 'sim ou nao',
            ],
            'extraFields' => [],
            'createdAt' => '2024-09-03T10:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ]);
    }

    public function testGetAResourceWhenNotFound(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', str_replace('{opportunity}', OpportunityFixtures::OPPORTUNITY_ID_1, self::BASE_URL), Uuid::v4()->toRfc4122());

        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested Phase was not found.',
            ],
        ]);
    }

    public function testCanUpdateAPhaseShouldBeSuccess(): void
    {
        $requestBody = PhaseTestFixtures::complete();
        unset($requestBody['id']);

        $url = sprintf('%s/%s', str_replace('{opportunity}', OpportunityFixtures::OPPORTUNITY_ID_2, self::BASE_URL), PhaseFixtures::PHASE_ID_5);
        $client = self::apiClient();

        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $phase = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Phase::class, PhaseFixtures::PHASE_ID_5);

        $this->assertResponseBodySame([
            'id' => PhaseFixtures::PHASE_ID_5,
            'name' => $requestBody['name'],
            'description' => $requestBody['description'],
            'startDate' => $phase->getStartDate()->format(DateTimeInterface::ATOM),
            'endDate' => $phase->getEndDate()->format(DateTimeInterface::ATOM),
            'status' => $requestBody['status'],
            'sequence' => 2,
            'createdBy' => ['id' => AgentFixtures::AGENT_ID_2],
            'opportunity' => ['id' => OpportunityFixtures::OPPORTUNITY_ID_2],
            'reviewers' => [
                ['id' => AgentFixtures::AGENT_ID_3],
                ['id' => AgentFixtures::AGENT_ID_4],
            ],
            'criteria' => [
                'communication-skill' => '0 a 10',
                'post-graduate' => 'sim ou nao',
            ],
            'extraFields' => $requestBody['extraFields'],
            'createdAt' => $phase->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => $phase->getUpdatedAt()->format(DateTimeInterface::ATOM),
            'deletedAt' => null,
        ]);
    }

    #[DataProvider('provideValidationUpdateCases')]
    public function testValidationUpdate(array $requestBody, array $expectedErrors): void
    {
        $client = self::apiClient();
        $url = sprintf('%s/%s', str_replace('{opportunity}', OpportunityFixtures::OPPORTUNITY_ID_2, self::BASE_URL), PhaseFixtures::PHASE_ID_5);

        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationUpdateCases(): array
    {
        $requestBody = PhaseTestFixtures::partial();

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
            'extraFields should be a valid JSON' => [
                'requestBody' => array_merge($requestBody, ['extraFields' => 'invalid-json']),
                'expectedErrors' => [
                    ['field' => 'extraFields', 'message' => 'This value should be of type json object.'],
                ],
            ],
            'reviewers is not an array' => [
                'requestBody' => array_merge($requestBody, ['reviewers' => 'not-an-array']),
                'expectedErrors' => [
                    ['field' => 'reviewers', 'message' => 'This value should be of type array.'],
                ],
            ],
            'reviewers contains a non-existent agent' => [
                'requestBody' => array_merge($requestBody, ['reviewers' => [Uuid::v4()->toRfc4122()]]),
                'expectedErrors' => [
                    ['field' => 'reviewers[0]', 'message' => 'This id does not exist.'],
                ],
            ],
            'reviewer is inscribed and cannot be assigned' => [
                'requestBody' => array_merge($requestBody, ['reviewers' => [AgentFixtures::AGENT_ID_1]]),
                'expectedErrors' => [
                    ['field' => 'reviewers', 'message' => 'The agent 0cc8c682-b0cd-4cb3-bd9d-41a9161b3566 is registered on the opportunity and cannot be a reviewer.'],
                ],
            ],
            'criteria is not a valid JSON' => [
                'requestBody' => array_merge($requestBody, ['criteria' => 'invalid-json']),
                'expectedErrors' => [
                    ['field' => 'criteria', 'message' => 'This value should be of type json object.'],
                ],
            ],
            'criteria is null' => [
                'requestBody' => array_merge($requestBody, ['criteria' => null]),
                'expectedErrors' => [
                    ['field' => 'criteria', 'message' => 'This value should not be null.'],
                ],
            ],
        ];
    }

    public function testDeleteAResourceWhenNotFound(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', str_replace('{opportunity}', OpportunityFixtures::OPPORTUNITY_ID_1, self::BASE_URL), Uuid::v4()->toRfc4122());

        $client->request(Request::METHOD_DELETE, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested Phase was not found.',
            ],
        ]);
    }

    public function testDeleteAResourceWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', str_replace('{opportunity}', OpportunityFixtures::OPPORTUNITY_ID_2, self::BASE_URL), PhaseFixtures::PHASE_ID_4);

        $client->request(Request::METHOD_DELETE, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
