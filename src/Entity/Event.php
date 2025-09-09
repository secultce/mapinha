<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\AccessibilityInfoEnum;
use App\Enum\EventFormatEnum;
use App\Enum\SocialNetworkEnum;
use App\Helper\DateFormatHelper;
use App\Repository\EventRepository;
use App\Service\ExporterInscriptions\ExportableSourceInterface;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event extends AbstractEntity implements ExportableSourceInterface
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[Groups(['event.get', 'opportunity.get', 'event-activity.get', 'inscription-event.get'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['event.get'])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['event.get'])]
    private ?string $image = null;

    #[ORM\ManyToOne(targetEntity: Agent::class)]
    #[ORM\JoinColumn(name: 'agent_group_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Groups(['event.get'])]
    private ?Agent $agentGroup = null;

    #[ORM\ManyToOne(targetEntity: Space::class)]
    #[ORM\JoinColumn(name: 'space_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Groups(['event.get'])]
    private ?Space $space = null;

    #[ORM\ManyToOne(targetEntity: Initiative::class)]
    #[ORM\JoinColumn(name: 'initiative_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Groups(['event.get'])]
    private ?Initiative $initiative = null;

    #[ORM\ManyToOne(targetEntity: self::class)]
    #[ORM\JoinColumn(name: 'parent_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Groups(['event.get'])]
    private ?Event $parent = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['event.get.item'])]
    private ?array $extraFields = null;

    #[ORM\ManyToOne(targetEntity: Agent::class)]
    #[ORM\JoinColumn(name: 'created_by_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['event.get'])]
    private Agent $createdBy;

    #[ORM\OneToMany(targetEntity: EventSchedule::class, mappedBy: 'event', orphanRemoval: true)]
    private Collection $eventSchedules;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['event.get'])]
    private ?string $coverImage = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['event.get'])]
    private ?string $subtitle = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['event.get'])]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['event.get'])]
    private ?string $longDescription = null;

    #[ORM\Column()]
    #[Groups(['event.get'])]
    private int $format = EventFormatEnum::IN_PERSON->value;

    #[ORM\ManyToOne(targetEntity: EventType::class)]
    #[ORM\JoinColumn(name: 'event_type_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    #[Groups(['event.get', 'event.get.item'])]
    private ?EventType $eventType = null;

    #[ORM\Column]
    #[Groups(['event.get'])]
    private DateTime $startDate;

    #[ORM\Column]
    #[Groups(['event.get'])]
    private ?DateTime $endDate = null;

    #[ORM\ManyToMany(targetEntity: ActivityArea::class)]
    #[ORM\JoinTable(name: 'activity_area_events')]
    #[Groups(['event.get', 'event.get.item'])]
    private Collection $activityAreas;

    #[ORM\ManyToMany(targetEntity: Tag::class)]
    #[ORM\JoinTable(name: 'tag_events')]
    #[Groups(['event.get', 'event.get.item'])]
    private Collection $tags;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['event.get'])]
    private ?string $site = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['event.get'])]
    private ?string $phoneNumber = null;

    #[ORM\Column]
    #[Groups(['event.get'])]
    private ?int $maxCapacity = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['event.get'])]
    private int $accessibleAudio = AccessibilityInfoEnum::NOT_INFORMED->value;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['event.get'])]
    private int $accessibleLibras = AccessibilityInfoEnum::NOT_INFORMED->value;

    #[ORM\Column]
    #[Groups(['event.get'])]
    private bool $free = true;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups(['event.get', 'event.get.item'])]
    private bool $draft = true;

    #[ORM\ManyToMany(targetEntity: CulturalLanguage::class)]
    #[ORM\JoinTable(name: 'event_cultural_languages')]
    #[Groups(['event.get', 'event.get.item'])]
    private Collection $culturalLanguages;

    /**
     * @var array<string, string>
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $socialNetworks = [];

    #[ORM\Column]
    #[Groups(['event.get'])]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    #[Groups(['event.get'])]
    private ?DateTime $updatedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['event.get'])]
    private ?DateTime $deletedAt = null;

    /**
     * @var Collection<int, EventActivity>
     */
    #[ORM\OneToMany(targetEntity: EventActivity::class, mappedBy: 'event', orphanRemoval: true)]
    private Collection $eventActivities;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->startDate = new DateTime();
        $this->eventActivities = new ArrayCollection();
        $this->activityAreas = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->culturalLanguages = new ArrayCollection();
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

    public function getAgentGroup(): ?Agent
    {
        return $this->agentGroup;
    }

    public function setAgentGroup(?Agent $agentGroup): void
    {
        $this->agentGroup = $agentGroup;
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

    public function getCreatedBy(): Agent
    {
        return $this->createdBy;
    }

    public function setCreatedBy(Agent $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function getParent(): ?Event
    {
        return $this->parent;
    }

    public function setParent(?Event $parent): void
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

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(?string $coverImage): void
    {
        $this->coverImage = $coverImage;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): void
    {
        $this->subtitle = $subtitle;
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

    public function getFormat(): int
    {
        return $this->format;
    }

    public function setFormat(int $format): void
    {
        $this->format = $format;
    }

    public function getEventType(): ?EventType
    {
        return $this->eventType;
    }

    public function setEventType(?EventType $eventType): void
    {
        $this->eventType = $eventType;
    }

    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getActivityAreas(): Collection
    {
        return $this->activityAreas;
    }

    public function setActivityAreas(Collection $activityAreas): Collection
    {
        return $this->activityAreas = $activityAreas;
    }

    public function addActivityArea(ActivityArea $activityArea): void
    {
        if (true === $this->activityAreas->contains($activityArea)) {
            return;
        }

        $this->activityAreas->add($activityArea);
    }

    public function removeActivityArea(ActivityArea $activityArea): void
    {
        $this->activityAreas->removeElement($activityArea);
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function setTags(Collection $tags): Collection
    {
        return $this->tags = $tags;
    }

    public function addTag(Tag $tag): void
    {
        if (true === $this->tags->contains($tag)) {
            return;
        }

        $this->tags->add($tag);
    }

    public function removeTag(Tag $tag): void
    {
        $this->tags->removeElement($tag);
    }

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(?string $site): void
    {
        $this->site = $site;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getMaxCapacity(): ?int
    {
        return $this->maxCapacity;
    }

    public function setMaxCapacity(?int $maxCapacity): void
    {
        $this->maxCapacity = $maxCapacity;
    }

    public function getAccessibleAudio(): int
    {
        return $this->accessibleAudio;
    }

    public function setAccessibleAudio(int $accessibleAudio): void
    {
        $this->accessibleAudio = $accessibleAudio;
    }

    public function getAccessibleLibras(): int
    {
        return $this->accessibleLibras;
    }

    public function setAccessibleLibras(int $accessibleLibras): void
    {
        $this->accessibleLibras = $accessibleLibras;
    }

    public function isFree(): bool
    {
        return $this->free;
    }

    public function setFree(bool $free): void
    {
        $this->free = $free;
    }

    public function isDraft(): bool
    {
        return $this->draft;
    }

    public function setDraft(bool $draft): void
    {
        $this->draft = $draft;
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

    public function getEventActivities(): Collection
    {
        return $this->eventActivities;
    }

    public function addEventActivity(EventActivity $eventActivity): void
    {
        if (false === $this->eventActivities->contains($eventActivity)) {
            return;
        }

        $this->eventActivities->add($eventActivity);
        $eventActivity->setEvent($this);
    }

    public function removeEventActivity(EventActivity $eventActivity): void
    {
        if (false === $this->eventActivities->removeElement($eventActivity)) {
            return;
        }

        if ($eventActivity->getEvent() === $this) {
            $eventActivity->setEvent(null);
        }
    }

    public function getEventSchedules(): Collection
    {
        return $this->eventSchedules;
    }

    public function addEventSchedule(EventSchedule $eventSchedule): void
    {
        if (false === $this->eventSchedules->contains($eventSchedule)) {
            return;
        }

        $this->eventSchedules->add($eventSchedule);
        $eventSchedule->setEvent($this);
    }

    public function removeEventSchedule(EventSchedule $eventSchedule): void
    {
        if (false === $this->eventSchedules->removeElement($eventSchedule)) {
            return;
        }

        if ($eventSchedule->getEvent() === $this) {
            $eventSchedule->setEvent(null);
        }
    }

    public function getCulturalLanguages(): Collection
    {
        return $this->culturalLanguages;
    }

    public function setCulturalLanguages(Collection $culturalLanguages): void
    {
        $this->culturalLanguages = $culturalLanguages;
    }

    public function addCulturalLanguage(CulturalLanguage $culturalLanguage): void
    {
        if (!$this->culturalLanguages->contains($culturalLanguage)) {
            $this->culturalLanguages->add($culturalLanguage);
        }
    }

    public function removeCulturalLanguage(CulturalLanguage $culturalLanguage): void
    {
        $this->culturalLanguages->removeElement($culturalLanguage);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id?->toRfc4122(),
            'name' => $this->name,
            'agentGroup' => $this->agentGroup?->toArray(),
            'space' => $this->space?->toArray(),
            'initiative' => $this->initiative?->toArray(),
            'parent' => $this->parent?->toArray(),
            'createdBy' => $this->createdBy->toArray(),
            'coverImage' => $this->coverImage,
            'subtitle' => $this->subtitle,
            'shortDescription' => $this->shortDescription,
            'longDescription' => $this->longDescription,
            'format' => $this->format,
            'eventType' => $this->eventType?->toArray(),
            'startDate' => $this->startDate?->format(DateFormatHelper::DEFAULT_FORMAT),
            'endDate' => $this->endDate?->format(DateFormatHelper::DEFAULT_FORMAT),
            'activityAreas' => $this->activityAreas->map(fn ($activityArea) => $activityArea->toArray())->toArray(),
            'tags' => $this->tags->map(fn ($tag) => $tag->toArray())->toArray(),
            'site' => $this->site,
            'phoneNumber' => $this->phoneNumber,
            'maxCapacity' => $this->maxCapacity,
            'accessibleAudio' => $this->accessibleAudio,
            'accessibleLibras' => $this->accessibleLibras,
            'free' => $this->free,
            'draft' => $this->draft,
            'culturalLanguages' => $this->culturalLanguages->map(fn ($culturalLanguage) => $culturalLanguage->toArray())->toArray(),
            'socialNetworks' => $this->socialNetworks,
            'createdAt' => $this->createdAt->format(DateFormatHelper::DEFAULT_FORMAT),
            'updatedAt' => $this->updatedAt?->format(DateFormatHelper::DEFAULT_FORMAT),
            'deletedAt' => $this->deletedAt?->format(DateFormatHelper::DEFAULT_FORMAT),
        ];
    }

    public function getExportSourceName(): string
    {
        return $this->getName();
    }
}
