<?php

declare(strict_types=1);

namespace App\Tests\Unit\Helper;

use App\Enum\SocialNetworkEnum;
use App\Helper\SocialNetworksNormalizerHelper;
use PHPUnit\Framework\TestCase;
use ValueError;

final class SocialNetworksNormalizerHelperTest extends TestCase
{
    public function testNormalizeSocialNetworksWithEmptyArray(): void
    {
        $result = SocialNetworksNormalizerHelper::normalizeSocialNetworks([]);
        $this->assertNull($result, 'The method should return null for a empty array.');
    }

    public function testNormalizeSocialNetworksWithAllSocialNetworks(): void
    {
        $data = [
            SocialNetworkEnum::FACEBOOK->value => 'hello.fac',
            SocialNetworkEnum::INSTAGRAM->value => 'hello.ins',
            SocialNetworkEnum::LINKEDIN->value => 'hello.lin',
            SocialNetworkEnum::PINTEREST->value => 'hello.pin',
            SocialNetworkEnum::SPOTIFY->value => 'hello.spo',
            SocialNetworkEnum::VIMEO->value => 'hello.vim',
            SocialNetworkEnum::TIKTOK->value => 'hello.tik',
            SocialNetworkEnum::X->value => 'hello.x',
            SocialNetworkEnum::YOUTUBE->value => 'hello.you',
        ];

        $expected = [
            'https://facebook.com/hello.fac',
            'https://instagram.com/hello.ins',
            'https://linkedin.com/hello.lin',
            'https://pinterest.com/hello.pin',
            'https://spotify.com/hello.spo',
            'https://vimeo.com/hello.vim',
            'https://tiktok.com/hello.tik',
            'https://x.com/hello.x',
            'https://youtube.com/hello.you',
        ];

        $result = SocialNetworksNormalizerHelper::normalizeSocialNetworks($data);

        $this->assertIsArray($result, 'The result should be an array.');
        $this->assertEquals($expected, array_values($result), 'The URLs generated must match those expected.');
    }

    public function testGetUrlForAllSocialNetworks(): void
    {
        $username = 'hello';

        $testCases = [
            'https://facebook.com/hello' => SocialNetworkEnum::FACEBOOK,
            'https://instagram.com/hello' => SocialNetworkEnum::INSTAGRAM,
            'https://linkedin.com/hello' => SocialNetworkEnum::LINKEDIN,
            'https://pinterest.com/hello' => SocialNetworkEnum::PINTEREST,
            'https://spotify.com/hello' => SocialNetworkEnum::SPOTIFY,
            'https://vimeo.com/hello' => SocialNetworkEnum::VIMEO,
            'https://tiktok.com/hello' => SocialNetworkEnum::TIKTOK,
            'https://x.com/hello' => SocialNetworkEnum::X,
            'https://youtube.com/hello' => SocialNetworkEnum::YOUTUBE,
        ];

        foreach ($testCases as $expectedUrl => $socialNetwork) {
            $result = SocialNetworksNormalizerHelper::getUrl($socialNetwork, $username);
            $this->assertEquals($expectedUrl, $result, "The url for {$socialNetwork->value} should be {$expectedUrl}.");
        }
    }

    public function testGetBaseUrlForAllSocialNetworks(): void
    {
        $testCases = [
            'https://facebook.com/' => SocialNetworkEnum::FACEBOOK,
            'https://instagram.com/' => SocialNetworkEnum::INSTAGRAM,
            'https://linkedin.com/' => SocialNetworkEnum::LINKEDIN,
            'https://pinterest.com/' => SocialNetworkEnum::PINTEREST,
            'https://spotify.com/' => SocialNetworkEnum::SPOTIFY,
            'https://vimeo.com/' => SocialNetworkEnum::VIMEO,
            'https://tiktok.com/' => SocialNetworkEnum::TIKTOK,
            'https://x.com/' => SocialNetworkEnum::X,
            'https://youtube.com/' => SocialNetworkEnum::YOUTUBE,
        ];

        foreach ($testCases as $expectedBaseUrl => $socialNetwork) {
            $result = SocialNetworksNormalizerHelper::getBaseUrl($socialNetwork);
            $this->assertEquals($expectedBaseUrl, $result, "The url base for {$socialNetwork->value} should be {$expectedBaseUrl}.");
        }
    }

    public function testNormalizeSocialNetworksWithInvalidSocialNetwork(): void
    {
        $this->expectException(ValueError::class);

        $data = [
            'INVALID_NETWORK' => 'hello',
        ];

        SocialNetworksNormalizerHelper::normalizeSocialNetworks($data);
    }
}
