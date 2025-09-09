<?php

declare(strict_types=1);

namespace App\Service\Interface;

use App\Entity\CulturalLanguage;
use Symfony\Component\Uid\Uuid;

interface CulturalLanguageServiceInterface
{
    public function get(Uuid $id): CulturalLanguage;

    public function list(int $limit = 50): array;

    public function remove(Uuid $id): void;
}
