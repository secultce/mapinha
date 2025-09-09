<?php

declare(strict_types=1);

namespace App\Enum;

use App\Enum\Trait\EnumTrait;

enum SexualOrientationEnum: string
{
    use EnumTrait;

    case HETEROSEXUAL = 'Heterossexual';
    case HOMOSEXUAL = 'Homossexual';
    case BISEXUAL = 'Bissexual';
    case ASSEXUAL = 'Assexual';
    case PANSEXUAL = 'Pansexual';
    case DEMISSEXUAL = 'Demissexual';
    case OMNISSEXUAL = 'Omnissexual';
    case QUEER = 'Queer';
    case POLISSEXUAL = 'Polissexual';
    case SAPIOSEXUAL = 'Sapiossexual';
    case INDEFINIDO = 'Prefere não dizer';
    case OUTRA = 'Outra';
}
