<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AccountEventRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: AccountEventRepository::class)]
class AccountEvent extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    private ?Uuid $id = null;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\Column(type: Types::INTEGER)]
    private int $type;

    #[ORM\Column(type: UuidType::NAME)]
    private ?Uuid $token = null;

    #[ORM\Column]
    private ?DateTimeImmutable $expirationAt = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(?Uuid $id): void
    {
        $this->id = $id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getToken(): ?Uuid
    {
        return $this->token;
    }

    public function setToken(?Uuid $token): void
    {
        $this->token = $token;
    }

    public function getExpirationAt(): ?DateTimeImmutable
    {
        return $this->expirationAt;
    }

    public function setExpirationAt(?DateTimeImmutable $expirationAt): void
    {
        $this->expirationAt = $expirationAt;
    }
}
