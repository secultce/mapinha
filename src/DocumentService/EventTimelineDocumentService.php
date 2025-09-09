<?php

declare(strict_types=1);

namespace App\DocumentService;

use App\Document\EventTimeline;
use App\DocumentService\Interface\TimelineDocumentServiceInterface;
use App\Service\Interface\UserServiceInterface;
use Doctrine\ODM\MongoDB\DocumentManager;

final class EventTimelineDocumentService extends AbstractTimelineDocumentService implements TimelineDocumentServiceInterface
{
    public function __construct(DocumentManager $documentManager, UserServiceInterface $userService)
    {
        parent::__construct($documentManager, EventTimeline::class, $userService);
    }
}
