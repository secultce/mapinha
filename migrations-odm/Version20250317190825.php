<?php

declare(strict_types=1);

namespace DoctrineMigrationsOdm;

use App\Document\InscriptionEventTimeline;
use DateTime;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\Uid\Uuid;

final class Version20250317190825
{
    public function up(DocumentManager $dm): void
    {
        $documentTimeline = new InscriptionEventTimeline();
        $documentTimeline->setUserId(Uuid::v4()->toRfc4122());
        $documentTimeline->setResourceId(Uuid::v4()->toRfc4122());
        $documentTimeline->setTitle('');
        $documentTimeline->setPriority(1);
        $documentTimeline->setDatetime(new DateTime());
        $documentTimeline->setDevice('linux');
        $documentTimeline->setPlatform('api');
        $documentTimeline->setFrom([]);
        $documentTimeline->setTo([]);

        $dm->persist($documentTimeline);
        $dm->flush();
    }

    public function down(DocumentManager $dm): void
    {
        $dm->getDocumentCollection(InscriptionEventTimeline::class)->drop();
    }
}
