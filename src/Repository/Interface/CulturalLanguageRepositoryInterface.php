<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\CulturalLanguage;

interface CulturalLanguageRepositoryInterface
{
    public function save(CulturalLanguage $culturalLanguage): CulturalLanguage;

    public function remove(CulturalLanguage $culturalLanguage): void;
}
