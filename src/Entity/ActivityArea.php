<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ActivityAreaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ActivityAreaRepository::class)]
class ActivityArea
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[Groups([
        'space.get', 'space.get.item',
        'activity-area.get', 'activity-area.get.item',
        'event.get', 'event.get.item',
        'organization.get', 'organization.get.item',
    ])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 20)]
    #[Groups([
        'space.get', 'space.get.item',
        'activity-area.get', 'activity-area.get.item',
        'event.get', 'event.get.item',
        'organization.get', 'organization.get.item',
    ])]
    private ?string $name = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id?->toRfc4122(),
            'name' => $this->name,
        ];
    }
}
