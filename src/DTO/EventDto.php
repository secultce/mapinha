<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\ActivityArea;
use App\Entity\Agent;
use App\Entity\Event;
use App\Entity\Initiative;
use App\Entity\Space;
use App\Entity\Tag;
use App\Enum\AccessibilityInfoEnum;
use App\Enum\EventFormatEnum;
use App\Validator\Constraints\Exists;
use App\Validator\Constraints\Json;
use App\Validator\Constraints\NotNull;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;

class EventDto
{
    public const string CREATE = 'create';

    public const string UPDATE = 'update';

    #[Sequentially([new NotBlank(), new Uuid()], groups: [self::CREATE])]
    public mixed $id;

    #[Sequentially([
        new NotBlank(groups: [self::CREATE]),
        new NotNull(groups: [self::UPDATE]),
        new Type('string', groups: [self::CREATE, self::UPDATE]),
        new Length(min: 2, max: 100, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $name;

    #[Image(maxSize: (2000000), mimeTypes: ['image/png', 'image/jpg', 'image/jpeg'], groups: [self::CREATE, self::UPDATE])]
    public ?File $image = null;

    #[Sequentially([
        new Uuid(groups: [self::CREATE, self::UPDATE]),
        new Exists(Agent::class, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $agentGroup;

    #[Sequentially([
        new Uuid(groups: [self::CREATE, self::UPDATE]),
        new Exists(Space::class, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $space;

    #[Sequentially([
        new Uuid(groups: [self::CREATE, self::UPDATE]),
        new Exists(Initiative::class, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $initiative;

    #[Sequentially([
        new Uuid(groups: [self::CREATE, self::UPDATE]),
        new Exists(Event::class, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $parent;

    #[Sequentially(new Json(groups: [self::CREATE, self::UPDATE]))]
    public mixed $extraFields;

    #[Sequentially([
        new NotNull(groups: [self::UPDATE]),
        new Uuid(groups: [self::CREATE, self::UPDATE]),
        new Exists(Agent::class, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $createdBy;

    #[Image(
        maxSize: (2000000),
        mimeTypes: ['image/png', 'image/jpg', 'image/jpeg'],
        groups: [self::CREATE, self::UPDATE]
    )]
    public mixed $coverImage;

    #[Sequentially([
        new Type('string', groups: [self::CREATE, self::UPDATE]),
        new Length(min: 2, max: 255, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $subtitle;

    #[Sequentially([
        new Type('string', groups: [self::CREATE, self::UPDATE]),
        new Length(min: 2, max: 255, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $shortDescription;

    #[Sequentially([
        new Type('string', groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $longDescription;

    #[Sequentially([
        new NotBlank(groups: [self::CREATE]),
        new NotNull(groups: [self::UPDATE]),
        new Type('integer', groups: [self::CREATE, self::UPDATE]),
        new Choice(callback: [EventFormatEnum::class, 'getValues'], groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $format;

    #[Sequentially([
        new NotBlank(groups: [self::CREATE]),
        new NotNull(groups: [self::UPDATE]),
        new Type('string', groups: [self::CREATE, self::UPDATE]),
        new DateTime(format: 'Y-m-d', groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $startDate;

    #[Sequentially([
        new Type('string', groups: [self::CREATE, self::UPDATE]),
        new DateTime(format: 'Y-m-d', groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $endDate;

    #[Sequentially([
        new All([new Uuid()], [self::CREATE, self::UPDATE]),
        new Exists(ActivityArea::class),
    ], groups: [self::CREATE, self::UPDATE])]
    public mixed $activityAreas;

    #[Sequentially([
        new All([new Uuid()], [self::CREATE, self::UPDATE]),
        new Exists(Tag::class),
    ], groups: [self::CREATE, self::UPDATE])]
    public mixed $tags;

    #[Sequentially([
        new Type('string', groups: [self::CREATE, self::UPDATE]),
        new Length(min: 2, max: 255, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $site;

    #[Sequentially([new Json(groups: [self::CREATE, self::UPDATE])])]
    public mixed $socialNetworks;

    #[Sequentially([
        new Type('string', groups: [self::CREATE, self::UPDATE]),
        new Length(min: 2, max: 20, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $phoneNumber;

    #[Sequentially([
        new Type('integer', groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $maxCapacity;

    #[Sequentially([
        new Type('string', groups: [self::CREATE, self::UPDATE]),
        new Choice(callback: [AccessibilityInfoEnum::class, 'getNames'], groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $accessibleAudio;

    #[Sequentially([
        new Type('string', groups: [self::CREATE, self::UPDATE]),
        new Choice(callback: [AccessibilityInfoEnum::class, 'getNames'], groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $accessibleLibras;

    #[Type('boolean', groups: [self::CREATE, self::UPDATE])]
    public mixed $free;

    #[Sequentially([
        new NotNull(groups: [self::UPDATE]),
        new Type(Types::BOOLEAN, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $draft = true;
}
