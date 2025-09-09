<?php

declare(strict_types=1);

namespace App\DocumentService;

use App\Document\AbstractDocument;
use App\Document\InitiativeTimeline;
use App\Document\InviteTimeline;
use App\Document\OrganizationTimeline;
use App\DocumentService\Interface\TimelineDocumentServiceInterface;
use App\Service\Interface\UserServiceInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

class OrganizationTimelineDocumentService extends AbstractTimelineDocumentService implements TimelineDocumentServiceInterface
{
    public function __construct(DocumentManager $documentManager, UserServiceInterface $userService, private EntityManagerInterface $entityManager)
    {
        parent::__construct($documentManager, OrganizationTimeline::class, $userService);
    }

    public function getAllEventsByOrganizationId(Uuid $id): array
    {
        $events = $this->getEventsByEntityId($id);

        $inviteEvents = $this->documentManager->getRepository(InviteTimeline::class)->findBy(['organizationId' => $id]);

        $initiativeEvents = $this->findInitiativeEvents($id->toRfc4122());

        return array_merge(array_map(function (AbstractDocument $event) {
            if (null === $event->getUserId()) {
                return $event->toArray();
            }

            $user = $this->userService->get(Uuid::fromString($event->getUserId()));
            $event->assignAuthor($user);

            return $event->toArray();
        }, [...$inviteEvents, ...$initiativeEvents]), $events);
    }

    private function findInitiativeEvents(string $organizationId): array
    {
        $query = $this->entityManager->createQuery(
            'SELECT i.id FROM App\Entity\Initiative i 
         WHERE i.organizationTo = :organizationId'
        )->setParameter('organizationId', $organizationId);

        $initiativeIds = array_column($query->getScalarResult(), 'id');

        return $this->documentManager->getRepository(InitiativeTimeline::class)
            ->findBy(['resourceId' => ['$in' => $initiativeIds]]);
    }
}
