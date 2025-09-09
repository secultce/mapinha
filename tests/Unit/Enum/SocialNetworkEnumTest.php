<?php

declare(strict_types=1);

namespace App\Tests\Unit\Enum;

use App\Enum\SocialNetworkEnum;
use App\Enum\Trait\EnumTrait;
use PHPUnit\Framework\TestCase;

class SocialNetworkEnumTest extends TestCase
{
    public function testEnumCasesHaveCorrectValues(): void
    {
        $this->assertSame('facebook', SocialNetworkEnum::FACEBOOK->value);
        $this->assertSame('instagram', SocialNetworkEnum::INSTAGRAM->value);
        $this->assertSame('linkedin', SocialNetworkEnum::LINKEDIN->value);
        $this->assertSame('pinterest', SocialNetworkEnum::PINTEREST->value);
        $this->assertSame('spotify', SocialNetworkEnum::SPOTIFY->value);
        $this->assertSame('vimeo', SocialNetworkEnum::VIMEO->value);
        $this->assertSame('tiktok', SocialNetworkEnum::TIKTOK->value);
        $this->assertSame('x', SocialNetworkEnum::X->value);
        $this->assertSame('youtube', SocialNetworkEnum::YOUTUBE->value);
    }

    public function testEnumTraitMethods(): void
    {
        $keys = SocialNetworkEnum::getNames();

        $this->assertIsArray($keys);
        $this->assertContains('facebook', $keys);
        $this->assertContains('instagram', $keys);
        $this->assertContains('linkedin', $keys);
        $this->assertContains('pinterest', $keys);
        $this->assertContains('spotify', $keys);
        $this->assertContains('vimeo', $keys);
        $this->assertContains('tiktok', $keys);
        $this->assertContains('x', $keys);
        $this->assertContains('youtube', $keys);
        $this->assertCount(9, $keys);

        $values = SocialNetworkEnum::getValues();

        $this->assertIsArray($values);
        $this->assertContains('facebook', $values);
        $this->assertContains('instagram', $values);
        $this->assertContains('linkedin', $values);
        $this->assertContains('pinterest', $values);
        $this->assertContains('spotify', $values);
        $this->assertContains('vimeo', $values);
        $this->assertContains('tiktok', $values);
        $this->assertContains('x', $values);
        $this->assertContains('youtube', $values);
        $this->assertCount(9, $values);
    }

    public function testEnumUsesEnumTrait(): void
    {
        $usedTraits = class_uses(SocialNetworkEnum::class);

        $this->assertIsArray($usedTraits);
        $this->assertArrayHasKey(EnumTrait::class, $usedTraits);
    }
}
