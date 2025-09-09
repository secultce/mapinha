<?php

declare(strict_types=1);

namespace App\DataFixtures\Entity;

use App\Entity\Agent;
use App\Entity\Organization;
use App\Enum\OrganizationTypeEnum;
use App\Enum\SocialNetworkEnum;
use App\Service\Interface\FileServiceInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class OrganizationFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public const string ORGANIZATION_ID_PREFIX = 'organization';
    public const string ORGANIZATION_ID_1 = 'bc89ea8d-6ad7-4cb8-92a9-b56ce203c7dd';
    public const string ORGANIZATION_ID_2 = 'a65aa657-c537-1f33-c06e-31c2e219136e';
    public const string ORGANIZATION_ID_3 = 'd12ead05-ef32-157a-c59e-4a83147ed9ec';
    public const string ORGANIZATION_ID_4 = 'd68da96e-a834-1bb1-cb3d-5ac2c2dbae7b';
    public const string ORGANIZATION_ID_5 = 'd430ade5-7f3d-1817-cae0-7152674ade73';
    public const string ORGANIZATION_ID_6 = '5d85a939-263f-19b5-c912-7825967271a4';
    public const string ORGANIZATION_ID_7 = '26c2aaf2-bf38-11d9-c036-7d6b4e56c350';
    public const string ORGANIZATION_ID_8 = '7241a715-453a-12db-c707-725dc3ab988c';
    public const string ORGANIZATION_ID_9 = '7cb6a1b8-f33e-1218-cb41-820b0f74e4d1';
    public const string ORGANIZATION_ID_10 = '8c4ca8bd-6e33-1b62-c58b-a66969c49f66';
    public const string ORGANIZATION_ID_11 = '8c4ca8bd-6e33-1b62-c58b-a66969c49f77';

    public const array ORGANIZATIONS = [
        [
            'id' => self::ORGANIZATION_ID_1,
            'name' => 'PHP com rapadura',
            'image' => null,
            'type' => OrganizationTypeEnum::COMUNIDADE->value,
            'description' => 'Comunidade de devs PHP do Estado do Ceará',
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'owner' => AgentFixtures::AGENT_ID_1,
            'agents' => [
                AgentFixtures::AGENT_ID_1,
            ],
            'parent' => null,
            'space' => null,
            'extraFields' => [
                'cnpj' => '00.000.000/0001-01',
                'email' => 'phpcomrapadura@example.com',
                'phone' => '(85) 99999-0001',
                'site' => 'https://www.phpcomrapadura.com.br',
            ],
            'socialNetworks' => [
                SocialNetworkEnum::INSTAGRAM->value => 'phpcomrapadura',
            ],
            'createdAt' => '2024-07-10T11:30:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::ORGANIZATION_ID_2,
            'name' => 'SECULT CE',
            'image' => null,
            'type' => OrganizationTypeEnum::EMPRESA->value,
            'description' => 'Secretaria de Cultura do Estado do Ceará',
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'owner' => AgentFixtures::AGENT_ID_1,
            'agents' => [
                AgentFixtures::AGENT_ID_1,
                AgentFixtures::AGENT_ID_2,
            ],
            'parent' => null,
            'space' => null,
            'extraFields' => [
                'cnpj' => '07.954.555/0001-11',
                'email' => 'agendagab@secult.ce.gov.br',
                'phone' => '(85) 99999-0002',
                'site' => 'https://www.secult.ce.gov.br/',
            ],
            'socialNetworks' => [
                SocialNetworkEnum::INSTAGRAM->value => 'secultceara',
            ],
            'createdAt' => '2024-07-11T10:49:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::ORGANIZATION_ID_3,
            'name' => 'Igreja de Russas',
            'image' => null,
            'type' => OrganizationTypeEnum::ENTIDADE->value,
            'description' => 'Paróquia Nossa Senhora da Consolação – Russas/CE',
            'createdBy' => AgentFixtures::AGENT_ID_3,
            'owner' => AgentFixtures::AGENT_ID_3,
            'agents' => [
                AgentFixtures::AGENT_ID_3,
            ],
            'parent' => null,
            'space' => null,
            'extraFields' => [
                'cnpj' => '04.117.525/0001-62',
                'email' => 'secretaria@igrejaderussas.org.br',
                'phone' => '(85) 99999-0003',
                'site' => 'https://www.igrejaderussas.org.br',
            ],
            'socialNetworks' => [
                SocialNetworkEnum::INSTAGRAM->value => 'igrejaderussas',
            ],
            'createdAt' => '2024-07-16T17:22:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::ORGANIZATION_ID_4,
            'name' => 'Grupo de Capoeira Axé Zumbi',
            'image' => null,
            'type' => OrganizationTypeEnum::COMUNIDADE->value,
            'description' => 'Grupo de Capoeira Axé Zumbi',
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'owner' => AgentFixtures::AGENT_ID_1,
            'agents' => [
                AgentFixtures::AGENT_ID_1,
            ],
            'parent' => null,
            'space' => null,
            'extraFields' => [
                'cnpj' => '00.000.000/0001-04',
                'email' => 'axezumbi@example.com',
                'phone' => '(85) 99999-0004',
                'site' => 'https://www.axezumbi.com.br',
            ],
            'socialNetworks' => [
                SocialNetworkEnum::INSTAGRAM->value => 'capoeiraaxezumbi',
            ],
            'createdAt' => '2024-07-17T15:12:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::ORGANIZATION_ID_5,
            'name' => 'PHPeste',
            'image' => null,
            'type' => OrganizationTypeEnum::COMUNIDADE->value,
            'description' => 'Organização da Conferencia de PHP do Nordeste',
            'createdBy' => AgentFixtures::AGENT_ID_3,
            'owner' => AgentFixtures::AGENT_ID_3,
            'agents' => [
                AgentFixtures::AGENT_ID_3,
            ],
            'parent' => null,
            'space' => null,
            'extraFields' => [
                'cnpj' => '00.000.000/0001-05',
                'email' => 'phpeste@example.com',
                'phone' => '(85) 99999-0005',
                'site' => 'https://www.phpeste.com.br',
            ],
            'socialNetworks' => [
                SocialNetworkEnum::INSTAGRAM->value => 'grupoderapente',
            ],
            'createdAt' => '2024-07-22T16:20:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::ORGANIZATION_ID_6,
            'name' => 'ONG Ambiental Ceará',
            'image' => null,
            'type' => OrganizationTypeEnum::ENTIDADE->value,
            'description' => 'Projetos de preservação ambiental no Ceará',
            'createdBy' => AgentFixtures::AGENT_ID_2,
            'owner' => AgentFixtures::AGENT_ID_2,
            'agents' => [
                AgentFixtures::AGENT_ID_2,
            ],
            'parent' => null,
            'space' => null,
            'extraFields' => [
                'cnpj' => '50.249.137/0001-52',
                'email' => 'contato@ongambientalce.org.br',
                'phone' => '(85) 99999-0006',
                'site' => 'https://www.ongambientalce.org.br',
            ],
            'socialNetworks' => [
                SocialNetworkEnum::INSTAGRAM->value => 'ongambientalce',
            ],
            'createdAt' => '2024-08-10T11:26:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::ORGANIZATION_ID_7,
            'name' => 'Folia Cearense ',
            'image' => null,
            'type' => OrganizationTypeEnum::EMPRESA->value,
            'description' => 'Produção e organização de expressões folclóricas e festas populares do Ceará',
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'owner' => AgentFixtures::AGENT_ID_1,
            'agents' => [
                AgentFixtures::AGENT_ID_1,
            ],
            'parent' => null,
            'space' => null,
            'extraFields' => [
                'cnpj' => '14.732.569/0001-03',
                'email' => 'contato@foliacearense.com.brm',
                'phone' => '(85) 99999-0007',
                'site' => 'https://www.foliacearense.com.br',
            ],
            'socialNetworks' => [
                SocialNetworkEnum::INSTAGRAM->value => 'foliacearense',
            ],
            'createdAt' => '2024-08-11T15:54:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::ORGANIZATION_ID_8,
            'name' => 'Federação Cearense de Skate',
            'image' => null,
            'type' => OrganizationTypeEnum::ENTIDADE->value,
            'description' => 'Entidade oficial, responsável pela regulamentação e gestão do Skate no Ceará',
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'owner' => AgentFixtures::AGENT_ID_1,
            'agents' => [
                AgentFixtures::AGENT_ID_1,
            ],
            'parent' => null,
            'space' => null,
            'extraFields' => [
                'cnpj' => '00.000.000/0001-08',
                'email' => 'fcskate@example.com',
                'phone' => '(85) 99999-0008',
                'site' => 'https://filiados.cbsk.com.br/site/login',
            ],
            'socialNetworks' => [
                SocialNetworkEnum::INSTAGRAM->value => 'fesk_skateboard',
            ],
            'createdAt' => '2024-08-12T14:24:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::ORGANIZATION_ID_9,
            'name' => '30praum',
            'image' => null,
            'type' => OrganizationTypeEnum::EMPRESA->value,
            'description' => 'Gravadora independente de trap, localizada em Fortaleza-CE',
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'owner' => AgentFixtures::AGENT_ID_1,
            'agents' => [
                AgentFixtures::AGENT_ID_1,
            ],
            'parent' => self::ORGANIZATION_ID_8,
            'space' => null,
            'extraFields' => [
                'cnpj' => '32.186.235/0001-06',
                'email' => '333@fashionlog.com.br',
                'phone' => '(85) 99999-0009',
                'site' => 'https://30praum.com.br/',
            ],
            'socialNetworks' => [
                SocialNetworkEnum::INSTAGRAM->value => '30praum',
            ],
            'createdAt' => '2024-08-13T20:25:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::ORGANIZATION_ID_10,
            'name' => 'Associação Cultural Cearense do Rock',
            'image' => null,
            'type' => OrganizationTypeEnum::ENTIDADE->value,
            'description' => 'Entidade sem fins lucrativos que busca promover o rock como elemento cultural e de transformação',
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'owner' => AgentFixtures::AGENT_ID_1,
            'agents' => [
                AgentFixtures::AGENT_ID_1,
            ],
            'parent' => self::ORGANIZATION_ID_9,
            'space' => null,
            'extraFields' => [
                'cnpj' => '00.000.000/0001-10',
                'email' => 'acr@example.com',
                'phone' => '(85) 99999-0010',
                'site' => 'https://www.acr.com.br',
            ],
            'socialNetworks' => [
                SocialNetworkEnum::INSTAGRAM->value => 'acr_ce',
            ],
            'createdAt' => '2024-08-14T10:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::ORGANIZATION_ID_11,
            'name' => 'Empresa Teste AI',
            'image' => null,
            'type' => OrganizationTypeEnum::EMPRESA->value,
            'description' => 'Organização do tipo EMPRESA para testes do painel',
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'owner' => AgentFixtures::AGENT_ID_1,
            'agents' => [
                AgentFixtures::AGENT_ID_1,
            ],
            'parent' => null,
            'space' => null,
            'extraFields' => [
                'cnpj' => '00.000.000/0001-00',
                'email' => 'teste@gmail.com,',
                'phone' => '(85) 99999-9999',
                'tipo' => 'OSC',
                'site' => 'https://www.empresa.com.br',
            ],
            'socialNetworks' => [
                SocialNetworkEnum::INSTAGRAM->value => 'empresa_ai_test',
            ],
            'createdAt' => '2024-08-20T09:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
    ];

    public const array ORGANIZATIONS_UPDATED = [
        [
            'id' => self::ORGANIZATION_ID_1,
            'name' => 'PHP com RAPadura',
            'image' => null,
            'type' => OrganizationTypeEnum::COMUNIDADE->value,
            'description' => 'Comunidade dos melhores devs PHP do Estado do Ceará',
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'owner' => AgentFixtures::AGENT_ID_1,
            'agents' => [
                AgentFixtures::AGENT_ID_1,
            ],
            'parent' => null,
            'space' => null,
            'createdAt' => '2024-07-10T11:30:00+00:00',
            'updatedAt' => '2024-07-10T12:20:00+00:00',
            'deletedAt' => null,
        ],
    ];

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected TokenStorageInterface $tokenStorage,
        private readonly SerializerInterface $serializer,
        private readonly FileServiceInterface $fileService,
        private readonly ParameterBagInterface $parameterBag,
    ) {
        parent::__construct($entityManager, $tokenStorage);
    }

    public function getDependencies(): array
    {
        return [
            AgentFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $this->createOrganizations($manager);
        $this->updateOrganizations($manager);
        $this->manualLogout();
    }

    private function mountOrganization(array $organizationData, array $context = []): Organization
    {
        $agents = $organizationData['agents'] ?? [];
        unset($organizationData['agents']);

        /** @var Organization $organization */
        $organization = $this->serializer->denormalize($organizationData, Organization::class, context: $context);

        foreach ($agents ?? [] as $agentId) {
            $organization->addAgent(
                $this->getReference(sprintf('%s-%s', AgentFixtures::AGENT_ID_PREFIX, $agentId), Agent::class)
            );
        }

        $organization->setCreatedBy($this->getReference(sprintf('%s-%s', AgentFixtures::AGENT_ID_PREFIX, $organizationData['createdBy']), Agent::class));
        $organization->setOwner($this->getReference(sprintf('%s-%s', AgentFixtures::AGENT_ID_PREFIX, $organizationData['owner']), Agent::class));

        return $organization;
    }

    private function createOrganizations(ObjectManager $manager): void
    {
        $counter = 0;

        foreach (self::ORGANIZATIONS as $organizationData) {
            if (5 > $counter) {
                $file = $this->fileService->uploadImage($this->parameterBag->get('app.dir.organization.profile'), ImageFixtures::getOrganizationImage());
                $organizationData['image'] = $file;
            }

            $organization = $this->mountOrganization($organizationData);

            $this->setReference(sprintf('%s-%s', self::ORGANIZATION_ID_PREFIX, $organizationData['id']), $organization);

            $this->manualLoginByAgent($organizationData['createdBy']);

            $manager->persist($organization);
            $counter++;
        }

        $manager->flush();
    }

    public function updateOrganizations(ObjectManager $manager): void
    {
        foreach (self::ORGANIZATIONS_UPDATED as $organizationData) {
            $organizationObj = $this->getReference(sprintf('%s-%s', self::ORGANIZATION_ID_PREFIX, $organizationData['id']), Organization::class);

            $organization = $this->mountOrganization($organizationData, ['object_to_populate' => $organizationObj]);

            $this->manualLoginByAgent($organizationData['createdBy']);

            $manager->persist($organization);
        }

        $manager->flush();
    }
}
