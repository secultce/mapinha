<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\SocialNetworkEnum;
use App\Helper\DateFormatHelper;
use App\Repository\OpportunityRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: OpportunityRepository::class)]
class Opportunity extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[Groups(['opportunity.get', 'phase.get', 'inscription-opportunity.get'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 100)]
    #[Groups('opportunity.get')]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('opportunity.get')]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups('opportunity.get')]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coverImage = null;

    #[ORM\ManyToOne(targetEntity: self::class)]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Groups('opportunity.get')]
    private ?Opportunity $parent = null;

    #[ORM\ManyToOne(targetEntity: Space::class)]
    #[ORM\JoinColumn(name: 'space_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Groups('opportunity.get')]
    private ?Space $space = null;

    #[ORM\ManyToOne(targetEntity: Initiative::class)]
    #[ORM\JoinColumn(name: 'initiative_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Groups('opportunity.get')]
    private ?Initiative $initiative = null;

    #[ORM\ManyToOne(targetEntity: Event::class)]
    #[ORM\JoinColumn(name: 'event_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Groups('opportunity.get')]
    private ?Event $event = null;

    #[ORM\ManyToOne(targetEntity: Agent::class, inversedBy: 'opportunities')]
    #[ORM\JoinColumn(name: 'created_by_id', referencedColumnName: 'id', nullable: false, onDelete: 'SET NULL')]
    #[Groups('opportunity.get')]
    private Agent $createdBy;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['opportunity.get.item'])]
    private ?array $extraFields = null;

    #[ORM\OneToMany(targetEntity: Phase::class, mappedBy: 'opportunity', orphanRemoval: true)]
    #[ORM\OrderBy(['sequence' => 'ASC'])]
    #[Groups('opportunity.get.item')]
    private Collection $phases;

    /**
     * @var array<string, string>
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $socialNetworks = [];

    #[ORM\Column]
    #[Groups('opportunity.get')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    #[Groups('opportunity.get')]
    private ?DateTime $updatedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups('opportunity.get')]
    private ?DateTime $deletedAt = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->phases = new ArrayCollection();
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

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(?string $coverImage): void
    {
        $this->coverImage = $coverImage;
    }

    public function getParent(): ?Opportunity
    {
        return $this->parent;
    }

    public function setParent(?Opportunity $parent): void
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

    public function getInitiative(): ?Initiative
    {
        return $this->initiative;
    }

    public function setInitiative(?Initiative $initiative): void
    {
        $this->initiative = $initiative;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): void
    {
        $this->event = $event;
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

    public function getPhases(): Collection
    {
        return $this->phases;
    }

    public function setPhases(Collection $phases): void
    {
        $this->phases = $phases;
    }

    public function addPhase(Phase $phase): void
    {
        $this->phases->add($phase);
    }

    public function removePhase(Phase $phase): void
    {
        $this->phases->removeElement($phase);
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id?->toRfc4122(),
            'name' => $this->name,
            'description' => $this->description,
            'parent' => $this->parent?->toArray(),
            'space' => $this->space?->toArray(),
            'initiative' => $this->initiative?->toArray(),
            'event' => $this->event?->toArray(),
            'image' => $this->image,
            'createdBy' => $this->createdBy->toArray(),
            'socialNetworks' => $this->socialNetworks,
            'createdAt' => $this->createdAt->format(DateFormatHelper::DEFAULT_FORMAT),
            'updatedAt' => $this->updatedAt?->format(DateFormatHelper::DEFAULT_FORMAT),
            'deletedAt' => $this->deletedAt?->format(DateFormatHelper::DEFAULT_FORMAT),
        ];
    }
}
