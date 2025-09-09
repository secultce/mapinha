<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use Symfony\Component\Uid\Uuid;

class CulturalLanguageTestFixtures implements TestFixtures
{
    public static function partial(): array
    {
        return [
            'id' => Uuid::v4()->toRfc4122(),
            'name' => 'Test cultural language',
        ];
    }

    public static function complete(): array
    {
        return array_merge(self::partial(), [
            'description' => 'Test cultural language description',
        ]);
    }
}
