<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AddressRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ORM\Table(name: 'address')]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'owner_type', type: 'string')]
#[ORM\DiscriminatorMap(['agent' => AgentAddress::class, 'space' => SpaceAddress::class, 'event' => EventAddress::class])]
abstract class Address extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[Groups(['address.get', 'agent.get', 'space.get'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['address.get', 'agent.get', 'space.get'])]
    private string $street;

    #[ORM\Column(length: 5)]
    #[Groups(['address.get', 'agent.get', 'space.get'])]
    private string $number;

    #[ORM\Column(length: 50)]
    #[Groups(['address.get', 'agent.get', 'space.get'])]
    private string $neighborhood;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['address.get', 'agent.get', 'space.get'])]
    private ?string $complement = null;

    #[ORM\ManyToOne(targetEntity: City::class)]
    #[ORM\JoinColumn(name: 'city_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['address.get', 'agent.get', 'space.get'])]
    private City $city;

    #[ORM\Column(length: 8)]
    #[Groups(['address.get', 'agent.get', 'space.get'])]
    private string $zipcode;

    private Agent|Space|null $owner = null;

    #[ORM\Column]
    #[Groups(['address.get'])]
    private ?DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    #[Groups(['address.get'])]
    private ?DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['address.get'])]
    private ?DateTimeImmutable $deletedAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(?Uuid $id): void
    {
        $this->id = $id;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function setNumber(string $number): void
    {
        $this->number = $number;
    }

    public function getNeighborhood(): string
    {
        return $this->neighborhood;
    }

    public function setNeighborhood(string $neighborhood): void
    {
        $this->neighborhood = $neighborhood;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): void
    {
        $this->complement = $complement;
    }

    public function getCity(): City
    {
        return $this->city;
    }

    public function setCity(City $city): void
    {
        $this->city = $city;
    }

    public function getZipcode(): string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): void
    {
        $this->zipcode = $zipcode;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeImmutable $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function getCompleteAddress(): string
    {
        $city = $this->city->getName();
        $state = $this->city->getState()->acronym;

        return trim(" 
            {$this->street}, {$this->number} - {$this->neighborhood}, {$city}-{$state}, {$this->zipcode}
        ");
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id?->toRfc4122(),
            'street' => $this->street,
            'number' => $this->number,
            'neighborhood' => $this->neighborhood,
            'complement' => $this->complement,
            'zipcode' => $this->zipcode,
            'city' => $this->city?->toArray(),
            'owner' => $this->owner?->getId()?->toRfc4122(),
            'createdAt' => $this->createdAt->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt?->format('Y-m-d H:i:s'),
            'deletedAt' => $this->deletedAt?->format('Y-m-d H:i:s'),
        ];
    }
}
