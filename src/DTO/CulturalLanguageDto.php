<?php

declare(strict_types=1);

namespace App\DTO;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;

final class CulturalLanguageDto
{
    public const string CREATE = 'create';

    public const string UPDATE = 'update';

    #[Sequentially([new NotBlank(), new Uuid()], groups: [self::CREATE])]
    public mixed $id;

    #[Sequentially([
        new NotBlank(),
        new NotNull(),
        new Type(Types::STRING),
        new Length(min: 2, max: 50),
    ], groups: [self::CREATE, self::UPDATE])]
    public string $name;

    #[Sequentially([
        new Type(Types::STRING),
        new Length(min: 2, max: 255),
    ], groups: [self::CREATE, self::UPDATE])]
    public string $description;
}
