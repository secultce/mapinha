<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\EntityAssociationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: EntityAssociationRepository::class)]
class EntityAssociation extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    private ?Uuid $id = null;

    #[ORM\Column(name: 'agent_id', type: UuidType::NAME)]
    private ?Agent $agent = null;

    #[ORM\Column(name: 'event_id', type: UuidType::NAME)]
    private ?Event $event = null;

    #[ORM\Column(name: 'initiative_id', type: UuidType::NAME)]
    private ?Initiative $initiative = null;

    #[ORM\Column(name: 'opportunity_id', type: UuidType::NAME)]
    private ?Opportunity $opportunity = null;

    #[ORM\Column(name: 'organization_id', type: UuidType::NAME)]
    private ?Organization $organization = null;

    #[ORM\OneToOne(targetEntity: Space::class, inversedBy: 'entityAssociation')]
    #[ORM\JoinColumn(name: 'space_id', referencedColumnName: 'id', nullable: false)]
    private ?Space $space = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups(['space.get'])]
    private bool $withAgent = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups(['space.get'])]
    private bool $withEvent = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups(['space.get'])]
    private bool $withInitiative = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups(['space.get'])]
    private bool $withOpportunity = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups(['space.get'])]
    private bool $withOrganization = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    #[Groups(['space.get'])]
    private bool $withSpace = false;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(?Uuid $id): void
    {
        $this->id = $id;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): void
    {
        $this->agent = $agent;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): void
    {
        $this->event = $event;
    }

    public function getInitiative(): ?Initiative
    {
        return $this->initiative;
    }

    public function setInitiative(?Initiative $initiative): void
    {
        $this->initiative = $initiative;
    }

    public function getOpportunity(): ?Opportunity
    {
        return $this->opportunity;
    }

    public function setOpportunity(?Opportunity $opportunity): void
    {
        $this->opportunity = $opportunity;
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): void
    {
        $this->organization = $organization;
    }

    public function getSpace(): ?Space
    {
        return $this->space;
    }

    public function setSpace(?Space $space): void
    {
        $this->space = $space;
    }

    public function withAgent(): bool
    {
        return $this->withAgent;
    }

    public function setWithAgent(bool $withAgent): void
    {
        $this->withAgent = $withAgent;
    }

    public function withEvent(): bool
    {
        return $this->withEvent;
    }

    public function setWithEvent(bool $withEvent): void
    {
        $this->withEvent = $withEvent;
    }

    public function withInitiative(): bool
    {
        return $this->withInitiative;
    }

    public function setWithInitiative(bool $withInitiative): void
    {
        $this->withInitiative = $withInitiative;
    }

    public function withOpportunity(): bool
    {
        return $this->withOpportunity;
    }

    public function setWithOpportunity(bool $withOpportunity): void
    {
        $this->withOpportunity = $withOpportunity;
    }

    public function withOrganization(): bool
    {
        return $this->withOrganization;
    }

    public function setWithOrganization(bool $withOrganization): void
    {
        $this->withOrganization = $withOrganization;
    }

    public function withSpace(): bool
    {
        return $this->withSpace;
    }

    public function setWithSpace(bool $withSpace): void
    {
        $this->withSpace = $withSpace;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->toRfc4122(),
            'agent' => $this->agent?->getId()->toRfc4122(),
            'event' => $this->event?->getId()->toRfc4122(),
            'initiative' => $this->initiative?->getId()->toRfc4122(),
            'opportunity' => $this->opportunity?->getId()->toRfc4122(),
            'organization' => $this->organization?->getId()->toRfc4122(),
            'space' => $this->space?->getId()->toRfc4122(),
            'withAgent' => $this->withAgent,
            'withEvent' => $this->withEvent,
            'withInitiative' => $this->withInitiative,
            'withOpportunity' => $this->withOpportunity,
            'withOrganization' => $this->withOrganization,
            'withSpace' => $this->withSpace,
        ];
    }
}
