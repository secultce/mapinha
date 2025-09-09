<?php

declare(strict_types=1);

namespace App\DataFixtures\Entity;

use App\Entity\Agent;
use App\Entity\Seal;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class SealFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public const string SEAL_ID_PREFIX = 'seal';
    public const string SEAL_ID_1 = '3120766e-b2bc-4b55-95d8-9b148fc26dd8';
    public const string SEAL_ID_2 = '0ded6ba1-95df-49ca-a3eb-bc369e087cdd';
    public const string SEAL_ID_3 = '2085d7dd-5916-4421-b113-60060ba25208';
    public const string SEAL_ID_4 = '282b2509-71d7-4697-877d-1ab2cc74335a';
    public const string SEAL_ID_5 = '97b2e717-abd3-472a-8abb-772a8f8d3e8b';
    public const string SEAL_ID_6 = '45e6fcb7-61d9-42bc-b96a-70aa4f204a64';
    public const string SEAL_ID_7 = '4c474b60-dd55-432b-9fcd-bb9cacc6d09b';
    public const string SEAL_ID_8 = 'e4f700e8-a81a-4cb2-81b1-b480492a38df';
    public const string SEAL_ID_9 = '9c5a1d45-3cc5-4c53-adbf-a4a1e0cbe09d';
    public const string SEAL_ID_10 = 'f7610415-5c71-46eb-80fa-f05e55fc0730';

    public const array SEALS = [
        [
            'id' => self::SEAL_ID_1,
            'name' => 'Qualidade Cult',
            'description' => 'Selo para validar eventos culturais de alta qualidade.',
            'active' => true,
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'expirationDate' => '2025-12-31T00:00:00+00:00',
            'createdAt' => '2024-07-10T11:30:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::SEAL_ID_2,
            'name' => 'Inovação Tecnológica',
            'description' => 'Selo que reconhece eventos com destaque em tecnologia.',
            'active' => true,
            'createdBy' => AgentFixtures::AGENT_ID_2,
            'expirationDate' => '2025-12-31T00:00:00+00:00',
            'createdAt' => '2024-08-15T10:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::SEAL_ID_3,
            'name' => 'Sustentabilidade',
            'description' => 'Selo para iniciativas comprometidas com práticas sustentáveis.',
            'active' => false,
            'createdBy' => AgentFixtures::AGENT_ID_3,
            'expirationDate' => '2025-12-31T00:00:00+00:00',
            'createdAt' => '2024-09-01T09:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::SEAL_ID_4,
            'name' => 'Inclusão Social',
            'description' => 'Selo para eventos que promovem diversidade e inclusão.',
            'active' => true,
            'createdBy' => AgentFixtures::AGENT_ID_4,
            'expirationDate' => '2025-12-31T00:00:00+00:00',
            'createdAt' => '2024-09-15T14:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::SEAL_ID_5,
            'name' => 'Excelência Educacional',
            'description' => 'Selo que destaca projetos com impacto na educação.',
            'active' => true,
            'createdBy' => AgentFixtures::AGENT_ID_5,
            'expirationDate' => '2025-12-31T00:00:00+00:00',
            'createdAt' => '2024-10-01T08:45:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::SEAL_ID_6,
            'name' => 'Tradição Cultural',
            'description' => 'Selo que reconhece eventos que preservam tradições culturais.',
            'active' => false,
            'createdBy' => AgentFixtures::AGENT_ID_6,
            'expirationDate' => '2025-12-31T00:00:00+00:00',
            'createdAt' => '2024-10-20T11:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::SEAL_ID_7,
            'name' => 'Impacto Social',
            'description' => 'Selo que destaca ações com impacto positivo na sociedade.',
            'active' => true,
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'expirationDate' => '2025-12-31T00:00:00+00:00',
            'createdAt' => '2024-11-01T10:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::SEAL_ID_8,
            'name' => 'Iniciativa Jovem',
            'description' => 'Selo para reconhecer projetos liderados por jovens talentos.',
            'active' => true,
            'createdBy' => AgentFixtures::AGENT_ID_3,
            'expirationDate' => '2025-12-31T00:00:00+00:00',
            'createdAt' => '2024-11-15T15:30:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::SEAL_ID_9,
            'name' => 'Artes e Ofícios',
            'description' => 'Selo que valoriza projetos relacionados a artesanato e cultura manual.',
            'active' => false,
            'createdBy' => AgentFixtures::AGENT_ID_2,
            'expirationDate' => '2025-12-31T00:00:00+00:00',
            'createdAt' => '2024-11-25T09:20:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::SEAL_ID_10,
            'name' => 'Reconhecimento Regional',
            'description' => 'Selo que destaca eventos com impacto em comunidades locais.',
            'active' => true,
            'createdBy' => AgentFixtures::AGENT_ID_4,
            'expirationDate' => '2025-12-31T00:00:00+00:00',
            'createdAt' => '2024-12-01T13:00:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
    ];

    public const array SEALS_UPDATED = [
        [
            'id' => self::SEAL_ID_1,
            'name' => 'Qualidade Cultural',
            'description' => 'Selo para validar eventos culturais de alta qualidade.',
            'active' => true,
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'expirationDate' => '2025-12-31T00:00:00+00:00',
            'createdAt' => '2024-07-10T11:30:00+00:00',
            'updatedAt' => '2024-07-10T11:37:00+00:00',
            'deletedAt' => null,
        ],
    ];

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected TokenStorageInterface $tokenStorage,
        private readonly SerializerInterface $serializer,
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
        $this->createSeals($manager);
        $this->updateSeals($manager);
        $this->manualLogout();
    }

    private function createSeals(ObjectManager $manager): void
    {
        foreach (self::SEALS as $sealData) {
            $seal = $this->mountSeal($sealData);

            $this->setReference(sprintf('%s-%s', self::SEAL_ID_PREFIX, $sealData['id']), $seal);

            $this->manualLoginByAgent($sealData['createdBy']);

            $manager->persist($seal);
        }

        $manager->flush();
    }

    private function updateSeals(ObjectManager $manager): void
    {
        foreach (self::SEALS_UPDATED as $sealData) {
            $sealObj = $this->getReference(sprintf('%s-%s', self::SEAL_ID_PREFIX, $sealData['id']), Seal::class);

            $seal = $this->mountSeal($sealData, ['object_to_populate' => $sealObj]);

            $this->manualLoginByAgent($sealData['createdBy']);

            $manager->persist($seal);
        }

        $manager->flush();
    }

    private function mountSeal(array $sealData, array $context = []): Seal
    {
        /** @var Seal $seal */
        $seal = $this->serializer->denormalize($sealData, Seal::class, context: $context);

        $seal->setCreatedBy($this->getReference(sprintf('%s-%s', AgentFixtures::AGENT_ID_PREFIX, $sealData['createdBy']), Agent::class));

        return $seal;
    }
}
