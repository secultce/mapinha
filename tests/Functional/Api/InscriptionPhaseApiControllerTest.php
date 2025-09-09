<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\DataFixtures\Entity\AgentFixtures;
use App\DataFixtures\Entity\OpportunityFixtures;
use App\DataFixtures\Entity\PhaseFixtures;
use App\Entity\InscriptionPhase;
use App\Tests\AbstractApiTestCase;
use App\Tests\Fixtures\InscriptionPhaseTestFixtures;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class InscriptionPhaseApiControllerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/opportunities/{opportunity}/phases/{phase}/inscriptions';

    public function testCanCreateWithCompleteRequestBody(): void
    {
        $requestBody = InscriptionPhaseTestFixtures::complete();

        $client = self::apiClient();

        $url = $this->mountUrl(OpportunityFixtures::OPPORTUNITY_ID_5, PhaseFixtures::PHASE_ID_10);

        $client->request(Request::METHOD_POST, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $inscriptionPhase = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(InscriptionPhase::class, $requestBody['id']);

        $this->assertResponseBodySame([
            'id' => $requestBody['id'],
            'agent' => ['id' => AgentFixtures::AGENT_ID_2],
            'organization' => null,
            'phase' => ['id' => PhaseFixtures::PHASE_ID_10],
            'status' => 'active',
            'extraFields' => [
                'keyTest' => 'valueTest',
            ],
            'createdAt' => $inscriptionPhase->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => null,
            'deletedAt' => null,
        ]);
    }

    public function testOpportunityShouldNotBelongsToAgent(): void
    {
        $requestBody = InscriptionPhaseTestFixtures::complete();

        $client = self::apiClient();

        $url = $this->mountUrl(OpportunityFixtures::OPPORTUNITY_ID_2, PhaseFixtures::PHASE_ID_4);

        $client->request(Request::METHOD_POST, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $this->assertResponseBodySame([
            'error_message' => 'error_general',
            'error_details' => [
                'description' => 'The requested was unauthorized.',
            ],
        ]);
    }

    public function testPhaseShouldBelongsToOpportunity(): void
    {
        $requestBody = InscriptionPhaseTestFixtures::complete();

        $client = self::apiClient();

        $url = $this->mountUrl(OpportunityFixtures::OPPORTUNITY_ID_5, PhaseFixtures::PHASE_ID_4);

        $client->request(Request::METHOD_POST, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $this->assertResponseBodySame([
            'error_message' => 'error_general',
            'error_details' => [
                'description' => 'The requested was unauthorized.',
            ],
        ]);
    }

    public function testCannotInscriptionWithoutThePrevious(): void
    {
        $requestBody = InscriptionPhaseTestFixtures::complete();

        $client = self::apiClient();

        $url = $this->mountUrl(OpportunityFixtures::OPPORTUNITY_ID_4, PhaseFixtures::PHASE_ID_9);

        $client->request(Request::METHOD_POST, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $this->assertResponseBodySame([
            'error_message' => 'error_general',
            'error_details' => [
                'description' => 'The agent was not inscribed in previous phases.',
            ],
        ]);
    }

    #[DataProvider('provideValidationCreateCases')]
    public function testValidationCreate(array $requestBody, array $expectedErrors): void
    {
        $url = $this->mountUrl(OpportunityFixtures::OPPORTUNITY_ID_5, PhaseFixtures::PHASE_ID_10);

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
        $requestBody = InscriptionPhaseTestFixtures::partial();

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

    private function mountUrl(string $opportunityId, string $phaseId): string
    {
        $urlWithOpportunity = str_replace('{opportunity}', $opportunityId, self::BASE_URL);

        return str_replace('{phase}', $phaseId, $urlWithOpportunity);
    }
}
