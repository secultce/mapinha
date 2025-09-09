<?php

declare(strict_types=1);

namespace App\Tests\Unit\Enum;

use App\Enum\EventFormatEnum;
use App\Enum\Trait\EnumTrait;
use PHPUnit\Framework\TestCase;

class EventFormatEnumTest extends TestCase
{
    public function testEnumCasesHaveCorrectValues(): void
    {
        $this->assertSame(1, EventFormatEnum::IN_PERSON->value);
        $this->assertSame(2, EventFormatEnum::ONLINE->value);
        $this->assertSame(3, EventFormatEnum::HYBRID->value);
    }

    public function testEnumTraitMethods(): void
    {
        $keys = EventFormatEnum::getNames();

        $this->assertIsArray($keys);
        $this->assertContains('in_person', $keys);
        $this->assertContains('online', $keys);
        $this->assertContains('hybrid', $keys);
        $this->assertCount(3, $keys);
    }

    public function testEnumUsesEnumTrait(): void
    {
        $usedTraits = class_uses(EventFormatEnum::class);

        $this->assertIsArray($usedTraits);
        $this->assertArrayHasKey(EnumTrait::class, $usedTraits);
    }
}
