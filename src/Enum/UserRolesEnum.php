<?php

declare(strict_types=1);

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum UserRolesEnum: string
{
    use EnumTrait;

    case ROLE_ADMIN = 'ROLE_ADMIN';
    case ROLE_MANAGER = 'ROLE_MANAGER';
    case ROLE_ORGANIZATION = 'ROLE_ORGANIZATION';
    case ROLE_COMPANY = 'ROLE_COMPANY';
    case ROLE_MUNICIPALITY = 'ROLE_MUNICIPALITY';
    case ROLE_USER = 'ROLE_USER';
}
