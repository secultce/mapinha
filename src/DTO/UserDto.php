<?php

declare(strict_types=1);

namespace App\DTO;

use App\Validator\Constraints\NotNull;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PasswordStrength;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;

class UserDto
{
    public const string CREATE = 'create';

    public const string UPDATE = 'update';

    #[Sequentially([new NotBlank(), new Uuid()], groups: [self::CREATE])]
    public mixed $id;

    #[Sequentially([
        new NotBlank(groups: [self::CREATE]),
        new NotNull(groups: [self::UPDATE]),
        new Type('string', groups: [self::CREATE, self::UPDATE]),
        new Length(min: 2, max: 50, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $firstname;

    #[Sequentially([
        new NotBlank(groups: [self::CREATE]),
        new NotNull(groups: [self::UPDATE]),
        new Type('string', groups: [self::CREATE, self::UPDATE]),
        new Length(min: 2, max: 50, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $lastname;

    #[Sequentially([
        new Type('string', groups: [self::CREATE, self::UPDATE]),
        new Length(max: 100, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $socialName = null;

    #[Sequentially([
        new NotBlank(groups: [self::CREATE]),
        new NotNull(groups: [self::UPDATE]),
        new Email(groups: [self::CREATE, self::UPDATE]),
        new Type('string', groups: [self::CREATE, self::UPDATE]),
        new Length(max: 100, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $email;

    #[Sequentially([
        new NotBlank(groups: [self::CREATE]),
        new NotNull(groups: [self::UPDATE]),
        new PasswordStrength(
            minScore: PasswordStrength::STRENGTH_WEAK,
            message: 'password.too_weak',
            groups: [self::CREATE, self::UPDATE]
        ),
        new Type('string', groups: [self::CREATE, self::UPDATE]),
        new Length(max: 255, groups: [self::CREATE, self::UPDATE]),
    ])]
    public mixed $password;

    #[Image(maxSize: (2000000), mimeTypes: ['image/png', 'image/jpg', 'image/jpeg'], groups: [self::CREATE, self::UPDATE])]
    public ?File $profileImage = null;

    #[Image(maxSize: (2000000), mimeTypes: ['image/png', 'image/jpg', 'image/jpeg'], groups: [self::UPDATE])]
    public ?File $coverImage = null;
}
