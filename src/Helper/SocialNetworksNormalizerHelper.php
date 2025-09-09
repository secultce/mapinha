<?php

declare(strict_types=1);

namespace App\Helper;

use App\Enum\SocialNetworkEnum;

class SocialNetworksNormalizerHelper
{
    public static function normalizeSocialNetworks(array $data): ?array
    {
        if ([] === $data) {
            return null;
        }

        $socialNetworks = [];

        foreach ($data as $key => $username) {
            $socialNetworkEnum = SocialNetworkEnum::from($key);
            $socialNetworks[$socialNetworkEnum->value] = self::getUrl($socialNetworkEnum, $username);
        }

        return $socialNetworks;
    }

    public static function getUrl(SocialNetworkEnum $socialNetwork, string $username): ?string
    {
        return self::getBaseUrl($socialNetwork).$username;
    }

    public static function getBaseUrl(SocialNetworkEnum $socialNetworkEnum): string
    {
        return match ($socialNetworkEnum) {
            SocialNetworkEnum::FACEBOOK => 'https://facebook.com/',
            SocialNetworkEnum::INSTAGRAM => 'https://instagram.com/',
            SocialNetworkEnum::LINKEDIN => 'https://linkedin.com/',
            SocialNetworkEnum::PINTEREST => 'https://pinterest.com/',
            SocialNetworkEnum::SPOTIFY => 'https://spotify.com/',
            SocialNetworkEnum::VIMEO => 'https://vimeo.com/',
            SocialNetworkEnum::TIKTOK => 'https://tiktok.com/',
            SocialNetworkEnum::X => 'https://x.com/',
            SocialNetworkEnum::YOUTUBE => 'https://youtube.com/',
        };
    }
}
