<?php

declare(strict_types=1);

namespace App\DataFixtures\Entity;

use App\Entity\EntityAssociation;
use App\Entity\Space;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class SpaceEntityAssociationFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public const string ENTITY_ASSOCIATION_ID_PREFIX = 'entity-association';
    public const string ENTITY_ASSOCIATION_ID_1 = 'b982c6f3-d792-4707-b580-8bc95eb02d35';
    public const string ENTITY_ASSOCIATION_ID_2 = '492488c2-36fc-4ce8-bd1e-033b772128fc';
    public const string ENTITY_ASSOCIATION_ID_3 = '6ddd2e47-c8dc-4e54-be56-079890f5ee8d';
    public const string ENTITY_ASSOCIATION_ID_4 = '04575db7-1169-4fa7-8b97-84f5c8b638c1';
    public const string ENTITY_ASSOCIATION_ID_5 = '36766110-bfcf-47bc-85fd-9c27e5f0781b';
    public const string ENTITY_ASSOCIATION_ID_6 = '440e12cd-43bb-4922-aa93-96b9bfa1c2bc';
    public const string ENTITY_ASSOCIATION_ID_7 = '8b595aa4-149d-4b18-a311-241c548857fd';
    public const string ENTITY_ASSOCIATION_ID_8 = 'b2c1e24c-8df2-41a1-94ad-c4aa742110ad';
    public const string ENTITY_ASSOCIATION_ID_9 = 'ab5bae95-b12e-4de2-b64d-eba9c419bb13';
    public const string ENTITY_ASSOCIATION_ID_10 = 'bdefc6cc-b965-4714-bff8-aa1fd6c8e6cb';

    public const array SPACE_ENTITIES_ASSOCIATIONS = [
        [
            'id' => self::ENTITY_ASSOCIATION_ID_1,
            'space' => SpaceFixtures::SPACE_ID_1,
            'withAgent' => true,
            'withEvent' => true,
            'withInitiative' => true,
            'withOpportunity' => true,
            'withOrganization' => true,
            'withSpace' => true,
            'createdBy' => AgentFixtures::AGENT_ID_1,
        ],
        [
            'id' => self::ENTITY_ASSOCIATION_ID_2,
            'space' => SpaceFixtures::SPACE_ID_2,
            'withAgent' => true,
            'withEvent' => true,
            'withInitiative' => true,
            'withOpportunity' => true,
            'withOrganization' => true,
            'withSpace' => true,
            'createdBy' => AgentFixtures::AGENT_ID_1,
        ],
        [
            'id' => self::ENTITY_ASSOCIATION_ID_3,
            'space' => SpaceFixtures::SPACE_ID_3,
            'withAgent' => true,
            'withEvent' => true,
            'withInitiative' => true,
            'withOpportunity' => true,
            'withOrganization' => true,
            'withSpace' => true,
            'createdBy' => AgentFixtures::AGENT_ID_2,
        ],
        [
            'id' => self::ENTITY_ASSOCIATION_ID_4,
            'space' => SpaceFixtures::SPACE_ID_4,
            'withAgent' => true,
            'withEvent' => true,
            'withInitiative' => true,
            'withOpportunity' => true,
            'withOrganization' => true,
            'withSpace' => true,
            'createdBy' => AgentFixtures::AGENT_ID_1,
        ],
        [
            'id' => self::ENTITY_ASSOCIATION_ID_5,
            'space' => SpaceFixtures::SPACE_ID_5,
            'withAgent' => true,
            'withEvent' => true,
            'withInitiative' => true,
            'withOpportunity' => true,
            'withOrganization' => true,
            'withSpace' => true,
            'createdBy' => AgentFixtures::AGENT_ID_1,
        ],
        [
            'id' => self::ENTITY_ASSOCIATION_ID_6,
            'space' => SpaceFixtures::SPACE_ID_6,
            'withAgent' => true,
            'withEvent' => true,
            'withInitiative' => true,
            'withOpportunity' => true,
            'withOrganization' => true,
            'withSpace' => true,
            'createdBy' => AgentFixtures::AGENT_ID_1,
        ],
        [
            'id' => self::ENTITY_ASSOCIATION_ID_7,
            'space' => SpaceFixtures::SPACE_ID_7,
            'withAgent' => true,
            'withEvent' => true,
            'withInitiative' => true,
            'withOpportunity' => true,
            'withOrganization' => true,
            'withSpace' => true,
            'createdBy' => AgentFixtures::AGENT_ID_1,
        ],
        [
            'id' => self::ENTITY_ASSOCIATION_ID_8,
            'space' => SpaceFixtures::SPACE_ID_8,
            'withAgent' => false,
            'withEvent' => false,
            'withInitiative' => false,
            'withOpportunity' => false,
            'withOrganization' => false,
            'withSpace' => false,
            'createdBy' => AgentFixtures::AGENT_ID_1,
        ],
        [
            'id' => self::ENTITY_ASSOCIATION_ID_9,
            'space' => SpaceFixtures::SPACE_ID_9,
            'withAgent' => false,
            'withEvent' => false,
            'withInitiative' => false,
            'withOpportunity' => false,
            'withOrganization' => false,
            'withSpace' => false,
            'createdBy' => AgentFixtures::AGENT_ID_1,
        ],
        [
            'id' => self::ENTITY_ASSOCIATION_ID_10,
            'space' => SpaceFixtures::SPACE_ID_10,
            'withAgent' => false,
            'withEvent' => false,
            'withInitiative' => false,
            'withOpportunity' => false,
            'withOrganization' => false,
            'withSpace' => false,
            'createdBy' => AgentFixtures::AGENT_ID_1,
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
            SpaceFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $this->createEntitiesAssociations($manager);
        $this->manualLogout();
    }

    private function createEntitiesAssociations(ObjectManager $manager): void
    {
        foreach (self::SPACE_ENTITIES_ASSOCIATIONS as $spaceEntityAssociationData) {
            $space = $this->getReference(sprintf('%s-%s', SpaceFixtures::SPACE_ID_PREFIX, $spaceEntityAssociationData['space']), Space::class);

            /* @var EntityAssociation $spaceEntityAssociation */
            $spaceEntityAssociation = $this->serializer->denormalize($spaceEntityAssociationData, EntityAssociation::class);
            $spaceEntityAssociation->setSpace($space);

            $this->setReference(sprintf('%s-%s', self::ENTITY_ASSOCIATION_ID_PREFIX, $spaceEntityAssociationData['id']), $spaceEntityAssociation);

            $this->manualLoginByAgent($spaceEntityAssociationData['createdBy']);

            $manager->persist($spaceEntityAssociation);
        }

        $manager->flush();
    }
}
