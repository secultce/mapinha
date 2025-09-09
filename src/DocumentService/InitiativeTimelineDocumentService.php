<?php

declare(strict_types=1);

namespace App\DocumentService;

use App\Document\InitiativeTimeline;
use App\DocumentService\Interface\TimelineDocumentServiceInterface;
use App\Service\Interface\UserServiceInterface;
use Doctrine\ODM\MongoDB\DocumentManager;

final class InitiativeTimelineDocumentService extends AbstractTimelineDocumentService implements TimelineDocumentServiceInterface
{
    public function __construct(DocumentManager $documentManager, UserServiceInterface $userService)
    {
        parent::__construct($documentManager, InitiativeTimeline::class, $userService);
    }
}
