<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security;

use App\Security\PasswordHasher;
use PHPUnit\Framework\TestCase;

class PasswordHasherTest extends TestCase
{
    public function testHashReturnsValidHash(): void
    {
        $password = 'Password@123';
        $hash = PasswordHasher::hash($password);

        $this->assertIsString($hash);
        $this->assertNotEmpty($hash);

        $this->assertTrue(password_verify($password, $hash));
    }

    public function testHashWithCustomCost(): void
    {
        $password = 'PasswordCustomCost';
        $cost = 10;
        $hash = PasswordHasher::hash($password, $cost);

        $info = password_get_info($hash);

        $this->assertSame($cost, $info['options']['cost']);
    }

    public function testVerifyReturnsTrueWithValidHash(): void
    {
        $password = 'verifyPassword';
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $this->assertTrue(PasswordHasher::verify($password, $hash));
    }

    public function testVerifyReturnsFalseWithInvalidHash(): void
    {
        $password = 'PasswordCorrect';
        $wrongPassword = 'PasswordWrong';
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $this->assertFalse(PasswordHasher::verify($wrongPassword, $hash));
    }
}
