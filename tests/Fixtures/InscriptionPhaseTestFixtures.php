<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\DataFixtures\Entity\AgentFixtures;
use App\DataFixtures\Entity\PhaseFixtures;
use Symfony\Component\Uid\Uuid;

class InscriptionPhaseTestFixtures implements TestFixtures
{
    public static function partial(): array
    {
        return [
            'id' => Uuid::v4()->toRfc4122(),
            'agent' => AgentFixtures::AGENT_ID_2,
            'phase' => PhaseFixtures::PHASE_ID_10,
            'status' => 'active',
            'extraFields' => [
                'keyTest' => 'valueTest',
            ],
        ];
    }

    public static function complete(): array
    {
        return self::partial();
    }
}
