<?php

declare(strict_types=1);

namespace App\Tests\Unit\Document;

use App\DataFixtures\Entity\UserFixtures;
use App\Document\NotificationDocument;
use App\Tests\AbstractApiTestCase;
use DateTime;
use DateTimeInterface;

class NotificationDocumentTest extends AbstractApiTestCase
{
    public function testGettersAndSettersFromNotificationDocumentShouldBeSuccessful(): void
    {
        $notification = new NotificationDocument();

        $id = 'test_id';
        $createdAt = new DateTime();
        $visitedAt = new DateTime();

        $notification->setId($id);
        $notification->setSender(UserFixtures::USER_ID_4);
        $notification->setTarget(UserFixtures::USER_ID_7);
        $notification->setVisited(true);
        $notification->setContext('Updated context');
        $notification->setContent('Updated content');
        $notification->setCreatedAt($createdAt);
        $notification->setVisitedAt($visitedAt);

        $this->assertEquals($id, $notification->getId());
        $this->assertEquals(UserFixtures::USER_ID_4, $notification->getSender());
        $this->assertEquals(UserFixtures::USER_ID_7, $notification->getTarget());
        $this->assertTrue($notification->isVisited());
        $this->assertEquals('Updated context', $notification->getContext());
        $this->assertEquals('Updated content', $notification->getContent());
        $this->assertEquals($createdAt, $notification->getCreatedAt());
        $this->assertEquals($visitedAt, $notification->getVisitedAt());
    }

    public function testMarkAsVisitedShouldUpdateVisitedAndVisitedAt(): void
    {
        $notification = new NotificationDocument();

        $this->assertFalse($notification->isVisited());
        $this->assertNull($notification->getVisitedAt());

        $notification->markAsVisited();

        $this->assertTrue($notification->isVisited());
        $this->assertInstanceOf(DateTime::class, $notification->getVisitedAt());
    }

    public function testToArrayShouldReturnCorrectData(): void
    {
        $createdAt = new DateTime();
        $visitedAt = new DateTime();

        $notification = new NotificationDocument();
        $notification->setId('test_id');
        $notification->setSender(UserFixtures::USER_ID_4);
        $notification->setTarget(UserFixtures::USER_ID_7);
        $notification->setVisited(false);
        $notification->setContext('Test context');
        $notification->setContent('Test content');
        $notification->setCreatedAt($createdAt);
        $notification->setVisitedAt($visitedAt);

        $expectedArray = [
            'id' => 'test_id',
            'sender' => UserFixtures::USER_ID_4,
            'target' => UserFixtures::USER_ID_7,
            'visited' => false,
            'context' => 'Test context',
            'content' => 'Test content',
            'createdAt' => $createdAt->format(DateTimeInterface::ATOM),
            'visitedAt' => $visitedAt->format(DateTimeInterface::ATOM),
        ];

        $this->assertEquals($expectedArray, $notification->toArray());
    }
}
