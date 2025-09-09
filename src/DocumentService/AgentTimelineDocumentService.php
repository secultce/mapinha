<?php

declare(strict_types=1);

namespace App\DocumentService;

use App\Document\AgentTimeline;
use App\DocumentService\Interface\TimelineDocumentServiceInterface;
use App\Service\Interface\UserServiceInterface;
use Doctrine\ODM\MongoDB\DocumentManager;

final class AgentTimelineDocumentService extends AbstractTimelineDocumentService implements TimelineDocumentServiceInterface
{
    public function __construct(DocumentManager $documentManager, UserServiceInterface $userService)
    {
        parent::__construct($documentManager, AgentTimeline::class, $userService);
    }
}
