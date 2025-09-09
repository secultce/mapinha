<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\ActivityArea;
use App\Entity\Agent;
use App\Entity\CulturalLanguage;
use App\Entity\Event;
use App\Entity\Initiative;
use App\Entity\Space;
use App\Entity\Tag;
use App\Enum\AccessibilityInfoEnum;
use App\Enum\EventFormatEnum;
use App\Helper\DateFormatHelper;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class EventEntityTest extends TestCase
{
    public function testGetters(): void
    {
        $event = new Event();

        $id = Uuid::v4();
        $name = 'Festival de Música';
        $image = 'image.jpg';
        $agentGroup = new Agent();
        $space = new Space();
        $initiative = new Initiative();
        $parentEvent = new Event();
        $extraFields = ['key' => 'value'];
        $createdBy = new Agent();
        $coverImage = 'coverimage.jpg';
        $subtitle = 'subtitle';
        $shortDescription = 'shortdescription';
        $longDescription = 'longdescription';
        $format = EventFormatEnum::HYBRID->value;
        $startDate = new DateTime();
        $endDate = new DateTime();
        $activityAreas = new ArrayCollection([
            new ActivityArea(),
            new ActivityArea(),
        ]);
        $tags = new ArrayCollection([
            new Tag(),
            new Tag(),
        ]);
        $site = 'evento.com';
        $phoneNumber = '0123456789';
        $maxCapacity = 100;
        $accessibleAudio = AccessibilityInfoEnum::YES->value;
        $accessibleLibras = AccessibilityInfoEnum::YES->value;
        $isFree = true;
        $draft = false;
        $culturalLanguages = new ArrayCollection([
            new CulturalLanguage(),
            new CulturalLanguage(),
        ]);
        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTime();
        $deletedAt = new DateTime();

        $event->setId($id);
        $event->setName($name);
        $event->setImage($image);
        $event->setAgentGroup($agentGroup);
        $event->setSpace($space);
        $event->setInitiative($initiative);
        $event->setParent($parentEvent);
        $event->setExtraFields($extraFields);
        $event->setCreatedBy($createdBy);
        $event->setCoverImage($coverImage);
        $event->setSubtitle($subtitle);
        $event->setShortDescription($shortDescription);
        $event->setLongDescription($longDescription);
        $event->setFormat($format);
        $event->setStartDate($startDate);
        $event->setEndDate($endDate);
        $event->setActivityAreas($activityAreas);
        $event->setTags($tags);
        $event->setSite($site);
        $event->setPhoneNumber($phoneNumber);
        $event->setMaxCapacity($maxCapacity);
        $event->setAccessibleAudio($accessibleAudio);
        $event->setAccessibleLibras($accessibleLibras);
        $event->setFree($isFree);
        $event->setDraft($draft);
        $event->setCulturalLanguages($culturalLanguages);
        $event->setCreatedAt($createdAt);
        $event->setUpdatedAt($updatedAt);
        $event->setDeletedAt($deletedAt);

        $this->assertSame($id, $event->getId());
        $this->assertSame($name, $event->getName());
        $this->assertSame($image, $event->getImage());
        $this->assertSame($agentGroup, $event->getAgentGroup());
        $this->assertSame($space, $event->getSpace());
        $this->assertSame($initiative, $event->getInitiative());
        $this->assertSame($parentEvent, $event->getParent());
        $this->assertSame($extraFields, $event->getExtraFields());
        $this->assertSame($createdBy, $event->getCreatedBy());
        $this->assertSame($coverImage, $event->getCoverImage());
        $this->assertSame($subtitle, $event->getSubtitle());
        $this->assertSame($shortDescription, $event->getShortDescription());
        $this->assertSame($longDescription, $event->getLongDescription());
        $this->assertSame($format, $event->getFormat());
        $this->assertSame($startDate, $event->getStartDate());
        $this->assertSame($endDate, $event->getEndDate());
        $this->assertSame($activityAreas, $event->getActivityAreas());
        $this->assertSame($tags, $event->getTags());
        $this->assertSame($site, $event->getSite());
        $this->assertSame($phoneNumber, $event->getPhoneNumber());
        $this->assertSame($maxCapacity, $event->getMaxCapacity());
        $this->assertSame($accessibleAudio, $event->getAccessibleAudio());
        $this->assertSame($accessibleLibras, $event->getAccessibleLibras());
        $this->assertSame($isFree, $event->isFree());
        $this->assertSame($draft, $event->isDraft());
        $this->assertSame($culturalLanguages, $event->getCulturalLanguages());
        $this->assertSame($createdAt, $event->getCreatedAt());
        $this->assertSame($updatedAt, $event->getUpdatedAt());
        $this->assertSame($deletedAt, $event->getDeletedAt());
    }

    public function testToArray(): void
    {
        $event = new Event();

        $id = Uuid::v4();
        $name = 'Festival de Música';
        $image = 'image.jpg';

        $agent = new Agent();
        $agent->setId(Uuid::v4());
        $agent->setName('Agent test');
        $agent->setShortBio('Short Bio');
        $agent->setLongBio('Long Bio');
        $agent->setCulture(true);

        $space = new Space();
        $space->setId(Uuid::v4());
        $space->setName('Space test');
        $space->setMaxCapacity(100);
        $space->setIsAccessible(true);
        $space->setCreatedBy($agent);

        $initiative = new Initiative();
        $initiative->setId(Uuid::v4());
        $initiative->setName('Initiative test');
        $initiative->setCreatedBy($agent);

        $parentEvent = new Event();
        $parentEvent->setId(Uuid::v4());
        $parentEvent->setName('Parent Event test');
        $parentEvent->setCreatedBy($agent);
        $parentEvent->setEndDate(new DateTime());
        $parentEvent->setMaxCapacity(500);

        $extraFields = ['key' => 'value'];
        $createdBy = $agent;
        $coverImage = 'coverimage.jpg';
        $subtitle = 'subtitle';
        $shortDescription = 'shortdescription';
        $longDescription = 'longdescription';
        $format = EventFormatEnum::HYBRID->value;
        $startDate = new DateTime();
        $endDate = new DateTime();

        $activityAreaUuid1 = Uuid::v4();
        $activityArea1 = new ActivityArea();
        $activityArea1->setId($activityAreaUuid1);
        $activityAreaUuid2 = Uuid::v4();
        $activityArea2 = new ActivityArea();
        $activityArea2->setId($activityAreaUuid2);

        $tagUuid1 = Uuid::v4();
        $tag1 = new Tag();
        $tag1->setId($tagUuid1);
        $tag1->setName('tag1');
        $tagUuid2 = Uuid::v4();
        $tag2 = new Tag();
        $tag2->setId($tagUuid2);
        $tag2->setName('tag2');

        $site = 'evento.com';
        $phoneNumber = '0123456789';
        $maxCapacity = 100;
        $accessibleAudio = AccessibilityInfoEnum::YES->value;
        $accessibleLibras = AccessibilityInfoEnum::YES->value;
        $free = true;
        $draft = true;

        $culturalLanguageUuid1 = Uuid::v4();
        $culturalLanguage1 = new CulturalLanguage();
        $culturalLanguage1->setId($culturalLanguageUuid1);
        $culturalLanguage1->setName('Cultural Language 1');
        $culturalLanguageUuid2 = Uuid::v4();
        $culturalLanguage2 = new CulturalLanguage();
        $culturalLanguage2->setId($culturalLanguageUuid2);

        $createdAt = new DateTimeImmutable();
        $updatedAt = new DateTime();
        $deletedAt = new DateTime();

        $event->setId($id);
        $event->setName($name);
        $event->setImage($image);
        $event->setAgentGroup($agent);
        $event->setSpace($space);
        $event->setInitiative($initiative);
        $event->setParent($parentEvent);
        $event->setExtraFields($extraFields);
        $event->setCreatedBy($agent);
        $event->setCoverImage($coverImage);
        $event->setSubtitle($subtitle);
        $event->setShortDescription($shortDescription);
        $event->setLongDescription($longDescription);
        $event->setFormat($format);
        $event->setStartDate($startDate);
        $event->setEndDate($endDate);
        $event->addActivityArea($activityArea1);
        $event->addActivityArea($activityArea2);
        $event->removeActivityArea($activityArea2);
        $event->addTag($tag1);
        $event->addTag($tag2);
        $event->removeTag($tag2);
        $event->setSite($site);
        $event->setPhoneNumber($phoneNumber);
        $event->setMaxCapacity($maxCapacity);
        $event->setAccessibleAudio($accessibleAudio);
        $event->setAccessibleLibras($accessibleLibras);
        $event->setFree($free);
        $event->addCulturalLanguage($culturalLanguage1);
        $event->addCulturalLanguage($culturalLanguage2);
        $event->removeCulturalLanguage($culturalLanguage2);
        $event->setCreatedAt($createdAt);
        $event->setUpdatedAt($updatedAt);
        $event->setDeletedAt($deletedAt);

        $actualArray = $event->toArray();
        $expectedArray = [
            'id' => $id->toRfc4122(),
            'name' => $name,
            'agentGroup' => $agent->toArray(),
            'space' => $space->toArray(),
            'initiative' => $initiative->toArray(),
            'parent' => $parentEvent->toArray(),
            'createdBy' => $createdBy->toArray(),
            'coverImage' => $coverImage,
            'subtitle' => $subtitle,
            'shortDescription' => $shortDescription,
            'longDescription' => $longDescription,
            'format' => $format,
            'startDate' => $startDate->format(DateFormatHelper::DEFAULT_FORMAT),
            'endDate' => $endDate->format(DateFormatHelper::DEFAULT_FORMAT),
            'activityAreas' => [
                $activityArea1->toArray(),
            ],
            'tags' => [
                $tag1->toArray(),
            ],
            'site' => $site,
            'phoneNumber' => $phoneNumber,
            'maxCapacity' => $maxCapacity,
            'accessibleAudio' => $accessibleAudio,
            'accessibleLibras' => $accessibleLibras,
            'free' => $free,
            'draft' => $draft,
            'culturalLanguages' => [
                $culturalLanguage1->toArray(),
            ],
            'socialNetworks' => [],
            'createdAt' => $createdAt->format(DateFormatHelper::DEFAULT_FORMAT),
            'updatedAt' => $updatedAt?->format(DateFormatHelper::DEFAULT_FORMAT),
            'deletedAt' => $deletedAt?->format(DateFormatHelper::DEFAULT_FORMAT),
            'eventType' => null,
        ];

        $this->assertEquals($expectedArray, $actualArray);
    }
}
