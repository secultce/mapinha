<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\DataFixtures\Entity\ActivityAreaFixtures;
use App\DataFixtures\Entity\AgentFixtures;
use App\DataFixtures\Entity\ArchitecturalAccessibilityFixtures;
use App\DataFixtures\Entity\SpaceFixtures;
use App\DataFixtures\Entity\SpaceTypeFixtures;
use Symfony\Component\Uid\Uuid;

class SpaceTestFixtures implements TestFixtures
{
    public static function partial(): array
    {
        return [
            'id' => Uuid::v4()->toRfc4122(),
            'name' => 'Test Space',
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'parent' => SpaceFixtures::SPACE_ID_1,
            'maxCapacity' => 100,
            'isAccessible' => true,
            'isDraft' => false,
        ];
    }

    public static function complete(): array
    {
        return array_merge(self::partial(), [
            'shortDescription' => 'Test short description',
            'longDescription' => 'Test long description',
            'image' => null,
            'coverImage' => null,
            'site' => 'https://test-site.com',
            'email' => 'test@email.com',
            'phoneNumber' => '+55 85 99999-9999',
            'extraFields' => [
                'type' => 'Cultural',
                'description' => 'É um espaço cultural que reúne artesãos de todo o Brasil para celebrar a cultura nordestina.',
                'location' => 'Recife, Pernambuco',
                'capacity' => 100,
            ],
            'activityAreas' => [
                ActivityAreaFixtures::ACTIVITY_AREA_ID_1,
                ActivityAreaFixtures::ACTIVITY_AREA_ID_2,
            ],
            'accessibility' => [
                ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_1,
                ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_4,
            ],
            'spaceType' => SpaceTypeFixtures::SPACE_TYPE_ID_1,
        ]);
    }
}
