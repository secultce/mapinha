<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Organization;
use App\Entity\User;
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

class AgentDto
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
        new Length(min: 11, max: 30, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $fiscalCode;

    #[Image(maxSize: (2000000), mimeTypes: ['image/png', 'image/jpg', 'image/jpeg'], groups: [self::CREATE, self::UPDATE])]
    public ?File $image = null;

    #[Image(maxSize: (2000000), mimeTypes: ['image/png', 'image/jpg', 'image/jpeg'], groups: [self::UPDATE])]
    public ?File $coverImage = null;

    #[Sequentially([
        new NotNull(groups: [self::UPDATE]),
        new Type('string', groups: [self::CREATE, self::UPDATE]),
        new Length(max: 100, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $shortBio;

    #[Sequentially([
        new NotNull(groups: [self::UPDATE]),
        new Type('string', groups: [self::CREATE, self::UPDATE]),
        new Length(max: 255, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $longBio;

    #[Sequentially([
        new NotNull(groups: [self::UPDATE]),
        new Type('bool', groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $culture;

    #[Sequentially([
        new NotBlank(groups: [self::CREATE]),
        new NotNull(groups: [self::UPDATE]),
        new Uuid(groups: [self::CREATE, self::UPDATE]),
        new Exists(User::class, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $user;

    #[Sequentially([
        new All([new Uuid()], [self::CREATE, self::UPDATE]),
        new Exists(Organization::class),
    ], groups: [self::CREATE, self::UPDATE])]
    public mixed $organizations;

    #[Sequentially([new Json()], groups: [self::CREATE, self::UPDATE])]
    public mixed $extraFields;
}
