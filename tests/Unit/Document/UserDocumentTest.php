<?php

declare(strict_types=1);

namespace App\Tests\Unit\Document;

use App\Document\UserTimeline;
use App\Tests\AbstractApiTestCase;
use DateTime;

class UserDocumentTest extends AbstractApiTestCase
{
    public function testGettersAndSettersFromUserDocumentShouldBeSuccessful(): void
    {
        $user = new UserTimeline();

        $datetime = new DateTime();
        $id = 'b4169426-9c01-4c92-ba36-4a276aedc469';
        $userId = 'db91c782-3476-4446-8ded-5d0f9cf110ba';
        $resourceId = '84a4a7d0-9a70-4554-bd62-c54fa570c6ea';
        $title = 'the entity was updated';
        $priority = 1;
        $device = 'smartphone';
        $platform = 'android';
        $from = ['name' => 'Agent X'];
        $to = ['name' => 'Agent Y'];

        $user->setId($id);
        $user->setUserId($userId);
        $user->setResourceId($resourceId);
        $user->setTitle($title);
        $user->setPriority($priority);
        $user->setDatetime($datetime);
        $user->setDevice($device);
        $user->setPlatform($platform);
        $user->setFrom($from);
        $user->setTo($to);

        $this->assertTrue($id === $user->getId());
        $this->assertTrue($userId === $user->getUserId());
        $this->assertTrue($resourceId === $user->getResourceId());
        $this->assertTrue($title === $user->getTitle());
        $this->assertTrue($priority === $user->getPriority());
        $this->assertTrue($datetime === $user->getDatetime());
        $this->assertTrue($device === $user->getDevice());
        $this->assertTrue($platform === $user->getPlatform());

        $this->assertTrue($from === $user->getFrom());
        $this->assertArrayHasKey('name', $user->getFrom());

        $this->assertTrue($to === $user->getTo());
        $this->assertArrayHasKey('name', $user->getTo());
    }
}
