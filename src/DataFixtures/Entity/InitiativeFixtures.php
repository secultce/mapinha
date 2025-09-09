<?php

declare(strict_types=1);

namespace App\DataFixtures\Entity;

use App\Entity\Agent;
use App\Entity\Initiative;
use App\Entity\Space;
use App\Enum\SocialNetworkEnum;
use App\Service\Interface\FileServiceInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class InitiativeFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public const string INITIATIVE_ID_PREFIX = 'initiative';
    public const string INITIATIVE_ID_1 = 'f0774ecd-4860-4b8c-9607-32090dc31f71';
    public const string INITIATIVE_ID_2 = 'a65a9657-c527-4f33-a06e-60c2e219136e';
    public const string INITIATIVE_ID_3 = 'd12efd05-efc2-457a-a59e-8183147ed9ec';
    public const string INITIATIVE_ID_4 = 'd68dc96e-a864-4bb1-ab3d-dec2c2dbae7b';
    public const string INITIATIVE_ID_5 = 'd4301de5-7f5d-4817-bae0-3152674ade73';
    public const string INITIATIVE_ID_6 = '5d850939-26ef-49b5-a912-f825967271a4';
    public const string INITIATIVE_ID_7 = '26c2aaf2-bf08-41d9-b036-7d6b4e56c350';
    public const string INITIATIVE_ID_8 = '7241b715-450a-42db-b707-225dc3ab988c';
    public const string INITIATIVE_ID_9 = '7cb6f1b8-f34e-4218-ab41-f10b0f74e4d1';
    public const string INITIATIVE_ID_10 = '8c4c48bd-6e63-4b62-858b-066969c49f66';

    public const array INITIATIVES = [
        [
            'id' => self::INITIATIVE_ID_1,
            'name' => 'Voz',
            'image' => null,
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'parent' => null,
            'space' => SpaceFixtures::SPACE_ID_4,
            'extraFields' => [
                'culturalLanguage' => 'Musical',
                'period' => [
                    'startDate' => '2024-08-01',
                    'endDate' => '2024-08-31',
                ],
                'shortDescription' => 'Vozes do Sertão é um festival de música que reúne artistas de todo o Brasil para celebrar a cultura nordestina.',
            ],
            'socialNetworks' => [
                SocialNetworkEnum::INSTAGRAM->value => 'vozdosertao',
            ],
            'createdAt' => '2024-07-10T11:30:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INITIATIVE_ID_2,
            'name' => 'Raízes e Tradições',
            'image' => null,
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'parent' => null,
            'space' => null,
            'extraFields' => [
                'culturalLanguage' => 'Cultural',
                'period' => [
                    'startDate' => '2024-08-15',
                    'endDate' => '2024-09-15',
                ],
                'shortDescription' => 'Raízes e Tradições é uma exposição que reúne artesãos de todo o Brasil para celebrar a cultura nordestina.',
            ],
            'createdAt' => '2024-07-11T10:49:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INITIATIVE_ID_3,
            'name' => 'Ritmos do Mundo',
            'image' => null,
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'parent' => null,
            'space' => SpaceFixtures::SPACE_ID_5,
            'extraFields' => [
                'culturalLanguage' => 'Banda',
                'period' => [
                    'startDate' => '2024-08-01',
                    'endDate' => '2024-08-31',
                ],
                'shortDescription' => 'Ritmos do Mundo é um festival de música que reúne artistas de todo o mundo para celebrar a diversidade cultural.',
            ],
            'createdAt' => '2024-07-16T17:22:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INITIATIVE_ID_4,
            'name' => 'AxeZumbi',
            'image' => null,
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'parent' => null,
            'space' => null,
            'extraFields' => [
                'culturalLanguage' => 'Dança',
                'period' => [
                    'startDate' => '2024-10-01',
                    'endDate' => '2024-10-31',
                ],
                'shortDescription' => 'AxeZumbi é um festival de dança que reúne artistas de todo o Brasil para celebrar a cultura afro-brasileira.',
            ],
            'createdAt' => '2024-07-17T15:12:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INITIATIVE_ID_5,
            'name' => 'Repente e Viola',
            'image' => null,
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'parent' => null,
            'space' => SpaceFixtures::SPACE_ID_5,
            'extraFields' => [
                'culturalLanguage' => 'Concurso de Música',
                'period' => [
                    'startDate' => '2024-08-01',
                    'endDate' => '2024-08-31',
                ],
                'shortDescription' => 'Repente e Viola é um concurso de música que reúne artistas de todo o Brasil para celebrar a cultura nordestina.',
            ],
            'createdAt' => '2024-07-22T16:20:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INITIATIVE_ID_6,
            'name' => 'Pé de Serra Cultural',
            'image' => null,
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'parent' => null,
            'space' => SpaceFixtures::SPACE_ID_6,
            'extraFields' => [
                'culturalLanguage' => 'Concerto',
                'period' => [
                    'startDate' => '2024-08-01',
                    'endDate' => '2024-08-31',
                ],
                'shortDescription' => 'Pé de Serra Cultural é um concerto que reúne artistas de todo o Brasil para celebrar a cultura nordestina.',
            ],
            'createdAt' => '2024-08-10T11:26:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INITIATIVE_ID_7,
            'name' => 'Musicalizando',
            'image' => null,
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'parent' => null,
            'space' => null,
            'extraFields' => [
                'culturalLanguage' => 'Oficina Musical',
                'period' => [
                    'startDate' => '2024-08-01',
                    'endDate' => '2024-08-31',
                ],
                'shortDescription' => 'Musicalizando é uma oficina musical que reúne artistas de todo o Brasil para celebrar a cultura nordestina.',
            ],
            'createdAt' => '2024-08-11T15:54:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INITIATIVE_ID_8,
            'name' => 'Baião de Dois',
            'image' => null,
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'parent' => null,
            'space' => null,
            'extraFields' => [
                'culturalLanguage' => 'Festival Culinário',
                'period' => [
                    'startDate' => '2024-08-01',
                    'endDate' => '2024-08-31',
                ],
                'shortDescription' => 'Baião de Dois é um festival culinário que reúne chefs de todo o Brasil para celebrar a cultura nordestina.',
            ],
            'createdAt' => '2024-08-12T14:24:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INITIATIVE_ID_9,
            'name' => 'Retalhos do Nordeste',
            'image' => null,
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'parent' => self::INITIATIVE_ID_8,
            'space' => SpaceFixtures::SPACE_ID_6,
            'extraFields' => [
                'culturalLanguage' => 'Artesanato',
                'period' => [
                    'startDate' => '2024-08-01',
                    'endDate' => '2024-08-31',
                ],
                'shortDescription' => 'Retalhos do Nordeste é uma exposição de artesanato que reúne artistas de todo o Brasil para celebrar a cultura nordestina.',
            ],
            'createdAt' => '2024-08-13T20:25:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INITIATIVE_ID_10,
            'name' => 'Arte da Caatinga',
            'image' => null,
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'parent' => self::INITIATIVE_ID_9,
            'space' => SpaceFixtures::SPACE_ID_3,
            'extraFields' => [
                'culturalLanguage' => 'Exposição',
                'period' => [
                    'startDate' => '2024-08-01',
                    'endDate' => '2024-08-31',
                ],
                'shortDescription' => 'Arte da Caatinga é uma exposição de arte que reúne artistas de todo o Brasil para celebrar a cultura nordestina.',
            ],
            'createdAt' => '2024-08-14T10:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
    ];

    public const array INITIATIVES_UPDATED = [
        [
            'id' => self::INITIATIVE_ID_1,
            'name' => 'Vozes do Sertão',
            'image' => null,
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'parent' => null,
            'space' => SpaceFixtures::SPACE_ID_4,
            'extraFields' => [
                'culturalLanguage' => 'Musical',
                'period' => [
                    'startDate' => '2024-08-01',
                    'endDate' => '2024-08-31',
                ],
                'shortDescription' => 'Vozes do Sertão é um festival de música que reúne artistas de todo o Brasil para celebrar a cultura nordestina.',
            ],
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
            SpaceFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $this->createInitiatives($manager);
        $this->updateInitiatives($manager);
        $this->manualLogout();
    }

    private function createInitiatives(ObjectManager $manager): void
    {
        $counter = 0;

        foreach (self::INITIATIVES as $initiativeData) {
            if (5 > $counter) {
                $file = $this->fileService->uploadImage($this->parameterBag->get('app.dir.initiative.profile'), ImageFixtures::getInitiativeImage());
                $initiativeData['image'] = $file;
            }

            $initiative = $this->mountInitiative($initiativeData);

            $this->setReference(sprintf('%s-%s', self::INITIATIVE_ID_PREFIX, $initiativeData['id']), $initiative);

            $this->manualLoginByAgent($initiativeData['createdBy']);

            $manager->persist($initiative);
            $counter++;
        }

        $manager->flush();
    }

    private function updateInitiatives(ObjectManager $manager): void
    {
        foreach (self::INITIATIVES_UPDATED as $initiativeData) {
            $initiativeObj = $this->getReference(sprintf('%s-%s', self::INITIATIVE_ID_PREFIX, $initiativeData['id']), Initiative::class);

            $initiative = $this->mountInitiative($initiativeData, ['object_to_populate' => $initiativeObj]);

            $this->manualLoginByAgent($initiativeData['createdBy']);

            $manager->persist($initiative);
        }

        $manager->flush();
    }

    private function mountInitiative(array $initiativeData, array $context = []): Initiative
    {
        /** @var Initiative $initiative */
        $initiative = $this->serializer->denormalize($initiativeData, Initiative::class, context: $context);

        $initiative->setCreatedBy($this->getReference(sprintf('%s-%s', AgentFixtures::AGENT_ID_PREFIX, $initiativeData['createdBy']), Agent::class));

        if (null !== $initiativeData['parent']) {
            $parent = $this->getReference(sprintf('%s-%s', self::INITIATIVE_ID_PREFIX, $initiativeData['parent']), Initiative::class);
            $initiative->setParent($parent);
        }

        if (null !== $initiativeData['space']) {
            $parent = $this->getReference(sprintf('%s-%s', SpaceFixtures::SPACE_ID_PREFIX, $initiativeData['space']), Space::class);
            $initiative->setSpace($parent);
        }

        return $initiative;
    }
}
