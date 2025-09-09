<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\InviteRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: InviteRepository::class)]
class Invite
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Agent::class)]
    #[ORM\JoinColumn(name: 'agent_id', nullable: true)]
    private ?Agent $guest;

    #[ORM\ManyToOne(targetEntity: Organization::class)]
    #[ORM\JoinColumn(name: 'organization_id', nullable: false)]
    private Organization $host;

    #[ORM\Column]
    private string $email;

    #[ORM\Column]
    private DateTimeImmutable $expirationAt;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getGuest(): ?Agent
    {
        return $this->guest;
    }

    public function setGuest(?Agent $guest): void
    {
        $this->guest = $guest;
    }

    public function getHost(): Organization
    {
        return $this->host;
    }

    public function setHost(Organization $host): void
    {
        $this->host = $host;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getExpirationAt(): DateTimeImmutable
    {
        return $this->expirationAt;
    }

    public function setExpirationAt(DateTimeImmutable $expirationAt): void
    {
        $this->expirationAt = $expirationAt;
    }
}
