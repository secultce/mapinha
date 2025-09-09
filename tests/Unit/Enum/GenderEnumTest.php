<?php

declare(strict_types=1);

namespace App\Tests\Unit\Enum;

use App\Enum\GenderEnum;
use App\Enum\Trait\EnumTrait;
use PHPUnit\Framework\TestCase;

class GenderEnumTest extends TestCase
{
    public function testEnumCasesHaveCorrectValues(): void
    {
        $this->assertSame('Masculino', GenderEnum::MASCULINO->value);
        $this->assertSame('Feminino', GenderEnum::FEMININO->value);
        $this->assertSame('Trans Masculino', GenderEnum::TRANS_MASCULINO->value);
        $this->assertSame('Trans Feminino', GenderEnum::TRANS_FEMININO->value);
        $this->assertSame('Não Binário', GenderEnum::NAO_BINARIO->value);
        $this->assertSame('Gênero Fluido', GenderEnum::GENERO_FLUIDO->value);
        $this->assertSame('Agênero', GenderEnum::AGENERO->value);
        $this->assertSame('Bigênero', GenderEnum::BIGENERO->value);
        $this->assertSame('Intersexo', GenderEnum::INTERSEXO->value);
        $this->assertSame('Pangênero', GenderEnum::PANGENERO->value);
        $this->assertSame('Two-Spirit', GenderEnum::TWO_SPIRIT->value);
        $this->assertSame('Outro', GenderEnum::OUTRO->value);
        $this->assertSame('Prefere não informar', GenderEnum::PREFERE_NAO_INFORMAR->value);
    }

    public function testEnumTraitMethods(): void
    {
        $keys = GenderEnum::getNames();

        $this->assertIsArray($keys);
        $this->assertContains('masculino', $keys);
        $this->assertContains('feminino', $keys);
        $this->assertContains('trans_masculino', $keys);
        $this->assertContains('trans_feminino', $keys);
        $this->assertContains('nao_binario', $keys);
        $this->assertContains('genero_fluido', $keys);
        $this->assertContains('agenero', $keys);
        $this->assertContains('bigenero', $keys);
        $this->assertContains('intersexo', $keys);
        $this->assertContains('pangenero', $keys);
        $this->assertContains('two_spirit', $keys);
        $this->assertContains('outro', $keys);
        $this->assertContains('prefere_nao_informar', $keys);
        $this->assertCount(13, $keys);

        $values = GenderEnum::getValues();

        $this->assertIsArray($values);
        $this->assertContains('Masculino', $values);
        $this->assertContains('Feminino', $values);
        $this->assertContains('Trans Masculino', $values);
        $this->assertContains('Trans Feminino', $values);
        $this->assertContains('Não Binário', $values);
        $this->assertContains('Gênero Fluido', $values);
        $this->assertContains('Agênero', $values);
        $this->assertContains('Bigênero', $values);
        $this->assertContains('Intersexo', $values);
        $this->assertContains('Pangênero', $values);
        $this->assertContains('Two-Spirit', $values);
        $this->assertContains('Outro', $values);
        $this->assertContains('Prefere não informar', $values);
        $this->assertCount(13, $values);
    }

    public function testEnumUsesEnumTrait(): void
    {
        $usedTraits = class_uses(GenderEnum::class);

        $this->assertIsArray($usedTraits);
        $this->assertArrayHasKey(EnumTrait::class, $usedTraits);
    }
}
