<?php

declare(strict_types=1);

namespace App\DataFixtures\Entity;

use App\Entity\Agent;
use App\Entity\Event;
use App\Entity\Initiative;
use App\Entity\Opportunity;
use App\Entity\Space;
use App\Enum\SocialNetworkEnum;
use App\Service\Interface\FileServiceInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class OpportunityFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public const string OPPORTUNITY_ID_PREFIX = 'opportunity';
    public const string OPPORTUNITY_ID_1 = '2f31c3b5-4385-4f1d-afc5-6042875a168e';
    public const string OPPORTUNITY_ID_2 = '39994097-41d8-463c-a7eb-7d9a0b40e953';
    public const string OPPORTUNITY_ID_3 = 'd9de9ad1-0a4b-4e4f-a3bb-5d61b9291339';
    public const string OPPORTUNITY_ID_4 = '378cc989-c2ae-4118-9f19-54bacb8718c4';
    public const string OPPORTUNITY_ID_5 = 'd1068a81-c006-4358-8846-a95ef252c881';
    public const string OPPORTUNITY_ID_6 = '45b263fa-952f-4e24-ad89-e715c08ab87e';
    public const string OPPORTUNITY_ID_7 = 'f8e0d2bb-a4d7-48e9-914b-85c563b6dd56';
    public const string OPPORTUNITY_ID_8 = '18db4f50-ff61-4cee-aedb-7bcee0908537';
    public const string OPPORTUNITY_ID_9 = 'ee2a9645-58a6-44d0-bf31-a908a9d1814d';
    public const string OPPORTUNITY_ID_10 = '083ef392-4c63-4200-a57f-818a1a75211c';

    public const array OPPORTUNITIES = [
        [
            'id' => self::OPPORTUNITY_ID_1,
            'name' => 'Inscrição para o Concurso de Cordelistas',
            'image' => null,
            'description' => 'Aberto edital para inscrições no concurso de cordelistas que ocorrerá durante o Festival de Literatura Nordestina.',
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'parent' => null,
            'space' => SpaceFixtures::SPACE_ID_1,
            'initiative' => InitiativeFixtures::INITIATIVE_ID_1,
            'event' => EventFixtures::EVENT_ID_1,
            'extraFields' => [
                'type' => 'Cultural',
                'period' => [
                    'startDate' => '2024-08-15',
                    'endDate' => '2024-09-15',
                ],
                'description' => 'O Festival de Literatura Nordestina é um evento cultural que reúne escritores, poetas, cordelistas e artistas populares para celebrar a literatura e a cultura do Nordeste.',
                'areasOfActivity' => ['Literatura', 'Cultura popular'],
                'tags' => ['Literatura', 'Cordel', 'Nordeste'],
            ],
            'socialNetworks' => [
                SocialNetworkEnum::INSTAGRAM->value => 'concursodecordelistas',
            ],
            'createdAt' => '2024-09-01T10:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::OPPORTUNITY_ID_2,
            'name' => 'Chamada para Oficinas de Artesanato - Feira de Cultura Popular',
            'image' => null,
            'description' => 'Estão abertas as inscrições para artesãos que desejam ministrar oficinas na Feira de Cultura Popular.',
            'createdBy' => AgentFixtures::AGENT_ID_2,
            'parent' => null,
            'space' => SpaceFixtures::SPACE_ID_2,
            'initiative' => InitiativeFixtures::INITIATIVE_ID_2,
            'event' => EventFixtures::EVENT_ID_2,
            'extraFields' => [
                'type' => 'Artesanato',
                'period' => [
                    'startDate' => '2024-10-01',
                    'endDate' => '2024-10-20',
                ],
                'description' => 'A Feira de Cultura Popular reúne artesãos de todo o Nordeste para ministrar oficinas e compartilhar suas técnicas e saberes.',
                'areasOfActivity' => ['Artesanato', 'Cultura popular'],
                'tags' => ['Artesanato', 'Feira', 'Cultura'],
            ],
            'createdAt' => '2024-09-04T10:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::OPPORTUNITY_ID_3,
            'name' => 'Credenciamento de Quadrilhas Juninas - São João do Nordeste',
            'image' => null,
            'description' => 'Edital de credenciamento para quadrilhas juninas interessadas em participar do São João do Nordeste.',
            'createdBy' => AgentFixtures::AGENT_ID_3,
            'parent' => null,
            'space' => SpaceFixtures::SPACE_ID_3,
            'initiative' => InitiativeFixtures::INITIATIVE_ID_3,
            'event' => EventFixtures::EVENT_ID_3,
            'extraFields' => [
                'type' => 'Cultural',
                'period' => [
                    'startDate' => '2024-06-01',
                    'endDate' => '2024-06-30',
                ],
                'description' => 'O São João do Nordeste celebra as tradições juninas com apresentações de quadrilhas e eventos culturais.',
                'areasOfActivity' => ['Dança', 'Cultura popular'],
                'tags' => ['Quadrilhas', 'São João', 'Nordeste'],
            ],
            'createdAt' => '2024-09-06T10:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::OPPORTUNITY_ID_4,
            'name' => 'Inscrição para o Festival de Danças Folclóricas - Encontro Nordestino',
            'image' => null,
            'description' => 'Concurso de danças folclóricas aberto para inscrições, integrando o Encontro Nordestino de Cultura.',
            'createdBy' => AgentFixtures::AGENT_ID_4,
            'parent' => null,
            'space' => SpaceFixtures::SPACE_ID_4,
            'initiative' => InitiativeFixtures::INITIATIVE_ID_4,
            'event' => EventFixtures::EVENT_ID_4,
            'extraFields' => [
                'type' => 'Cultural',
                'period' => [
                    'startDate' => '2024-11-01',
                    'endDate' => '2024-11-15',
                ],
                'description' => 'O Encontro Nordestino celebra a diversidade cultural da região, com destaque para as danças folclóricas tradicionais.',
                'areasOfActivity' => ['Dança', 'Cultura popular'],
                'tags' => ['Danças folclóricas', 'Nordeste', 'Cultura popular'],
            ],
            'createdAt' => '2024-09-08T10:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::OPPORTUNITY_ID_5,
            'name' => 'Edital de Patrocínio para Grupos de Maracatu - Carnaval Cultural',
            'image' => null,
            'description' => 'Oportunidade de patrocínio para grupos de Maracatu que irão se apresentar no Carnaval Cultural.',
            'createdBy' => AgentFixtures::AGENT_ID_5,
            'parent' => null,
            'space' => SpaceFixtures::SPACE_ID_5,
            'initiative' => InitiativeFixtures::INITIATIVE_ID_5,
            'event' => EventFixtures::EVENT_ID_5,
            'extraFields' => [
                'type' => 'Cultural',
                'period' => [
                    'startDate' => '2024-02-01',
                    'endDate' => '2024-03-01',
                ],
                'description' => 'O Carnaval Cultural traz a tradição dos grupos de Maracatu, oferecendo apoio financeiro para suas apresentações.',
                'areasOfActivity' => ['Música', 'Cultura popular'],
                'tags' => ['Maracatu', 'Carnaval', 'Nordeste'],
            ],
            'createdAt' => '2024-09-10T10:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::OPPORTUNITY_ID_6,
            'name' => 'Chamada para Exposição de Artes Visuais - Mostra Nordeste de Arte Contemporânea',
            'image' => null,
            'description' => 'Inscrições abertas para artistas visuais interessados em participar da Mostra Nordeste de Arte Contemporânea.',
            'createdBy' => AgentFixtures::AGENT_ID_6,
            'parent' => null,
            'space' => SpaceFixtures::SPACE_ID_6,
            'initiative' => InitiativeFixtures::INITIATIVE_ID_6,
            'event' => EventFixtures::EVENT_ID_6,
            'extraFields' => [
                'type' => 'Exposição',
                'period' => [
                    'startDate' => '2024-09-20',
                    'endDate' => '2024-10-05',
                ],
                'description' => 'A Mostra Nordeste de Arte Contemporânea destaca novos talentos das artes visuais e suas conexões com a cultura nordestina.',
                'areasOfActivity' => ['Artes visuais', 'Cultura contemporânea'],
                'tags' => ['Artes visuais', 'Exposição', 'Nordeste'],
            ],
            'createdAt' => '2024-09-11T10:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::OPPORTUNITY_ID_7,
            'name' => 'Inscrição para Palestras sobre Culinária Regional - Festival Gastronômico Nordestino',
            'image' => null,
            'description' => 'Chefs e estudiosos da culinária nordestina podem se inscrever para palestras no Festival Gastronômico.',
            'createdBy' => AgentFixtures::AGENT_ID_7,
            'parent' => null,
            'space' => SpaceFixtures::SPACE_ID_7,
            'initiative' => InitiativeFixtures::INITIATIVE_ID_7,
            'event' => EventFixtures::EVENT_ID_7,
            'extraFields' => [
                'type' => 'Gastronomia',
                'period' => [
                    'startDate' => '2024-12-01',
                    'endDate' => '2024-12-10',
                ],
                'description' => 'O Festival Gastronômico Nordestino promove a culinária regional, destacando pratos típicos e palestras com especialistas.',
                'areasOfActivity' => ['Gastronomia', 'Cultura'],
                'tags' => ['Culinária', 'Gastronomia', 'Nordeste'],
            ],
            'createdAt' => '2024-09-12T10:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::OPPORTUNITY_ID_8,
            'name' => 'Convocatória para Mostra de Filmes Nordestinos - Cine Nordeste',
            'image' => null,
            'description' => 'Cineastas podem inscrever seus filmes para exibição na Mostra de Filmes Nordestinos.',
            'createdBy' => AgentFixtures::AGENT_ID_8,
            'parent' => null,
            'space' => SpaceFixtures::SPACE_ID_8,
            'initiative' => InitiativeFixtures::INITIATIVE_ID_8,
            'event' => EventFixtures::EVENT_ID_8,
            'extraFields' => [
                'type' => 'Cultural',
                'period' => [
                    'startDate' => '2024-07-01',
                    'endDate' => '2024-07-15',
                ],
                'description' => 'O Cine Nordeste exibe produções cinematográficas da região, promovendo o cinema nordestino e seus realizadores.',
                'areasOfActivity' => ['Cinema', 'Cultura popular'],
                'tags' => ['Cinema', 'Filmes', 'Nordeste'],
            ],
            'createdAt' => '2024-09-13T10:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::OPPORTUNITY_ID_9,
            'name' => 'Chamada para Oficinas de Teatro de Rua - Encontro de Artes Cênicas Nordestinas',
            'image' => null,
            'description' => 'Teatralistas podem se inscrever para ministrar oficinas de teatro de rua no Encontro de Artes Cênicas.',
            'createdBy' => AgentFixtures::AGENT_ID_9,
            'parent' => null,
            'space' => SpaceFixtures::SPACE_ID_9,
            'initiative' => InitiativeFixtures::INITIATIVE_ID_9,
            'event' => EventFixtures::EVENT_ID_9,
            'extraFields' => [
                'type' => 'Teatro',
                'period' => [
                    'startDate' => '2024-08-01',
                    'endDate' => '2024-08-15',
                ],
                'description' => 'O Encontro de Artes Cênicas Nordestinas reúne artistas de teatro de rua para oficinas, apresentações e debates sobre a arte cênica.',
                'areasOfActivity' => ['Teatro', 'Cultura popular'],
                'tags' => ['Teatro', 'Artes cênicas', 'Nordeste'],
            ],
            'createdAt' => '2024-09-14T10:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::OPPORTUNITY_ID_10,
            'name' => 'Edital para Seleção de Artistas de Rua - Circuito Cultural Nordestino',
            'image' => null,
            'description' => 'Artistas de rua podem se inscrever para participar do Circuito Cultural Nordestino, promovendo a cultura popular.',
            'createdBy' => AgentFixtures::AGENT_ID_10,
            'parent' => null,
            'space' => SpaceFixtures::SPACE_ID_10,
            'initiative' => InitiativeFixtures::INITIATIVE_ID_10,
            'event' => EventFixtures::EVENT_ID_10,
            'extraFields' => [
                'type' => 'Cultural',
                'period' => [
                    'startDate' => '2024-05-01',
                    'endDate' => '2024-05-15',
                ],
                'description' => 'O Circuito Cultural Nordestino leva artistas de rua para apresentações em espaços públicos, promovendo a cultura popular e a arte de rua.',
                'areasOfActivity' => ['Arte de rua', 'Cultura popular'],
                'tags' => ['Arte de rua', 'Cultura', 'Nordeste'],
            ],
            'createdAt' => '2024-09-15T10:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
    ];

    public const array OPPORTUNITIES_UPDATED = [
        [
            'id' => self::OPPORTUNITY_ID_1,
            'name' => 'Inscrição para o Concurso de Cordelistas - Festival de Literatura Nordestina',
            'description' => 'Aberto edital para inscrições no concurso de cordelistas que ocorrerá durante o Festival de Literatura Nordestina.',
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'parent' => null,
            'space' => SpaceFixtures::SPACE_ID_1,
            'initiative' => InitiativeFixtures::INITIATIVE_ID_1,
            'event' => EventFixtures::EVENT_ID_1,
            'createdAt' => '2024-09-01T10:00:00+00:00',
            'updatedAt' => '2024-09-01T10:00:00+00:00',
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
            SpaceFixtures::class,
            InitiativeFixtures::class,
            EventFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $this->createOpportunities($manager);
        $this->updateOpportunities($manager);
        $this->manualLogout();
    }

    public function createOpportunities(ObjectManager $manager): void
    {
        $counter = 0;

        foreach (self::OPPORTUNITIES as $opportunityData) {
            if (5 > $counter) {
                $file = $this->fileService->uploadImage(
                    $this->parameterBag->get('app.dir.opportunity.profile'),
                    ImageFixtures::getOpportunityImage()
                );
                $opportunityData['image'] = $file;
            }

            $opportunity = $this->mountOpportunity($opportunityData);

            $this->setReference(sprintf('%s-%s', self::OPPORTUNITY_ID_PREFIX, $opportunityData['id']), $opportunity);

            $this->manualLoginByAgent($opportunityData['createdBy']);

            $manager->persist($opportunity);
            $counter++;
        }

        $manager->flush();
    }

    public function updateOpportunities(ObjectManager $manager): void
    {
        foreach (self::OPPORTUNITIES_UPDATED as $opportunityData) {
            $opportunityObj = $this->getReference(sprintf('%s-%s', self::OPPORTUNITY_ID_PREFIX, $opportunityData['id']), Opportunity::class);

            $opportunity = $this->mountOpportunity($opportunityData, ['object_to_populate' => $opportunityObj]);

            $this->manualLoginByAgent($opportunityData['createdBy']);

            $manager->persist($opportunity);
        }

        $manager->flush();
    }

    private function mountOpportunity(array $opportunityData, array $context = []): Opportunity
    {
        /** @var Opportunity $opportunity */
        $opportunity = $this->serializer->denormalize($opportunityData, Opportunity::class, context: $context);

        $opportunity->setCreatedBy($this->getReference(sprintf('%s-%s', AgentFixtures::AGENT_ID_PREFIX, $opportunityData['createdBy']), Agent::class));

        if (null !== $opportunityData['parent']) {
            $parent = $this->getReference(sprintf('%s-%s', self::OPPORTUNITY_ID_PREFIX, $opportunityData['parent']), Opportunity::class);
            $opportunity->setParent($parent);
        }

        if (null !== $opportunityData['space']) {
            $space = $this->getReference(sprintf('%s-%s', SpaceFixtures::SPACE_ID_PREFIX, $opportunityData['space']), Space::class);
            $opportunity->setSpace($space);
        }

        if (null !== $opportunityData['initiative']) {
            $initiative = $this->getReference(sprintf('%s-%s', InitiativeFixtures::INITIATIVE_ID_PREFIX, $opportunityData['initiative']), Initiative::class);
            $opportunity->setInitiative($initiative);
        }

        if (null !== $opportunityData['event']) {
            $event = $this->getReference(sprintf('%s-%s', EventFixtures::EVENT_ID_PREFIX, $opportunityData['event']), Event::class);
            $opportunity->setEvent($event);
        }

        return $opportunity;
    }
}
