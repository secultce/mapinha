<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Enum\UserStatusEnum;
use Symfony\Component\Uid\Uuid;

class UserTestFixtures implements TestFixtures
{
    public static function partial(): array
    {
        return [
            'id' => Uuid::v4()->toRfc4122(),
            'firstname' => 'User',
            'lastname' => 'Test',
            'socialName' => null,
            'email' => 'user.test@mail.com',
            'password' => 'Aurora@2024',
            'image' => null,
            'status' => UserStatusEnum::ACTIVE->value,
        ];
    }

    public static function complete(): array
    {
        return array_merge(self::partial(), [
            'socialName' => 'User Test',
        ]);
    }
}
