<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Agent;
use App\Entity\Event;
use App\Entity\Initiative;
use App\Entity\Opportunity;
use App\Entity\Space;
use App\Validator\Constraints\Exists;
use App\Validator\Constraints\Json;
use App\Validator\Constraints\NotNull;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;

class OpportunityDto
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

    #[Sequentially([
        new Uuid(groups: [self::CREATE, self::UPDATE]),
        new Exists(Opportunity::class, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $parent;

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
    public mixed $event;

    #[Sequentially([
        new NotBlank(groups: [self::CREATE]),
        new NotNull(groups: [self::CREATE, self::UPDATE]),
        new Uuid(groups: [self::CREATE, self::UPDATE]),
        new Exists(Agent::class, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $createdBy;

    #[Sequentially([new Json(groups: [self::CREATE, self::UPDATE])])]
    public mixed $extraFields;

    #[Image(maxSize: (2000000), mimeTypes: ['image/png', 'image/jpg', 'image/jpeg'], groups: [self::CREATE, self::UPDATE])]
    public ?File $profileImage = null;

    #[Image(maxSize: (2000000), mimeTypes: ['image/png', 'image/jpg', 'image/jpeg'], groups: [self::UPDATE])]
    public ?File $coverImage = null;
}
