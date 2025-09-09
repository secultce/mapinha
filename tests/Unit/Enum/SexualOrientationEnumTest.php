<?php

declare(strict_types=1);

namespace App\Tests\Unit\Enum;

use App\Enum\SexualOrientationEnum;
use App\Enum\Trait\EnumTrait;
use PHPUnit\Framework\TestCase;

class SexualOrientationEnumTest extends TestCase
{
    public function testEnumCasesHaveCorrectValues(): void
    {
        $this->assertSame('Heterossexual', SexualOrientationEnum::HETEROSEXUAL->value);
        $this->assertSame('Homossexual', SexualOrientationEnum::HOMOSEXUAL->value);
        $this->assertSame('Bissexual', SexualOrientationEnum::BISEXUAL->value);
        $this->assertSame('Assexual', SexualOrientationEnum::ASSEXUAL->value);
        $this->assertSame('Pansexual', SexualOrientationEnum::PANSEXUAL->value);
        $this->assertSame('Demissexual', SexualOrientationEnum::DEMISSEXUAL->value);
        $this->assertSame('Omnissexual', SexualOrientationEnum::OMNISSEXUAL->value);
        $this->assertSame('Queer', SexualOrientationEnum::QUEER->value);
        $this->assertSame('Polissexual', SexualOrientationEnum::POLISSEXUAL->value);
        $this->assertSame('Sapiossexual', SexualOrientationEnum::SAPIOSEXUAL->value);
        $this->assertSame('Prefere não dizer', SexualOrientationEnum::INDEFINIDO->value);
        $this->assertSame('Outra', SexualOrientationEnum::OUTRA->value);
    }

    public function testEnumTraitMethods(): void
    {
        $keys = SexualOrientationEnum::getNames();

        $this->assertIsArray($keys);
        $this->assertContains('heterosexual', $keys);
        $this->assertContains('homosexual', $keys);
        $this->assertContains('bisexual', $keys);
        $this->assertContains('assexual', $keys);
        $this->assertContains('pansexual', $keys);
        $this->assertContains('demissexual', $keys);
        $this->assertContains('omnissexual', $keys);
        $this->assertContains('queer', $keys);
        $this->assertContains('polissexual', $keys);
        $this->assertContains('sapiosexual', $keys);
        $this->assertContains('indefinido', $keys);
        $this->assertContains('outra', $keys);
        $this->assertCount(12, $keys);

        $values = SexualOrientationEnum::getValues();

        $this->assertIsArray($values);
        $this->assertContains('Heterossexual', $values);
        $this->assertContains('Homossexual', $values);
        $this->assertContains('Bissexual', $values);
        $this->assertContains('Assexual', $values);
        $this->assertContains('Pansexual', $values);
        $this->assertContains('Demissexual', $values);
        $this->assertContains('Omnissexual', $values);
        $this->assertContains('Queer', $values);
        $this->assertContains('Polissexual', $values);
        $this->assertContains('Sapiossexual', $values);
        $this->assertContains('Prefere não dizer', $values);
        $this->assertContains('Outra', $values);
        $this->assertCount(12, $values);
    }

    public function testEnumUsesEnumTrait(): void
    {
        $usedTraits = class_uses(SexualOrientationEnum::class);

        $this->assertIsArray($usedTraits);
        $this->assertArrayHasKey(EnumTrait::class, $usedTraits);
    }
}
