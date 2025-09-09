<?php

declare(strict_types=1);

namespace App\DataFixtures\Entity;

use App\Entity\Agent;
use App\Entity\InscriptionPhase;
use App\Entity\InscriptionPhaseReview;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class InscriptionPhaseReviewFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public const string INSCRIPTION_PHASE_REVIEW_ID_PREFIX = 'inscription-phase-review';
    public const string INSCRIPTION_PHASE_REVIEW_ID_1 = 'f2284be6-04fd-416b-a43a-3e77faf8418f';
    public const string INSCRIPTION_PHASE_REVIEW_ID_2 = '92bcbc38-b037-44d0-b030-383c04819e97';
    public const string INSCRIPTION_PHASE_REVIEW_ID_3 = '3c760bf0-294e-4c65-92d3-0e5175880925';
    public const string INSCRIPTION_PHASE_REVIEW_ID_4 = '37f76e49-31d1-4993-be71-e90fc5209d1f';
    public const string INSCRIPTION_PHASE_REVIEW_ID_5 = 'b5085df8-52f2-42fc-a7fa-9fb6ee17a614';
    public const string INSCRIPTION_PHASE_REVIEW_ID_6 = 'cde769d0-eaa4-4a3b-b02c-234893a52561';
    public const string INSCRIPTION_PHASE_REVIEW_ID_7 = '8bc137fe-0891-49fb-9034-53c6d765991c';
    public const string INSCRIPTION_PHASE_REVIEW_ID_8 = 'dd648874-af24-4cbe-914d-30241492216a';
    public const string INSCRIPTION_PHASE_REVIEW_ID_9 = '1ae1ad2b-08bf-461c-b640-a9bbfd76e7cd';
    public const string INSCRIPTION_PHASE_REVIEW_ID_10 = '2e32d7fb-ad96-4916-9290-ae41543e413f';
    public const string INSCRIPTION_PHASE_REVIEW_ID_11 = '7957193b-a73e-4861-aea8-daa3b6bd27b5';
    public const string INSCRIPTION_PHASE_REVIEW_ID_12 = '4920c4d5-36fa-4e90-bbab-ba35b543a589';
    public const string INSCRIPTION_PHASE_REVIEW_ID_13 = '1d04eb43-fd8d-4f7d-9f21-8efec14e9d00';
    public const string INSCRIPTION_PHASE_REVIEW_ID_14 = '7dd47fc3-bf89-4be5-828f-ddeefd9d9258';
    public const string INSCRIPTION_PHASE_REVIEW_ID_15 = '21f78233-453a-480e-9f27-6c87bf761960';
    public const string INSCRIPTION_PHASE_REVIEW_ID_16 = '0a3392c5-f3ee-4a3f-8033-8347de5ed563';
    public const string INSCRIPTION_PHASE_REVIEW_ID_17 = '2ce49840-a49c-4cfd-881a-b2628f8d0154';
    public const string INSCRIPTION_PHASE_REVIEW_ID_18 = '35245867-c85b-4223-8024-9aec674df9a4';
    public const string INSCRIPTION_PHASE_REVIEW_ID_19 = '47a95301-5231-4fbe-9a4d-a4818e70cfa8';
    public const string INSCRIPTION_PHASE_REVIEW_ID_20 = '36706e97-9ebf-4fee-bf12-c416a6e11c0d';
    public const string INSCRIPTION_PHASE_REVIEW_ID_21 = '20b3755b-17d3-4963-8da3-95596c3ef954';
    public const string INSCRIPTION_PHASE_REVIEW_ID_22 = '83fbd308-f1fd-4849-a4d6-b169ee4ee7af';
    public const string INSCRIPTION_PHASE_REVIEW_ID_23 = '3e3ed251-47c1-4515-8ff7-ddb875d54b02';
    public const string INSCRIPTION_PHASE_REVIEW_ID_24 = '4c205f98-4c90-48eb-8619-28424b932e87';
    public const string INSCRIPTION_PHASE_REVIEW_ID_25 = '14650e23-9e37-4da2-bcd6-0e6e2958a18d';
    public const string INSCRIPTION_PHASE_REVIEW_ID_26 = 'e8217375-afc7-4b06-86af-54924d13510f';

    public const array DATA = [
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_1,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_1,
            'reviewer' => AgentFixtures::AGENT_ID_1,
            'result' => [],
            'createdAt' => '2024-07-10T11:30:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_2,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_2,
            'reviewer' => AgentFixtures::AGENT_ID_1,
            'result' => [],
            'createdAt' => '2024-07-10T11:31:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_3,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_3,
            'reviewer' => AgentFixtures::AGENT_ID_1,
            'result' => [],
            'createdAt' => '2024-07-10T11:33:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_4,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_4,
            'reviewer' => AgentFixtures::AGENT_ID_1,
            'result' => [],
            'createdAt' => '2024-07-10T11:34:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_5,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_5,
            'reviewer' => AgentFixtures::AGENT_ID_1,
            'result' => [],
            'createdAt' => '2024-07-10T11:35:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_6,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_6,
            'reviewer' => AgentFixtures::AGENT_ID_1,
            'result' => [],
            'createdAt' => '2024-07-10T11:36:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_7,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_7,
            'reviewer' => AgentFixtures::AGENT_ID_2,
            'result' => [],
            'createdAt' => '2024-07-10T11:37:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_8,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_8,
            'reviewer' => AgentFixtures::AGENT_ID_2,
            'result' => [],
            'createdAt' => '2024-07-10T11:38:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_9,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_9,
            'reviewer' => AgentFixtures::AGENT_ID_2,
            'result' => [],
            'createdAt' => '2024-07-10T11:39:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_10,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_10,
            'reviewer' => AgentFixtures::AGENT_ID_2,
            'result' => [],
            'createdAt' => '2024-07-10T11:40:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_11,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_11,
            'reviewer' => AgentFixtures::AGENT_ID_2,
            'result' => [],
            'createdAt' => '2024-07-10T11:41:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_12,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_12,
            'reviewer' => AgentFixtures::AGENT_ID_2,
            'result' => [],
            'createdAt' => '2024-07-10T11:42:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_13,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_13,
            'reviewer' => AgentFixtures::AGENT_ID_2,
            'result' => [],
            'createdAt' => '2024-07-10T11:43:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_14,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_14,
            'reviewer' => AgentFixtures::AGENT_ID_2,
            'result' => [],
            'createdAt' => '2024-07-10T11:44:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_15,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_15,
            'reviewer' => AgentFixtures::AGENT_ID_3,
            'result' => [],
            'createdAt' => '2024-07-10T11:45:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_16,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_16,
            'reviewer' => AgentFixtures::AGENT_ID_3,
            'result' => [],
            'createdAt' => '2024-07-10T11:46:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_17,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_17,
            'reviewer' => AgentFixtures::AGENT_ID_3,
            'result' => [],
            'createdAt' => '2024-07-10T11:47:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_18,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_18,
            'reviewer' => AgentFixtures::AGENT_ID_3,
            'result' => [],
            'createdAt' => '2024-07-10T11:48:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_19,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_19,
            'reviewer' => AgentFixtures::AGENT_ID_3,
            'result' => [],
            'createdAt' => '2024-07-10T11:49:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_20,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_20,
            'reviewer' => AgentFixtures::AGENT_ID_3,
            'result' => [],
            'createdAt' => '2024-07-10T11:50:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_21,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_21,
            'reviewer' => AgentFixtures::AGENT_ID_3,
            'result' => [],
            'createdAt' => '2024-07-10T11:51:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_22,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_22,
            'reviewer' => AgentFixtures::AGENT_ID_3,
            'result' => [],
            'createdAt' => '2024-07-10T11:52:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_23,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_23,
            'reviewer' => AgentFixtures::AGENT_ID_4,
            'result' => [],
            'createdAt' => '2024-07-10T11:53:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_24,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_24,
            'reviewer' => AgentFixtures::AGENT_ID_4,
            'result' => [],
            'createdAt' => '2024-07-10T11:54:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_25,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_25,
            'reviewer' => AgentFixtures::AGENT_ID_4,
            'result' => [],
            'createdAt' => '2024-07-10T11:55:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_26,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_26,
            'reviewer' => AgentFixtures::AGENT_ID_4,
            'result' => [],
            'createdAt' => '2024-07-10T11:56:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
        ],
    ];

    public const array DATA_UPDATED = [
        [
            'id' => self::INSCRIPTION_PHASE_REVIEW_ID_1,
            'inscriptionPhase' => InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_2,
            'reviewer' => AgentFixtures::AGENT_ID_1,
            'result' => [],
            'createdAt' => '2024-07-10T11:30:00+00:00',
            'updatedAt' => '2024-07-10T12:20:00+00:00',
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
            InscriptionPhaseFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $this->createInscriptionPhaseReview($manager);
        $this->updateInscriptionPhaseReview($manager);
        $this->manualLogout();
    }

    private function createInscriptionPhaseReview(ObjectManager $manager): void
    {
        foreach (self::DATA as $inscriptionPhaseReviewData) {
            $inscriptionPhaseReview = $this->mountInscriptionPhaseReview($inscriptionPhaseReviewData);

            $this->manualLoginByAgent($inscriptionPhaseReviewData['reviewer']);

            $this->setReference(sprintf('%s-%s', self::INSCRIPTION_PHASE_REVIEW_ID_PREFIX, $inscriptionPhaseReviewData['id']), $inscriptionPhaseReview);

            $manager->persist($inscriptionPhaseReview);
        }

        $manager->flush();
    }

    private function updateInscriptionPhaseReview(ObjectManager $manager): void
    {
        foreach (self::DATA_UPDATED as $inscriptionPhaseReviewData) {
            $inscriptionPhaseReviewObj = $this->getReference(sprintf('%s-%s', self::INSCRIPTION_PHASE_REVIEW_ID_PREFIX, $inscriptionPhaseReviewData['id']), InscriptionPhaseReview::class);

            $inscriptionPhaseReview = $this->mountInscriptionPhaseReview($inscriptionPhaseReviewData, ['object_to_populate' => $inscriptionPhaseReviewObj]);

            $this->manualLoginByAgent($inscriptionPhaseReviewData['reviewer']);

            $manager->persist($inscriptionPhaseReview);
        }

        $manager->flush();
    }

    private function mountInscriptionPhaseReview(mixed $inscriptionPhaseReviewData, array $context = []): InscriptionPhaseReview
    {
        /** @var InscriptionPhaseReview $inscriptionPhaseReview */
        $inscriptionPhaseReview = $this->serializer->denormalize($inscriptionPhaseReviewData, InscriptionPhaseReview::class, context: $context);

        $inscriptionPhaseReview->setReviewer($this->getReference(sprintf('%s-%s', AgentFixtures::AGENT_ID_PREFIX, $inscriptionPhaseReviewData['reviewer']), Agent::class));
        $inscriptionPhaseReview->setInscriptionPhase($this->getReference(sprintf('%s-%s', InscriptionPhaseFixtures::INSCRIPTION_PHASE_ID_PREFIX, $inscriptionPhaseReviewData['inscriptionPhase']), InscriptionPhase::class));

        return $inscriptionPhaseReview;
    }
}
