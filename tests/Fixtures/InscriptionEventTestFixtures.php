<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\DataFixtures\Entity\AgentFixtures;
use App\DataFixtures\Entity\EventFixtures;
use Symfony\Component\Uid\Uuid;

final class InscriptionEventTestFixtures implements TestFixtures
{
    public static function partial(): array
    {
        return [
            'id' => Uuid::v4()->toRfc4122(),
            'agent' => AgentFixtures::AGENT_ID_3,
            'event' => EventFixtures::EVENT_ID_4,
            'status' => 'active',
        ];
    }

    public static function complete(): array
    {
        return self::partial();
    }
}
