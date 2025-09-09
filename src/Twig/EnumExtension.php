<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use ValueError;

class EnumExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_enum_case_name', [$this, 'getEnumCaseName']),
        ];
    }

    public function getEnumCaseName(?int $value, string $enumClass): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!enum_exists($enumClass)) {
            return null;
        }

        try {
            return $enumClass::getName($value);
        } catch (ValueError) {
            return null;
        }
    }
}
