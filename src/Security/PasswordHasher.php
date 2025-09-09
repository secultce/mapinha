<?php

declare(strict_types=1);

namespace App\Security;

readonly class PasswordHasher
{
    public static function hash(string $password, int $cost = 13): string
    {
        return password_hash($password, PASSWORD_DEFAULT, [
            'cost' => $cost,
        ]);
    }

    public static function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
