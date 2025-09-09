<?php

declare(strict_types=1);

namespace App\DocumentService;

use App\Document\AbstractDocument;
use App\DocumentService\Interface\TimelineDocumentServiceInterface;
use App\Service\Interface\UserServiceInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Symfony\Component\Uid\Uuid;

abstract class AbstractTimelineDocumentService implements TimelineDocumentServiceInterface
{
    public const string CREATED = 'The resource was created';
    public const string DELETED = 'The resource was deleted';
    public const string UPDATED = 'The resource was updated';

    protected DocumentRepository $documentRepository;

    public function __construct(
        protected readonly DocumentManager $documentManager,
        protected readonly string $documentClass,
        protected readonly UserServiceInterface $userService
    ) {
        $this->documentRepository = $this->documentManager->getRepository(
            $this->documentClass
        );
    }

    public function getEventsByEntityId(Uuid $id): array
    {
        $events = $this->documentRepository->findBy([
            'resourceId' => $id,
        ]);

        return array_map(function (AbstractDocument $event) {
            if (null === $event->getUserId()) {
                return $event->toArray();
            }

            $user = $this->userService->get(Uuid::fromString($event->getUserId()));
            $event->assignAuthor($user);

            return $event->toArray();
        }, $events);
    }
}
