<?php

declare(strict_types=1);

namespace App\Tests\Unit\Enum;

use App\Enum\AgeClassificationEnum;
use App\Enum\Trait\EnumTrait;
use PHPUnit\Framework\TestCase;

class AgeClassificationEnumTest extends TestCase
{
    public function testEnumCasesHaveCorrectValues(): void
    {
        $this->assertSame('Free', AgeClassificationEnum::FREE->value);
        $this->assertSame('10 years', AgeClassificationEnum::AGE_10->value);
        $this->assertSame('12 years', AgeClassificationEnum::AGE_12->value);
        $this->assertSame('14 years', AgeClassificationEnum::AGE_14->value);
        $this->assertSame('16 years', AgeClassificationEnum::AGE_16->value);
        $this->assertSame('18 years', AgeClassificationEnum::AGE_18->value);
        $this->assertSame('Not rated', AgeClassificationEnum::NOT_RATED->value);
    }

    public function testEnumTraitMethods(): void
    {
        $keys = AgeClassificationEnum::getNames();

        $this->assertIsArray($keys);
        $this->assertContains('free', $keys);
        $this->assertContains('age_10', $keys);
        $this->assertContains('age_12', $keys);
        $this->assertContains('age_14', $keys);
        $this->assertContains('age_16', $keys);
        $this->assertContains('age_18', $keys);
        $this->assertContains('not_rated', $keys);
        $this->assertCount(7, $keys);

        $values = AgeClassificationEnum::getValues();

        $this->assertIsArray($values);
        $this->assertContains('Free', $values);
        $this->assertContains('10 years', $values);
        $this->assertContains('12 years', $values);
        $this->assertContains('14 years', $values);
        $this->assertContains('16 years', $values);
        $this->assertContains('18 years', $values);
        $this->assertContains('Not rated', $values);
        $this->assertCount(7, $values);
    }

    public function testEnumUsesEnumTrait(): void
    {
        $usedTraits = class_uses(AgeClassificationEnum::class);

        $this->assertIsArray($usedTraits);
        $this->assertArrayHasKey(EnumTrait::class, $usedTraits);
    }
}
