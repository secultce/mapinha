<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repositories;

use App\Entity\Agent;
use App\Entity\Opportunity;
use App\Entity\User;
use App\Repository\OpportunityRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Uid\Uuid;

class OpportunityRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private OpportunityRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $container = self::getContainer();

        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->repository = $this->entityManager->getRepository(Opportunity::class);

        $mockUser = new User();
        $mockUser->setId(Uuid::v4());

        $tokenStorage = $container->get('security.token_storage');
        assert($tokenStorage instanceof TokenStorageInterface);

        $token = new UsernamePasswordToken($mockUser, 'main');
        $tokenStorage->setToken($token);
    }

    public function testSaveAndFindOpportunity(): void
    {
        $agent = $this->createAgent();

        $opportunityName = 'Oportunidade Teste '.uniqid();
        $opportunity = new Opportunity();
        $opportunity->setId(Uuid::v4());
        $opportunity->setName($opportunityName);
        $opportunity->setCreatedBy($agent);

        $savedOpportunity = $this->repository->save($opportunity);
        $opportunityId = $savedOpportunity->getId();

        $this->entityManager->clear();

        $foundOpportunity = $this->repository->find($opportunityId);

        $this->assertNotNull($foundOpportunity);
        $this->assertEquals($opportunityName, $foundOpportunity->getName());
        $this->assertInstanceOf(Opportunity::class, $savedOpportunity);
    }

    public function testCountRecentOpportunities(): void
    {
        $agent = $this->createAgent();

        $recentDate = new DateTimeImmutable('-3 days');
        $this->createTestOpportunity($agent, 'Oportunidade Recente 1', $recentDate);

        $recentDate2 = new DateTimeImmutable('-1 day');
        $this->createTestOpportunity($agent, 'Oportunidade Recente 2', $recentDate2);

        $oldDate = new DateTimeImmutable('-10 days');
        $this->createTestOpportunity($agent, 'Oportunidade Antiga', $oldDate);

        $deletedOpportunity = $this->createTestOpportunity($agent, 'Oportunidade Deletada', $recentDate);
        $deletedOpportunity->setDeletedAt(new DateTime());

        $this->entityManager->flush();

        $startDate = new DateTime('-7 days');
        $recentCount = $this->repository->countRecentOpportunities($startDate);

        $this->assertEquals(2, $recentCount);
    }

    public function testCountOpenedOpportunities(): void
    {
        $agent = $this->createAgent();

        $this->createTestOpportunity($agent, 'Oportunidade Sem Fases');

        $deletedOpportunity = $this->createTestOpportunity($agent, 'Oportunidade Deletada');
        $deletedOpportunity->setDeletedAt(new DateTime());

        $this->entityManager->flush();

        $openedCount = $this->repository->countOpenedOpportunities();

        $this->assertGreaterThanOrEqual(1, $openedCount);
    }

    public function testCountFinishedOpportunities(): void
    {
        $this->createAgent();

        $finishedCount = $this->repository->countFinishedOpportunities();

        $this->assertIsInt($finishedCount);
        $this->assertGreaterThanOrEqual(0, $finishedCount);
    }

    public function testSaveUpdatesExistingOpportunity(): void
    {
        $agent = $this->createAgent();

        $opportunity = $this->createTestOpportunity($agent, 'Oportunidade Original');
        $this->entityManager->flush();

        $opportunityId = $opportunity->getId();

        $opportunity->setName('Oportunidade Atualizada');
        $opportunity->setUpdatedAt(new DateTime());

        $this->repository->save($opportunity);

        $this->entityManager->clear();

        $foundOpportunity = $this->repository->find($opportunityId);

        $this->assertNotNull($foundOpportunity);
        $this->assertEquals('Oportunidade Atualizada', $foundOpportunity->getName());
        $this->assertNotNull($foundOpportunity->getUpdatedAt());
    }

    public function testCountRecentOpportunitiesWithDifferentDates(): void
    {
        $agent = $this->createAgent();

        $this->createTestOpportunity($agent, 'Op 1 dia', new DateTimeImmutable('-1 day'));
        $this->createTestOpportunity($agent, 'Op 5 dias', new DateTimeImmutable('-5 days'));
        $this->createTestOpportunity($agent, 'Op 15 dias', new DateTimeImmutable('-15 days'));

        $this->entityManager->flush();

        $count3Days = $this->repository->countRecentOpportunities(new DateTime('-3 days'));
        $this->assertEquals(1, $count3Days);

        $count10Days = $this->repository->countRecentOpportunities(new DateTime('-10 days'));
        $this->assertEquals(2, $count10Days);

        $count20Days = $this->repository->countRecentOpportunities(new DateTime('-20 days'));
        $this->assertEquals(3, $count20Days);
    }

    public function testCountRecentOpportunitiesWithEmptyResult(): void
    {
        $startDate = new DateTime('-1 day');
        $count = $this->repository->countRecentOpportunities($startDate);

        $this->assertIsInt($count);
        $this->assertGreaterThanOrEqual(0, $count);
    }

    public function testCountMethodsReturnIntegers(): void
    {
        $startDate = new DateTime('-7 days');

        $recentCount = $this->repository->countRecentOpportunities($startDate);
        $openedCount = $this->repository->countOpenedOpportunities();
        $finishedCount = $this->repository->countFinishedOpportunities();

        $this->assertIsInt($recentCount);
        $this->assertIsInt($openedCount);
        $this->assertIsInt($finishedCount);

        $this->assertGreaterThanOrEqual(0, $recentCount);
        $this->assertGreaterThanOrEqual(0, $openedCount);
        $this->assertGreaterThanOrEqual(0, $finishedCount);
    }

    public function testSaveWithNullValues(): void
    {
        $agent = $this->createAgent();

        $opportunity = new Opportunity();
        $opportunity->setId(Uuid::v4());
        $opportunity->setName('Oportunidade Mínima');
        $opportunity->setCreatedBy($agent);

        $savedOpportunity = $this->repository->save($opportunity);

        $this->assertNotNull($savedOpportunity);
        $this->assertEquals('Oportunidade Mínima', $savedOpportunity->getName());
        $this->assertNull($savedOpportunity->getDeletedAt());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->clear();
    }

    private function createAgent(): Agent
    {
        $agent = new Agent();
        $agent->setId(Uuid::v4());
        $agent->setName('Agente de Teste '.uniqid());
        $agent->setShortBio('Biografia curta para o agente de teste.');
        $agent->setLongBio('Biografia longa e detalhada para o agente de teste.');
        $agent->setCulture(true);
        $agent->setMain(false);

        $this->entityManager->persist($agent);
        $this->entityManager->flush();

        return $agent;
    }

    private function createTestOpportunity(Agent $agent, string $name, ?DateTimeImmutable $createdAt = null): Opportunity
    {
        $opportunity = new Opportunity();
        $opportunity->setId(Uuid::v4());
        $opportunity->setName($name);
        $opportunity->setCreatedBy($agent);

        if ($createdAt) {
            $opportunity->setCreatedAt($createdAt);
        }

        $this->entityManager->persist($opportunity);

        return $opportunity;
    }
}
