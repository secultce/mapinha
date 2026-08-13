<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class SpaceAddress extends Address
{
    #[ORM\OneToOne(targetEntity: Space::class, inversedBy: 'address')]
    #[ORM\JoinColumn(name: 'owner_id', referencedColumnName: 'id', nullable: false)]
    public ?Space $owner = null;

    public function __construct()
    {
        $this->setId(Uuid::v4());
        parent::__construct();
    }

    public function getOwner(): Space
    {
        return $this->owner;
    }

    public function setOwner(?Space $owner): void
    {
        $this->owner = $owner;
    }
}
