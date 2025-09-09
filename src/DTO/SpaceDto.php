<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Agent;
use App\Entity\Space;
use App\Validator\Constraints\Exists;
use App\Validator\Constraints\Json;
use App\Validator\Constraints\NotNull;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;

class SpaceDto
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

    #[Image(
        maxSize: (2000000),
        mimeTypes: ['image/png', 'image/jpg', 'image/jpeg'],
        groups: [self::CREATE, self::UPDATE]
    )]
    public ?File $image = null;

    #[Sequentially([
        new NotBlank(groups: [self::CREATE]),
        new Uuid(groups: [self::CREATE, self::UPDATE]),
        new Exists(Agent::class, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $createdBy;

    #[Sequentially([
        new Uuid(groups: [self::CREATE, self::UPDATE]),
        new Exists(Space::class, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $parent;

    #[Sequentially([new Json(groups: [self::CREATE, self::UPDATE])])]
    public mixed $extraFields;

    #[Image(
        maxSize: (2000000),
        mimeTypes: ['image/png', 'image/jpg', 'image/jpeg'],
        groups: [self::CREATE, self::UPDATE]
    )]
    public ?File $coverImage = null;

    #[Sequentially([
        new Type('string', groups: [self::CREATE, self::UPDATE]),
        new Length(max: 255, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $shortDescription = null;

    #[Sequentially([
        new Type('string', groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $longDescription = null;

    #[Sequentially([
        new Type('string', groups: [self::CREATE, self::UPDATE]),
        new Length(max: 255, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $site = null;

    #[Sequentially([
        new Type('string', groups: [self::CREATE, self::UPDATE]),
        new Length(max: 255, groups: [self::CREATE, self::UPDATE]),
        new Email(groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $email = null;

    #[Sequentially([
        new Type('string', groups: [self::CREATE, self::UPDATE]),
        new Length(max: 20, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $phoneNumber = null;

    #[Sequentially([
        new Type('integer', groups: [self::CREATE, self::UPDATE]),
        new Range(min: 1, groups: [self::UPDATE]),
    ])]
    public mixed $maxCapacity;

    #[Sequentially([
        new NotNull(groups: [self::UPDATE]),
        new Type(Types::BOOLEAN, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $isAccessible;

    #[Sequentially([
        new NotNull(groups: [self::UPDATE]),
        new Type(Types::BOOLEAN, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $isDraft = true;
}
