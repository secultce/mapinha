<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\SocialNetworkEnum;
use App\Helper\DateFormatHelper;
use App\Repository\AgentRepository;
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

#[ORM\Entity(repositoryClass: AgentRepository::class)]
class Agent extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[Groups(['agent.get', 'event.get', 'initiative.get', 'opportunity.get', 'space.get', 'user.get', 'organization.get', 'phase.get', 'inscription-opportunity.get', 'inscription-phase.get', 'seal.get', 'inscription-event.get'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['agent.get'])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['agent.get'])]
    private ?string $image = null;

    #[ORM\Column(length: 100)]
    #[Groups(['agent.get'])]
    private string $shortBio;

    #[ORM\Column(length: 255)]
    #[Groups(['agent.get'])]
    private string $longBio;

    #[ORM\Column]
    #[Groups(['agent.get'])]
    private bool $culture;

    #[ORM\Column]
    #[Groups(['agent.get'])]
    private bool $main = false;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['agent.get.item'])]
    private ?array $extraFields = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'agents')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[Groups('agent.get')]
    #[MaxDepth(1)]
    private User $user;

    #[ORM\ManyToMany(targetEntity: Organization::class, mappedBy: 'agents')]
    #[Groups(['agent.get'])]
    private Collection $organizations;

    #[ORM\ManyToMany(targetEntity: CulturalFunction::class)]
    #[ORM\JoinTable(name: 'agent_cultural_function')]
    #[Groups(['agent.get', 'agent.get.item'])]
    private Collection $culturalFunction;

    #[ORM\OneToMany(targetEntity: Opportunity::class, mappedBy: 'createdBy')]
    private Collection $opportunities;

    #[ORM\OneToMany(targetEntity: Seal::class, mappedBy: 'createdBy')]
    private Collection $seals;

    #[ORM\OneToMany(targetEntity: AgentAddress::class, mappedBy: 'owner', orphanRemoval: true)]
    #[Groups(['agent.get.item'])]
    private ?Collection $addresses = null;

    /**
     * @var array<string, string>
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['agent.get.item'])]
    private array $socialNetworks = [];

    #[ORM\Column]
    #[Groups(['agent.get'])]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    #[Groups(['agent.get'])]
    private ?DateTime $updatedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['agent.get'])]
    private ?DateTime $deletedAt = null;

    public function __construct()
    {
        $this->organizations = new ArrayCollection();
        $this->opportunities = new ArrayCollection();
        $this->seals = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->culturalFunction = new ArrayCollection();
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

    public function getShortBio(): string
    {
        return $this->shortBio;
    }

    public function setShortBio(string $shortBio): void
    {
        $this->shortBio = $shortBio;
    }

    public function getLongBio(): string
    {
        return $this->longBio;
    }

    public function setLongBio(string $longBio): void
    {
        $this->longBio = $longBio;
    }

    public function isCulture(): bool
    {
        return $this->culture;
    }

    public function setCulture(bool $culture): void
    {
        $this->culture = $culture;
    }

    public function isMain(): bool
    {
        return $this->main;
    }

    public function setMain(bool $main): void
    {
        $this->main = $main;
    }

    public function getExtraFields(): ?array
    {
        return $this->extraFields;
    }

    public function setExtraFields(?array $extraFields): void
    {
        $this->extraFields = $extraFields;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getOrganizations(): Collection
    {
        return $this->organizations;
    }

    public function setOrganizations(Collection $organizations): void
    {
        $this->organizations = $organizations;
    }

    public function addOrganization(Organization $organization): void
    {
        $this->organizations->add($organization);
    }

    public function getCulturalFunction(): Collection
    {
        return $this->culturalFunction;
    }

    public function setCulturalFunction(Collection $culturalFunction): void
    {
        $this->culturalFunction = $culturalFunction;
    }

    public function getOpportunities(): Collection
    {
        return $this->opportunities;
    }

    public function getSeals(): Collection
    {
        return $this->seals;
    }

    public function setSeals(Collection $seals): void
    {
        $this->seals = $seals;
    }

    public function addSeal(Seal $seal): void
    {
        $this->seals->add($seal);
    }

    public function getAddresses(): ?Collection
    {
        return $this->addresses;
    }

    public function setAddresses(Collection $addresses): void
    {
        $this->addresses = $addresses;
    }

    public function addAddress(AgentAddress $address): void
    {
        $this->addresses->add($address);
    }

    public function removeAddress(AgentAddress $address): void
    {
        $this->addresses->removeElement($address);
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

    public function createFromUser(User $user): Agent
    {
        $this->setUser($user);
        $this->setCreatedAt(new DateTimeImmutable());
        $this->setName($user->getName());
        $this->setImage($user->getImage());
        $this->setMain(true);
        $this->shortBio = '';
        $this->longBio = '';
        $this->culture = false;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id?->toRfc4122(),
            'name' => $this->name,
            'image' => $this->image,
            'shortBio' => $this->shortBio,
            'longBio' => $this->longBio,
            'culture' => $this->culture,
            'extraFields' => $this->extraFields,
            'organizations' => $this->organizations->map(fn ($organization) => $organization->getId()->toRfc4122())->toArray(),
            'culturalFunction' => $this->culturalFunction->map(fn ($culturalFunction) => $culturalFunction->getId()->toRfc4122())->toArray(),
            'socialNetworks' => $this->socialNetworks,
            'createdAt' => $this->createdAt->format(DateFormatHelper::DEFAULT_FORMAT),
            'updatedAt' => $this->updatedAt?->format(DateFormatHelper::DEFAULT_FORMAT),
            'deletedAt' => $this->deletedAt?->format(DateFormatHelper::DEFAULT_FORMAT),
        ];
    }
}
