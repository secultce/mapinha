<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\DataFixtures\Entity\AgentFixtures;
use App\Enum\OrganizationTypeEnum;
use Symfony\Component\Uid\Uuid;

class OrganizationTestFixtures implements TestFixtures
{
    public static function partial(): array
    {
        return [
            'id' => Uuid::v4()->toRfc4122(),
            'name' => 'Test Organization',
            'owner' => AgentFixtures::AGENT_ID_1,
            'createdBy' => AgentFixtures::AGENT_ID_1,
            'type' => OrganizationTypeEnum::EMPRESA->value,
        ];
    }

    public static function complete(): array
    {
        return array_merge(self::partial(), [
            'description' => 'Test Organization',
            'longDescription' => 'Long Description',
            'agents' => [
                AgentFixtures::AGENT_ID_1,
                AgentFixtures::AGENT_ID_2,
                AgentFixtures::AGENT_ID_3,
            ],
            'extraFields' => [
                'instagram' => '@organizationtest',
            ],
        ]);
    }
}
