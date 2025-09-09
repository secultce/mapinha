<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\EntityAssociation;

interface EntityAssociationRepositoryInterface
{
    public function save(EntityAssociation $entityAssociation): EntityAssociation;
}
