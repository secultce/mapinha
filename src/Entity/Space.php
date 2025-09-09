<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\SocialNetworkEnum;
use App\Helper\DateFormatHelper;
use App\Repository\SpaceRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\MaxDepth;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SpaceRepository::class)]
class Space extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[Groups(['event.get', 'initiative.get', 'opportunity.get', 'space.get', 'activity-area.get', 'space.get.item'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['space.get', 'space.get.item'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['space.get', 'space.get.item'])]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups('space.get.item')]
    private ?string $longDescription = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['space.get', 'space.get.item'])]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('space.get.item')]
    private ?string $coverImage = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('space.get.item')]
    private ?string $site = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('space.get.item')]
    private ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups('space.get.item')]
    private ?string $phoneNumber = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Groups('space.get.item')]
    private ?int $maxCapacity = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups(['space.get', 'space.get.item'])]
    private bool $isAccessible = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups(['space.get', 'space.get.item'])]
    private bool $isDraft = true;

    #[ORM\OneToOne(targetEntity: SpaceAddress::class, mappedBy: 'owner', cascade: ['persist', 'remove'])]
    #[Groups(['space.get', 'space.get.item'])]
    private ?SpaceAddress $address = null;

    #[ORM\ManyToOne(targetEntity: Agent::class)]
    #[ORM\JoinColumn(name: 'created_by_id', referencedColumnName: 'id', nullable: false, onDelete: 'SET NULL')]
    #[Groups(['space.get', 'space.get.item'])]
    private Agent $createdBy;

    #[ORM\ManyToOne(targetEntity: self::class)]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Groups('space.get.item')]
    #[MaxDepth(1)]
    private ?Space $parent = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['space.get.item'])]
    private ?array $extraFields = null;

    #[ORM\ManyToMany(targetEntity: ActivityArea::class, inversedBy: 'spaces', cascade: ['persist'])]
    #[ORM\JoinTable(name: 'activity_area_spaces')]
    #[Groups(['space.get', 'space.get.item'])]
    private Collection $activityAreas;

    #[ORM\OneToOne(targetEntity: EntityAssociation::class, mappedBy: 'space', cascade: ['persist'])]
    private ?EntityAssociation $entityAssociation = null;

    #[ORM\ManyToMany(targetEntity: Tag::class)]
    #[ORM\JoinTable(name: 'space_tags')]
    #[Groups(['space.get', 'space.get.item'])]
    private Collection $tags;

    #[ORM\ManyToMany(targetEntity: ArchitecturalAccessibility::class, inversedBy: 'spaces')]
    #[ORM\JoinTable(name: 'spaces_accessibilities')]
    #[Groups(['space.get', 'space.get.item'])]
    private Collection $accessibilities;

    /**
     * @var array<string, string>
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $socialNetworks = [];

    #[ORM\Column]
    #[Groups(['space.get', 'space.get.item'])]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    #[Groups(['space.get', 'space.get.item'])]
    private ?DateTime $updatedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['space.get', 'space.get.item'])]
    private ?DateTime $deletedAt = null;

    #[ORM\ManyToOne(targetEntity: SpaceType::class)]
    #[ORM\JoinColumn(name: 'space_type_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Groups(['space.get', 'space.get.item'])]
    private ?SpaceType $spaceType = null;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->activityAreas = new ArrayCollection();
        $this->accessibilities = new ArrayCollection();
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

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(?string $longDescription): void
    {
        $this->longDescription = $longDescription;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCreatedBy(): Agent
    {
        return $this->createdBy;
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

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(?string $site): void
    {
        $this->site = $site;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getMaxCapacity(): int
    {
        return $this->maxCapacity;
    }

    public function setMaxCapacity(int $maxCapacity): void
    {
        $this->maxCapacity = $maxCapacity;
    }

    public function isAccessible(): bool
    {
        return $this->isAccessible;
    }

    public function setIsAccessible(bool $isAccessible): void
    {
        $this->isAccessible = $isAccessible;
    }

    public function isDraft(): bool
    {
        return $this->isDraft;
    }

    public function setIsDraft(bool $isDraft): void
    {
        $this->isDraft = $isDraft;
    }

    public function setCreatedBy(Agent $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function getParent(): ?Space
    {
        return $this->parent;
    }

    public function setParent(?Space $parent): void
    {
        $this->parent = $parent;
    }

    public function getExtraFields(): ?array
    {
        return $this->extraFields;
    }

    public function setExtraFields(?array $extraFields): void
    {
        $this->extraFields = $extraFields;
    }

    public function getAddress(): ?SpaceAddress
    {
        return $this->address;
    }

    public function setAddress(SpaceAddress $address): void
    {
        $this->address = $address;
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

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function setTags(Collection $tags): void
    {
        $this->tags = $tags;
    }

    public function addTag(Tag $tag): void
    {
        if (false === $this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
    }

    public function removeTag(Tag $tag): void
    {
        $this->tags->removeElement($tag);
    }

    public function getAccessibilities(): Collection
    {
        return $this->accessibilities;
    }

    public function setAccessibilities(Collection $accessibilities): void
    {
        $this->accessibilities = $accessibilities;
    }

    public function addAccessibility(ArchitecturalAccessibility $accessibility): void
    {
        if (!$this->accessibilities->contains($accessibility)) {
            $this->accessibilities->add($accessibility);
        }
    }

    public function removeAccessibility(ArchitecturalAccessibility $accessibility): void
    {
        $this->accessibilities->removeElement($accessibility);
    }

    public function getEntityAssociation(): ?EntityAssociation
    {
        return $this->entityAssociation;
    }

    public function setEntityAssociation(EntityAssociation $entityAssociation): void
    {
        $this->entityAssociation = $entityAssociation;
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

    public function getSpaceType(): ?SpaceType
    {
        return $this->spaceType;
    }

    public function setSpaceType(?SpaceType $spaceType): void
    {
        $this->spaceType = $spaceType;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id?->toRfc4122(),
            'name' => $this->name,
            'shortDescription' => $this->shortDescription,
            'longDescription' => $this->longDescription,
            'image' => $this->image,
            'coverImage' => $this->coverImage,
            'site' => $this->site,
            'email' => $this->email,
            'phoneNumber' => $this->phoneNumber,
            'maxCapacity' => $this->maxCapacity,
            'isAccessible' => $this->isAccessible,
            'isDraft' => $this->isDraft,
            'createdBy' => $this->createdBy->getId()->toRfc4122(),
            'parent' => $this->parent?->getId()->toRfc4122(),
            'address' => $this->address?->toArray(),
            'extraFields' => $this->extraFields,
            'activityAreas' => $this->activityAreas->map(fn (ActivityArea $activityArea) => $activityArea->toArray())->toArray(),
            'entityAssociation' => $this->entityAssociation?->toArray(),
            'tags' => $this->tags->map(fn (Tag $tag) => $tag->toArray())->toArray(),
            'accessibilities' => $this->accessibilities->map(fn (ArchitecturalAccessibility $accessibility) => $accessibility->toArray())->toArray(),
            'spaceType' => $this->spaceType?->toArray(),
            'socialNetworks' => $this->socialNetworks,
            'createdAt' => $this->createdAt->format(DateFormatHelper::DEFAULT_FORMAT),
            'updatedAt' => $this->updatedAt?->format(DateFormatHelper::DEFAULT_FORMAT),
            'deletedAt' => $this->deletedAt?->format(DateFormatHelper::DEFAULT_FORMAT),
        ];
    }
}
