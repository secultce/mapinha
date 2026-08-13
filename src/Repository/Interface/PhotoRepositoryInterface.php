<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\Photo;

interface PhotoRepositoryInterface
{
    public function save(Photo $photo): Photo;
}
