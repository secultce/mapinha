<?php

declare(strict_types=1);

namespace App\Tests\Unit\Enum;

use App\Enum\RaceEnum;
use App\Enum\Trait\EnumTrait;
use PHPUnit\Framework\TestCase;

class RaceEnumTest extends TestCase
{
    public function testEnumCasesHaveCorrectValues(): void
    {
        $this->assertSame('Branca', RaceEnum::BRANCA->value);
        $this->assertSame('Preta', RaceEnum::PRETA->value);
        $this->assertSame('Parda', RaceEnum::PARDA->value);
        $this->assertSame('Amarela', RaceEnum::AMARELA->value);
        $this->assertSame('Indígena', RaceEnum::INDIGENA->value);
        $this->assertSame('Outra', RaceEnum::OUTRA->value);
        $this->assertSame('Prefere não informar', RaceEnum::PREFERE_NAO_INFORMAR->value);
    }

    public function testEnumTraitMethods(): void
    {
        $keys = RaceEnum::getNames();

        $this->assertIsArray($keys);
        $this->assertContains('branca', $keys);
        $this->assertContains('preta', $keys);
        $this->assertContains('parda', $keys);
        $this->assertContains('amarela', $keys);
        $this->assertContains('indigena', $keys);
        $this->assertContains('outra', $keys);
        $this->assertContains('prefere_nao_informar', $keys);
        $this->assertCount(7, $keys);

        $values = RaceEnum::getValues();

        $this->assertIsArray($values);
        $this->assertContains('Branca', $values);
        $this->assertContains('Preta', $values);
        $this->assertContains('Parda', $values);
        $this->assertContains('Amarela', $values);
        $this->assertContains('Indígena', $values);
        $this->assertContains('Outra', $values);
        $this->assertContains('Prefere não informar', $values);
        $this->assertCount(7, $values);
    }

    public function testEnumUsesEnumTrait(): void
    {
        $usedTraits = class_uses(RaceEnum::class);

        $this->assertIsArray($usedTraits);
        $this->assertArrayHasKey(EnumTrait::class, $usedTraits);
    }
}
