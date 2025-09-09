<?php

declare(strict_types=1);

namespace App\Tests\Unit\Document;

use App\Document\OpportunityTimeline;
use App\Tests\AbstractApiTestCase;
use DateTime;

class OpportunityDocumentTest extends AbstractApiTestCase
{
    public function testsGettersAndSettersFromOpportunityShouldBeSuccessful(): void
    {
        $opportunity = new OpportunityTimeline();

        $this->assertNull($opportunity->getId());

        $id = 'fbeb1195-7040-4600-877b-492d40de7352';
        $userId = 'fbeb1195-7040-4600-877b-492d40de7310';
        $resourceId = 'abcb1195-7040-4600-877b-492d40de7352';
        $title = 'My Opportunity';
        $priority = 10;
        $datetime = new DateTime();
        $device = 'Smartphone';
        $platform = 'Android';
        $from = ['string1', 1];
        $to = ['to', 55];

        $opportunity->setId($id);
        $opportunity->setUserId($userId);
        $opportunity->setResourceId($resourceId);
        $opportunity->setTitle($title);
        $opportunity->setPriority($priority);
        $opportunity->setDatetime($datetime);
        $opportunity->setDevice($device);
        $opportunity->setPlatform($platform);
        $opportunity->setFrom($from);
        $opportunity->setTo($to);

        $this->assertEquals($id, $opportunity->getId());
        $this->assertIsString($opportunity->getId());

        $this->assertEquals($userId, $opportunity->getUserId());
        $this->assertIsString($opportunity->getUserId());

        $this->assertEquals($resourceId, $opportunity->getResourceId());
        $this->assertIsString($opportunity->getResourceId());

        $this->assertEquals($title, $opportunity->getTitle());
        $this->assertIsString($opportunity->getTitle());

        $this->assertEquals($priority, $opportunity->getPriority());
        $this->assertIsInt($opportunity->getPriority());

        $this->assertEquals($datetime, $opportunity->getDatetime());
        $this->assertInstanceOf(DateTime::class, $opportunity->getDatetime());

        $this->assertEquals($device, $opportunity->getDevice());
        $this->assertIsString($opportunity->getDevice());

        $this->assertEquals($platform, $opportunity->getPlatform());
        $this->assertIsString($opportunity->getPlatform());

        $this->assertEquals($from, $opportunity->getFrom());
        $this->assertIsArray($opportunity->getFrom());

        $this->assertEquals($to, $opportunity->getTo());
        $this->assertIsArray($opportunity->getTo());
    }
}
