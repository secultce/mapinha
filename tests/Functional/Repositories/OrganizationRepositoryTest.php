<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repositories;

use App\Entity\Agent;
use App\Entity\Organization;
use App\Entity\User;
use App\Enum\OrganizationTypeEnum;
use App\Repository\OrganizationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

class OrganizationRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private OrganizationRepository $organizationRepository;
    private UserInterface $mockUser;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
        $this->organizationRepository = $this->entityManager->getRepository(Organization::class);

        $this->mockUser = new User();
        $userId = Uuid::v4();
        $this->mockUser->setId($userId);

        $tokenStorage = static::getContainer()->get('security.token_storage');
        assert($tokenStorage instanceof \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface);

        $token = new UsernamePasswordToken($this->mockUser, 'main');
        $tokenStorage->setToken($token);
    }

    private function createAgent(): Agent
    {
        $agent = new Agent();
        $agent->setId(Uuid::v4());
        $agent->setName('Agente de Teste');
        $agent->setShortBio('Biografia curta para o agente de teste.');
        $agent->setLongBio('Biografia longa e detalhada para o agente de teste.');
        $agent->setCulture(true);
        $agent->setMain(false);

        $this->entityManager->persist($agent);
        $this->entityManager->flush();

        return $agent;
    }

    public function testSaveOrganization(): void
    {
        $agent = $this->createAgent();

        $organization = new Organization();
        $organization->setId(Uuid::v4());
        $organization->setName('Test Organization');
        $organization->setType(OrganizationTypeEnum::EMPRESA->value);
        $organization->setOwner($agent);
        $organization->setCreatedBy($agent);

        $this->organizationRepository->save($organization);

        $this->entityManager->clear();
        $foundOrganization = $this->organizationRepository->find($organization->getId());

        $this->assertNotNull($foundOrganization);
        $this->assertEquals('Test Organization', $foundOrganization->getName());
    }

    public function testFindOneById(): void
    {
        $agent = $this->createAgent();

        $organization = new Organization();
        $organization->setId(Uuid::v4());
        $organization->setName('Find Test Org');
        $organization->setType(OrganizationTypeEnum::EMPRESA->value);
        $organization->setOwner($agent);
        $organization->setCreatedBy($agent);

        $this->organizationRepository->save($organization);
        $this->entityManager->clear();

        $foundOrganization = $this->organizationRepository->findOneById($organization->getId()->toRfc4122());

        $this->assertNotNull($foundOrganization);
        $this->assertEquals('Find Test Org', $foundOrganization->getName());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        unset($this->entityManager);
    }
}
