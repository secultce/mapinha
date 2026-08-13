<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'event_address')]
class EventAddress extends Address
{
    #[ORM\OneToOne(targetEntity: Event::class, inversedBy: 'address')]
    #[ORM\JoinColumn(name: 'owner_id', referencedColumnName: 'id', nullable: false)]
    public Event $owner;

    public function __construct()
    {
        $this->setId(Uuid::v4());
        parent::__construct();
    }

    public function getOwner(): Event
    {
        return $this->owner;
    }

    public function setOwner(Event $owner): void
    {
        $this->owner = $owner;
    }

    public function toArray(): array
    {
        $data = parent::toArray();
        $data['owner'] = $this->owner->getId()?->toRfc4122();

        return $data;
    }
}
