<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\OrganizationTypeEnum;
use App\Enum\SocialNetworkEnum;
use App\Helper\DateFormatHelper;
use App\Repository\OrganizationRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: OrganizationRepository::class)]
class Organization extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[Groups(['agent.get', 'organization.get'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 100)]
    #[Groups('organization.get')]
    private string $name;

    #[ORM\Column(nullable: true)]
    #[Groups('organization.get')]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups('organization.get')]
    private ?string $longDescription = null;

    #[ORM\Column(type: 'string', nullable: false)]
    #[Groups('organization.get')]
    private string $type = OrganizationTypeEnum::UNDEFINED->value;

    #[ORM\Column(nullable: true)]
    #[Groups('organization.get')]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('organization.get')]
    private ?string $coverImage = null;

    #[ORM\JoinTable(name: 'organizations_agents')]
    #[ORM\JoinColumn(name: 'organization_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'agent_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Agent::class, inversedBy: 'organizations')]
    #[Groups('organization.get')]
    private Collection $agents;

    #[ORM\ManyToOne(targetEntity: Agent::class)]
    #[ORM\JoinColumn(referencedColumnName: 'id', nullable: false, onDelete: 'SET NULL')]
    #[Groups('organization.get')]
    private Agent $owner;

    #[ORM\ManyToOne(targetEntity: Agent::class)]
    #[ORM\JoinColumn(name: 'created_by_id', referencedColumnName: 'id', nullable: false, onDelete: 'SET NULL')]
    #[Groups('organization.get')]
    private Agent $createdBy;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['organization.get.item'])]
    private ?array $extraFields = null;

    /**
     * @var array<string, string>
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $socialNetworks = [];

    #[ORM\ManyToMany(targetEntity: ActivityArea::class, inversedBy: 'organizations', cascade: ['persist'])]
    #[ORM\JoinTable(name: 'activity_area_organizations')]
    #[Groups(['organization.get', 'organization.get.item'])]
    private Collection $activityAreas;

    #[ORM\Column]
    #[Groups('organization.get')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    #[Groups('organization.get')]
    private ?DateTime $updatedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups('organization.get')]
    private ?DateTime $deletedAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->agents = new ArrayCollection();
        $this->activityAreas = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(?string $longDescription): void
    {
        $this->longDescription = $longDescription;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(?string $coverImage): void
    {
        $this->coverImage = $coverImage;
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
        $this->agents->add($agent);
    }

    public function removeAgent(Agent $agent): void
    {
        $this->agents->removeElement($agent);
    }

    public function getOwner(): Agent
    {
        return $this->owner;
    }

    public function setOwner(Agent $owner): void
    {
        $this->owner = $owner;
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

    public function addExtraField(string $name, mixed $value): void
    {
        $this->extraFields[$name] = $value;
    }

    public function getActivityAreas(): Collection
    {
        return $this->activityAreas;
    }

    public function setActivityAreas(Collection $activityAreas): void
    {
        $this->activityAreas = $activityAreas;
    }

    public function addActivityArea(ActivityArea $activityArea): void
    {
        if (!$this->activityAreas->contains($activityArea)) {
            $this->activityAreas->add($activityArea);
        }
    }

    public function removeActivityArea(ActivityArea $activityArea): void
    {
        $this->activityAreas->removeElement($activityArea);
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

    public function hasAgent(Agent $agent): bool
    {
        return $this->agents->contains($agent);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id?->toRfc4122(),
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'agents' => $this->agents->map(fn ($agent) => $agent->getId()->toRfc4122()),
            'owner' => $this->owner->toArray(),
            'createdBy' => $this->createdBy->toArray(),
            'extraFields' => $this->extraFields,
            'socialNetworks' => $this->socialNetworks,
            'createdAt' => $this->createdAt->format(DateFormatHelper::DEFAULT_FORMAT),
            'updatedAt' => $this->updatedAt?->format(DateFormatHelper::DEFAULT_FORMAT),
            'deletedAt' => $this->deletedAt?->format(DateFormatHelper::DEFAULT_FORMAT),
        ];
    }
}
