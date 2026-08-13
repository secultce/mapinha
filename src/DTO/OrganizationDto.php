<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Agent;
use App\Validator\Constraints\Exists;
use App\Validator\Constraints\Json;
use App\Validator\Constraints\NotNull;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;

class OrganizationDto
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

    #[Sequentially([new Type('string'), new Length(max: 255)], groups: [self::CREATE, self::UPDATE])]
    public mixed $description;

    #[Sequentially([
        new NotNull(groups: [self::UPDATE]),
        new Uuid(groups: [self::CREATE, self::UPDATE]),
        new Exists(Agent::class, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $createdBy;

    #[Sequentially([
        new NotNull(),
        new Uuid(),
    ])]
    public mixed $activityAreas;

    #[Sequentially([
        new NotNull(groups: [self::UPDATE]),
        new Uuid(groups: [self::CREATE, self::UPDATE]),
        new Exists(Agent::class, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $owner;

    #[Sequentially([
        new All([new Uuid()], [self::CREATE, self::UPDATE]),
        new Exists(Agent::class),
    ], groups: [self::CREATE, self::UPDATE])]
    public mixed $agents;

    #[Sequentially([new Json(groups: [self::CREATE, self::UPDATE])])]
    public mixed $extraFields;

    #[Image(maxSize: (2000000), mimeTypes: ['image/png', 'image/jpg', 'image/jpeg'], groups: [self::CREATE, self::UPDATE])]
    public ?File $image = null;

    #[Image(maxSize: (2000000), mimeTypes: ['image/png', 'image/jpg', 'image/jpeg'], groups: [self::UPDATE])]
    public ?File $coverImage = null;
}
