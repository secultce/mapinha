<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Photo;
use App\Repository\Interface\PhotoRepositoryInterface;
use Doctrine\Persistence\ManagerRegistry;

class PhotoRepository extends AbstractRepository implements PhotoRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Photo::class);
    }

    public function save(Photo $photo): Photo
    {
        $this->getEntityManager()->persist($photo);
        $this->getEntityManager()->flush();

        return $photo;
    }
}
