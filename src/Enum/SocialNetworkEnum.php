<?php

declare(strict_types=1);

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum SocialNetworkEnum: string
{
    use EnumTrait;

    case FACEBOOK = 'facebook';
    case INSTAGRAM = 'instagram';
    case LINKEDIN = 'linkedin';
    case PINTEREST = 'pinterest';
    case SPOTIFY = 'spotify';
    case VIMEO = 'vimeo';
    case TIKTOK = 'tiktok';
    case X = 'x';
    case YOUTUBE = 'youtube';
}
