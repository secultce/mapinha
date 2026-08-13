<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[Groups(['city.get', 'state.get', 'address.get.item', 'space.get'])]
    private Uuid $id;

    #[ORM\Column(length: 100)]
    #[Groups(['city.get', 'state.get', 'address.get.item', 'agent.get.item'])]
    private readonly string $name;

    #[ORM\ManyToOne(targetEntity: State::class)]
    #[ORM\JoinColumn(name: 'state_id', referencedColumnName: 'id')]
    #[Groups(['city.get', 'address.get.item', 'agent.get.item'])]
    private readonly State $state;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Groups(['city.get'])]
    private readonly ?int $cityCode;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getState(): State
    {
        return $this->state;
    }

    public function getCityCode(): ?int
    {
        return $this->cityCode;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->toRfc4122(),
            'name' => $this->name,
            'state' => $this->state->toArray(),
            'cityCode' => $this->cityCode,
        ];
    }
}
