<?php

declare(strict_types=1);

namespace App\Tests\Functional\Services;

use App\DataFixtures\Entity\AgentFixtures;
use App\DataFixtures\Entity\OrganizationFixtures;
use App\Entity\Agent;
use App\Service\Interface\OrganizationServiceInterface;
use App\Tests\AbstractKernelTestCase;
use Symfony\Component\Uid\Uuid;

final class OrganizationServiceTest extends AbstractKernelTestCase
{
    private OrganizationServiceInterface $service;

    protected function setUp(): void
    {
        self::bootKernel();

        self::loginUser();

        $this->service = self::getOrganizationService();
    }

    public function testRemoveAgentFromOrganization(): void
    {
        $organizationId = Uuid::fromString(OrganizationFixtures::ORGANIZATION_ID_2);
        $agentId = Uuid::fromString(AgentFixtures::AGENT_ID_2);

        $organization = $this->service->get($organizationId);
        $initialAgents = $organization->getAgents()->toArray();

        $agentExists = $organization->getAgents()->exists(
            fn (int $index, Agent $agent) => $agent->getId()->equals($agentId)
        );
        $this->assertTrue($agentExists, 'The agent should be in the organization before removal');

        $this->service->removeAgent($agentId, $organizationId);

        $updatedOrganization = $this->service->get($organizationId);
        $agentStillExists = $updatedOrganization->getAgents()->exists(
            fn (int $index, Agent $agent) => $agent->getId()->equals($agentId)
        );

        $this->assertFalse($agentStillExists, 'The agent should no longer be in the organization');
        $this->assertCount(
            count($initialAgents) - 1,
            $updatedOrganization->getAgents(),
            'The organization should have one agent less'
        );
    }

    private function getOrganizationService(): OrganizationServiceInterface
    {
        return self::getContainer()->get(OrganizationServiceInterface::class);
    }
}
