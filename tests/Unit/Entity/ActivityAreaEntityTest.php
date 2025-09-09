<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\ActivityArea;
use App\Tests\AbstractApiTestCase;
use Symfony\Component\Uid\Uuid;

class ActivityAreaEntityTest extends AbstractApiTestCase
{
    public function testGetters(): void
    {
        $activityArea = new ActivityArea();

        $id = Uuid::v4();
        $name = 'Cultural Activities';

        $activityArea->setId($id);
        $activityArea->setName($name);

        $this->assertEquals($id, $activityArea->getId());
        $this->assertEquals($name, $activityArea->getName());
    }

    public function testToArray(): void
    {
        $activityArea = new ActivityArea();

        $id = Uuid::v4();
        $name = 'Cultural Activities';

        $activityArea->setId($id);
        $activityArea->setName($name);

        $this->assertEquals(
            [
                'id' => $id->toRfc4122(),
                'name' => $name,
            ],
            $activityArea->toArray()
        );
    }
}
