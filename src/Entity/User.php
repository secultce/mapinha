<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\UserRolesEnum;
use App\Enum\UserStatusEnum;
use App\Helper\DateFormatHelper;
use App\Repository\UserRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`app_user`')]
class User extends AbstractEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[Groups(['agent.get', 'user.get'])]
    private Uuid $id;

    #[ORM\Column(length: 50)]
    #[Groups(['user.get'])]
    private string $firstname;

    #[ORM\Column(length: 50)]
    #[Groups(['user.get'])]
    private string $lastname;

    #[ORM\Column(length: 100, nullable: true)]
    #[Groups(['user.get'])]
    private ?string $socialName = null;

    #[ORM\Column(length: 100, unique: true)]
    private string $email;

    #[ORM\Column(length: 255)]
    private string $password;

    #[ORM\Column(nullable: true)]
    #[Groups(['user.get'])]
    private ?string $image = null;

    #[ORM\OneToMany(targetEntity: Agent::class, mappedBy: 'user')]
    #[Groups(['user.get'])]
    private Collection $agents;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Groups(['user.get'])]
    private string $status = UserStatusEnum::AWAITING_CONFIRMATION->value;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column]
    #[Groups(['user.get'])]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    #[Groups(['user.get'])]
    private ?DateTime $updatedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['user.get'])]
    private ?DateTime $deletedAt = null;

    public function __construct()
    {
        $this->agents = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }

    public function getSocialName(): ?string
    {
        return $this->socialName;
    }

    public function setSocialName(?string $socialName): void
    {
        $this->socialName = $socialName;
    }

    public function getName(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getAgents(): Collection
    {
        return $this->agents;
    }

    public function setAgents(Collection $agents): void
    {
        $this->agents = $agents;
    }

    public function addAgent(Agent $agent): void
    {
        if (false == $this->agents->contains($agent)) {
            $this->agents->add($agent);
        }
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = UserRolesEnum::ROLE_USER->value;

        return array_unique($roles);
    }

    public function isRole(UserRolesEnum $role): bool
    {
        if (in_array($role->value, $this->getRoles(), true)) {
            return true;
        }

        return false;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function addRole(string $role): void
    {
        if (true === in_array($role, $this->roles, true)) {
            return;
        }

        $this->roles[] = $role;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTime $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function isActive(): bool
    {
        return null === $this->deletedAt && UserStatusEnum::ACTIVE->value === $this->status;
    }

    public function getLastLogin(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->toRfc4122(),
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'socialName' => $this->socialName,
            'email' => $this->email,
            'image' => $this->image,
            'status' => $this->status,
            'createdAt' => $this->createdAt->format(DateFormatHelper::DEFAULT_FORMAT),
            'updatedAt' => $this->updatedAt?->format(DateFormatHelper::DEFAULT_FORMAT),
            'deletedAt' => $this->deletedAt?->format(DateFormatHelper::DEFAULT_FORMAT),
        ];
    }
}
