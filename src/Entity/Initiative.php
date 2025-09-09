<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\SocialNetworkEnum;
use App\Helper\DateFormatHelper;
use App\Repository\InitiativeRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\MaxDepth;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: InitiativeRepository::class)]
class Initiative extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[Groups(['event.get', 'initiative.get', 'opportunity.get'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 100)]
    #[Groups('initiative.get')]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['initiative.get'])]
    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: self::class)]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Groups('initiative.get')]
    #[MaxDepth(1)]
    private ?Initiative $parent = null;

    #[ORM\ManyToOne(targetEntity: Space::class)]
    #[ORM\JoinColumn(name: 'space_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Groups('initiative.get')]
    private ?Space $space = null;

    #[ORM\ManyToOne(targetEntity: Organization::class)]
    #[ORM\JoinColumn(name: 'organization_from_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Groups('initiative.get')]
    private ?Organization $organizationFrom = null;

    #[ORM\ManyToOne(targetEntity: Organization::class)]
    #[ORM\JoinColumn(name: 'organization_to_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Groups('initiative.get')]
    private ?Organization $organizationTo = null;

    #[ORM\ManyToOne(targetEntity: Agent::class)]
    #[ORM\JoinColumn(name: 'created_by_id', referencedColumnName: 'id', nullable: false, onDelete: 'SET NULL')]
    #[Groups('initiative.get')]
    private Agent $createdBy;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['initiative.get.item'])]
    private ?array $extraFields = null;

    /**
     * @var array<string, string>
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $socialNetworks = [];

    #[ORM\Column]
    #[Groups('initiative.get')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    #[Groups('initiative.get')]
    private ?DateTime $updatedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups('initiative.get')]
    private ?DateTime $deletedAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getParent(): ?Initiative
    {
        return $this->parent;
    }

    public function setParent(?Initiative $parent): void
    {
        $this->parent = $parent;
    }

    public function getSpace(): ?Space
    {
        return $this->space;
    }

    public function setSpace(?Space $space): void
    {
        $this->space = $space;
    }

    public function getOrganizationFrom(): ?Organization
    {
        return $this->organizationFrom;
    }

    public function setOrganizationFrom(?Organization $organization): void
    {
        $this->organizationFrom = $organization;
    }

    public function getOrganizationTo(): ?Organization
    {
        return $this->organizationTo;
    }

    public function setOrganizationTo(?Organization $organization): void
    {
        $this->organizationTo = $organization;
    }

    public function getCreatedBy(): Agent
    {
        return $this->createdBy;
    }

    public function setCreatedBy(Agent $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function getExtraFields(): ?array
    {
        return $this->extraFields;
    }

    public function setExtraFields(?array $extraFields): void
    {
        $this->extraFields = $extraFields;
    }

    public function getSocialNetworks(): array
    {
        return $this->socialNetworks;
    }

    public function setSocialNetworks(array $socialNetworks): void
    {
        foreach ($socialNetworks as $key => $username) {
            $socialNetworksEnum = SocialNetworkEnum::from($key);
            $this->addSocialNetwork($socialNetworksEnum->value, $username);
        }
    }

    public function addSocialNetwork(string $socialNetworksEnum, $username): void
    {
        $this->socialNetworks[$socialNetworksEnum] = $username;
    }

    public function removeSocialNetwork(SocialNetworkEnum $socialNetwork): void
    {
        unset($this->socialNetworks[$socialNetwork->name]);
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

    public function toArray(): array
    {
        return [
            'id' => $this->id?->toRfc4122(),
            'name' => $this->name,
            'parent' => $this->parent?->getId()->toRfc4122(),
            'space' => $this->space?->getId()->toRfc4122(),
            'createdBy' => $this->createdBy->getId()->toRfc4122(),
            'extraFields' => $this->extraFields,
            'socialNetworks' => $this->socialNetworks,
            'createdAt' => $this->createdAt->format(DateFormatHelper::DEFAULT_FORMAT),
            'updatedAt' => $this->updatedAt?->format(DateFormatHelper::DEFAULT_FORMAT),
            'deletedAt' => $this->deletedAt?->format(DateFormatHelper::DEFAULT_FORMAT),
        ];
    }
}
