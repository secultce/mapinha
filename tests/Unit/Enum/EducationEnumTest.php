<?php

declare(strict_types=1);

namespace App\Tests\Unit\Enum;

use App\Enum\EducationEnum;
use App\Enum\Trait\EnumTrait;
use PHPUnit\Framework\TestCase;

class EducationEnumTest extends TestCase
{
    public function testEnumCasesHaveCorrectValues(): void
    {
        $this->assertSame('Não alfabetizado', EducationEnum::NOT_LITERATE->value);
        $this->assertSame('Fundamental incompleto', EducationEnum::ELEMENTARY_INCOMPLETE->value);
        $this->assertSame('Fundamental completo', EducationEnum::ELEMENTARY_COMPLETE->value);
        $this->assertSame('Médio incompleto', EducationEnum::HIGH_SCHOOL_INCOMPLETE->value);
        $this->assertSame('Médio completo', EducationEnum::HIGH_SCHOOL_COMPLETE->value);
        $this->assertSame('Superior incompleto', EducationEnum::COLLEGE_INCOMPLETE->value);
        $this->assertSame('Superior completo', EducationEnum::COLLEGE_COMPLETE->value);
        $this->assertSame('Pós-graduação (lato sensu)', EducationEnum::POSTGRADUATE->value);
        $this->assertSame('Mestrado (stricto sensu)', EducationEnum::MASTER->value);
        $this->assertSame('Doutorado (stricto sensu)', EducationEnum::DOCTORATE->value);
        $this->assertSame('Pós-doutorado', EducationEnum::POST_DOCTORATE->value);
        $this->assertSame('Outro', EducationEnum::OTHER->value);
        $this->assertSame('Prefere não informar', EducationEnum::PREFER_NOT_TO_DISCLOSE->value);
    }

    public function testEnumTraitMethods(): void
    {
        $keys = EducationEnum::getNames();

        $this->assertIsArray($keys);
        $this->assertContains('not_literate', $keys);
        $this->assertContains('elementary_incomplete', $keys);
        $this->assertContains('elementary_complete', $keys);
        $this->assertContains('high_school_incomplete', $keys);
        $this->assertContains('high_school_complete', $keys);
        $this->assertContains('college_incomplete', $keys);
        $this->assertContains('college_complete', $keys);
        $this->assertContains('postgraduate', $keys);
        $this->assertContains('master', $keys);
        $this->assertContains('doctorate', $keys);
        $this->assertContains('post_doctorate', $keys);
        $this->assertContains('other', $keys);
        $this->assertContains('prefer_not_to_disclose', $keys);
        $this->assertCount(13, $keys);

        $values = EducationEnum::getValues();

        $this->assertIsArray($values);
        $this->assertContains('Não alfabetizado', $values);
        $this->assertContains('Fundamental incompleto', $values);
        $this->assertContains('Fundamental completo', $values);
        $this->assertContains('Médio incompleto', $values);
        $this->assertContains('Médio completo', $values);
        $this->assertContains('Superior incompleto', $values);
        $this->assertContains('Superior completo', $values);
        $this->assertContains('Pós-graduação (lato sensu)', $values);
        $this->assertContains('Mestrado (stricto sensu)', $values);
        $this->assertContains('Doutorado (stricto sensu)', $values);
        $this->assertContains('Pós-doutorado', $values);
        $this->assertContains('Outro', $values);
        $this->assertContains('Prefere não informar', $values);
        $this->assertCount(13, $values);
    }

    public function testEnumUsesEnumTrait(): void
    {
        $usedTraits = class_uses(EducationEnum::class);

        $this->assertIsArray($usedTraits);
        $this->assertArrayHasKey(EnumTrait::class, $usedTraits);
    }
}
