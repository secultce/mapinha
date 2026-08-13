<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\SpacePhoto;

interface SpacePhotoRepositoryInterface
{
    public function save(SpacePhoto $spacePhoto): SpacePhoto;
}
