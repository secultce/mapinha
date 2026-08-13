<?php

declare(strict_types=1);

namespace App\DTO;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;

class PhotoDto
{
    public const string CREATE = 'create';

    #[Sequentially([new NotBlank(), new Uuid()], groups: [self::CREATE])]
    public mixed $id;

    #[Image(
        maxSize: (2000000),
        mimeTypes: ['image/png', 'image/jpg', 'image/jpeg'],
        groups: [self::CREATE]
    )]
    public ?File $image = null;

    #[Type(Types::STRING, groups: [self::CREATE])]
    public mixed $description = null;
}
