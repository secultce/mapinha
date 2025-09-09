<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\DataFixtures\Entity\ActivityAreaFixtures;
use App\DataFixtures\Entity\AgentFixtures;
use App\DataFixtures\Entity\EventFixtures;
use App\DataFixtures\Entity\EventTypeFixtures;
use App\DataFixtures\Entity\InitiativeFixtures;
use App\DataFixtures\Entity\SpaceFixtures;
use App\DataFixtures\Entity\TagFixtures;
use App\Enum\EventFormatEnum;
use Symfony\Component\Uid\Uuid;

class EventTestFixtures implements TestFixtures
{
    public static function partial(): array
    {
        return [
            'id' => Uuid::v4()->toRfc4122(),
            'name' => 'Test Event',
            'agentGroup' => AgentFixtures::AGENT_ID_1,
            'space' => SpaceFixtures::SPACE_ID_1,
            'initiative' => InitiativeFixtures::INITIATIVE_ID_1,
            'type' => EventFormatEnum::HYBRID->value,
            'startDate' => '2025-01-16',
            'endDate' => '2025-04-01',
            'maxCapacity' => 5000,
        ];
    }

    public static function complete(): array
    {
        return array_merge(self::partial(), [
            'parent' => EventFixtures::EVENT_ID_1,
            'extraFields' => [
                'ageRating' => 'Free',
            ],
            'coverImage' => 'coverimage.jpg',
            'subtitle' => 'Subtítulo de exemplo',
            'shortDescription' => 'Descrição curta',
            'longDescription' => 'Uma descrição mais longa',
            'eventType' => EventTypeFixtures::EVENT_TYPE_ID_1,
            'activityAreas' => [
                ActivityAreaFixtures::ACTIVITY_AREA_ID_1,
                ActivityAreaFixtures::ACTIVITY_AREA_ID_9,
                ActivityAreaFixtures::ACTIVITY_AREA_ID_2,
            ],
            'tags' => [
                TagFixtures::TAG_ID_3,
                TagFixtures::TAG_ID_4,
            ],
            'site' => 'evento.com.br',
            'phoneNumber' => '8585998585',
            'accessibleAudio' => 'not_informed',
            'accessibleLibras' => 'not_informed',
            'free' => true,
        ]);
    }
}
