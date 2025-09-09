<?php

declare(strict_types=1);

namespace App\Tests\Unit\Enum;

use App\Enum\InscriptionEventStatusEnum;
use App\Enum\Trait\EnumTrait;
use PHPUnit\Framework\TestCase;

class InscriptionEventStatusEnumTest extends TestCase
{
    public function testEnumCasesHaveCorrectValues(): void
    {
        $this->assertSame(1, InscriptionEventStatusEnum::ACTIVE->value);
        $this->assertSame(2, InscriptionEventStatusEnum::CANCELLED->value);
        $this->assertSame(3, InscriptionEventStatusEnum::CONFIRMED->value);
        $this->assertSame(4, InscriptionEventStatusEnum::SUSPENDED->value);
    }

    public function testEnumTraitMethods(): void
    {
        $keys = InscriptionEventStatusEnum::getNames();

        $this->assertIsArray($keys);
        $this->assertCount(4, $keys);
        $this->assertContains('active', $keys);
        $this->assertContains('cancelled', $keys);
        $this->assertContains('confirmed', $keys);
        $this->assertContains('suspended', $keys);

        $values = InscriptionEventStatusEnum::getValues();

        $this->assertIsArray($values);
        $this->assertCount(4, $values);
        $this->assertContains(1, $values);
        $this->assertContains(2, $values);
        $this->assertContains(3, $values);
        $this->assertContains(4, $values);
    }

    public function testEnumUsesEnumTrait(): void
    {
        $usedTraits = class_uses(InscriptionEventStatusEnum::class);

        $this->assertIsArray($usedTraits);
        $this->assertArrayHasKey(EnumTrait::class, $usedTraits);
    }
}
