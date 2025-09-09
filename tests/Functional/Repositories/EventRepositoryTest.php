<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repositories;

use App\DataFixtures\Entity\AgentFixtures;
use App\Entity\Agent;
use App\Entity\CulturalLanguage;
use App\Entity\Event;
use App\Entity\Tag;
use App\Entity\User;
use App\Repository\EventRepository;
use App\Service\Interface\StateServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Uid\Uuid;

class EventRepositoryTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private EventRepository $repository;
    private StateServiceInterface $stateService;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $container = self::getContainer();

        $this->entityManager = $container->get(EntityManagerInterface::class);
        $this->repository = $this->entityManager->getRepository(Event::class);

        $mockUser = new User();
        $mockUser->setId(Uuid::v4());

        $tokenStorage = $container->get('security.token_storage');
        assert($tokenStorage instanceof TokenStorageInterface);

        $token = new UsernamePasswordToken($mockUser, 'main');
        $tokenStorage->setToken($token);

        $this->stateService = static::getContainer()->get(StateServiceInterface::class);
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

    private function createTestEvent(Agent $agent, string $name): Event
    {
        $event = new Event();
        $event->setId(Uuid::v4());
        $event->setName($name);
        $event->setDraft(false);
        $event->setCreatedBy($agent);
        $event->setStartDate(new DateTime('now'));

        $this->entityManager->persist($event);

        return $event;
    }

    public function testSaveAndFindEvent(): void
    {
        $agent = $this->createAgent();

        $eventName = 'Evento Funcional '.uniqid();
        $event = new Event();
        $event->setId(Uuid::v4());
        $event->setName($eventName);
        $event->setDraft(false);
        $event->setCreatedBy($agent);
        $event->setStartDate(new DateTime('2025-11-20 20:00:00'));

        $this->repository->save($event);
        $eventId = $event->getId();

        $this->entityManager->clear();

        $foundEvent = $this->repository->find($eventId);

        $this->assertNotNull($foundEvent);
        $this->assertEquals($eventName, $foundEvent->getName());
    }

    public function testFindByFiltersWithPeriod(): void
    {
        $filtersWithMatch = [
            'period' => [
                'start' => '2024-07-10',
                'end' => '2024-07-11',
            ],
        ];

        $foundEvents = $this->repository->findByFilters($filtersWithMatch, ['createdAt' => 'ASC'], 10);

        $this->assertCount(1, $foundEvents);
        $this->assertEquals('Festival Sertão Criativo', $foundEvents[0]->getName());

        $filtersWithoutMatch = [
            'period' => [
                'start' => '2025-01-01',
                'end' => '2025-01-31',
            ],
        ];

        $notFoundEvents = $this->repository->findByFilters($filtersWithoutMatch, ['createdAt' => 'ASC'], 10);
        $this->assertEmpty($notFoundEvents, 'Não deveria encontrar eventos em Janeiro/2025.');
    }

    public function testFindByFiltersWithName(): void
    {
        $filters = ['name' => 'Festival Sertão Criativo'];
        $orderBy = ['createdAt' => 'ASC'];
        $limit = 10;

        $foundEvents = $this->repository->findByFilters($filters, $orderBy, $limit);

        $this->assertCount(1, $foundEvents);
        $this->assertEquals('Festival Sertão Criativo', $foundEvents[0]->getName());
    }

    public function testFindByFiltersWithDraft(): void
    {
        $draftEvents = $this->repository->findByFilters(['draft' => true], ['createdAt' => 'ASC'], 10);
        $this->assertNotEmpty($draftEvents, 'Deveria encontrar pelo menos um evento com draft=true');
        $this->assertTrue($draftEvents[0]->isDraft());

        $publishedEvents = $this->repository->findByFilters(['draft' => false], ['createdAt' => 'ASC'], 10);
        $this->assertNotEmpty($publishedEvents, 'Deveria encontrar pelo menos um evento com draft=false');
        $this->assertFalse($publishedEvents[0]->isDraft());
    }

    public function testFindByFiltersWithTag(): void
    {
        $tagRepository = $this->entityManager->getRepository(Tag::class);
        $tag = $tagRepository->findOneBy(['name' => 'Tecnologia']);
        $this->assertNotNull($tag, 'A tag "Tecnologia" não foi encontrada nos fixtures.');

        $filters = ['tags' => $tag->getId()];
        $foundEvents = $this->repository->findByFilters($filters, ['createdAt' => 'ASC'], 10);

        $this->assertNotEmpty($foundEvents, 'Deveria encontrar eventos com a tag "Tecnologia".');
    }

    public function testFindByAgent(): void
    {
        $agentRepository = $this->entityManager->getRepository(Agent::class);
        $agent = $agentRepository->findOneBy(['id' => AgentFixtures::AGENT_ID_1]);
        $this->assertNotNull($agent, 'Agente de teste não encontrado.');

        $foundEvents = $this->repository->findByAgent($agent->getId()->toRfc4122());

        $this->assertCount(5, $foundEvents);
        $this->assertEquals('Cores do Sertão', $foundEvents[0]->getName());
    }

    public function testFindByFiltersWithState(): void
    {
        $state = $this->stateService->findBy(['name' => 'Bahia'])[0];

        $filters = ['state' => $state];
        $orderBy = ['createdAt' => 'ASC'];
        $limit = 10;

        $foundEvents = $this->repository->findByFilters($filters, $orderBy, $limit);

        $this->assertCount(1, $foundEvents);
        $this->assertEquals('PHP com Rapadura 10 anos', $foundEvents[0]->getName());
    }

    public function testFindByFiltersWithCulturalLanguage(): void
    {
        $languageRepository = $this->entityManager->getRepository(CulturalLanguage::class);
        $language = $languageRepository->findOneBy(['name' => 'Gastronomia']);
        $this->assertNotNull($language, 'A linguagem "Gastronomia" não foi encontrada nos fixtures.');

        $filters = ['culturalLanguages' => $language->getId()];
        $foundEvents = $this->repository->findByFilters($filters, ['createdAt' => 'ASC'], 10);

        $this->assertNotEmpty($foundEvents, 'Deveria encontrar eventos com a linguagem "Gastronomia".');
    }

    public function testFindByFiltersWithAgeRating(): void
    {
        $filters = ['ageRating' => 'Free'];

        $foundEvents = $this->repository->findByFilters($filters, ['createdAt' => 'ASC'], 10);

        $this->assertNotEmpty($foundEvents, 'Deveria encontrar eventos com classificação "Free".');

        foreach ($foundEvents as $event) {
            $extraFields = $event->getExtraFields();
            $this->assertArrayHasKey('ageRating', $extraFields);
            $this->assertEquals('Free', $extraFields['ageRating']);
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        unset($this->entityManager);
    }
}
