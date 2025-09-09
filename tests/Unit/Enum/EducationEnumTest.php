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
        $this->assertSame('Not literate', EducationEnum::NOT_LITERATE->value);
        $this->assertSame('Incomplete Elementary School', EducationEnum::ELEMENTARY_INCOMPLETE->value);
        $this->assertSame('Complete Elementary School', EducationEnum::ELEMENTARY_COMPLETE->value);
        $this->assertSame('Incomplete High School', EducationEnum::HIGH_SCHOOL_INCOMPLETE->value);
        $this->assertSame('Complete High School', EducationEnum::HIGH_SCHOOL_COMPLETE->value);
        $this->assertSame('Incomplete College', EducationEnum::COLLEGE_INCOMPLETE->value);
        $this->assertSame('Complete College', EducationEnum::COLLEGE_COMPLETE->value);
        $this->assertSame('Postgraduate (lato sensu)', EducationEnum::POSTGRADUATE->value);
        $this->assertSame('Master (stricto sensu)', EducationEnum::MASTER->value);
        $this->assertSame('Doctorate (stricto sensu)', EducationEnum::DOCTORATE->value);
        $this->assertSame('Post-doctorate', EducationEnum::POST_DOCTORATE->value);
        $this->assertSame('Other', EducationEnum::OTHER->value);
        $this->assertSame('Prefer not to disclose', EducationEnum::PREFER_NOT_TO_DISCLOSE->value);
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
        $this->assertContains('Not literate', $values);
        $this->assertContains('Incomplete Elementary School', $values);
        $this->assertContains('Complete Elementary School', $values);
        $this->assertContains('Incomplete High School', $values);
        $this->assertContains('Complete High School', $values);
        $this->assertContains('Incomplete College', $values);
        $this->assertContains('Complete College', $values);
        $this->assertContains('Postgraduate (lato sensu)', $values);
        $this->assertContains('Master (stricto sensu)', $values);
        $this->assertContains('Doctorate (stricto sensu)', $values);
        $this->assertContains('Post-doctorate', $values);
        $this->assertContains('Other', $values);
        $this->assertContains('Prefer not to disclose', $values);
        $this->assertCount(13, $values);
    }

    public function testEnumUsesEnumTrait(): void
    {
        $usedTraits = class_uses(EducationEnum::class);

        $this->assertIsArray($usedTraits);
        $this->assertArrayHasKey(EnumTrait::class, $usedTraits);
    }
}
