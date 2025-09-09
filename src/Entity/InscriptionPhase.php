<?php

declare(strict_types=1);

namespace App\Entity;

use App\Helper\DateFormatHelper;
use App\Repository\InscriptionPhaseRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: InscriptionPhaseRepository::class)]
class InscriptionPhase
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME)]
    #[Groups(['inscription-phase.get'])]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(targetEntity: Agent::class)]
    #[ORM\JoinColumn(name: 'agent_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['inscription-phase.get'])]
    private ?Agent $agent = null;

    #[ORM\ManyToOne(targetEntity: Organization::class)]
    #[ORM\JoinColumn(name: 'organization_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['inscription-phase.get'])]
    private ?Organization $organization = null;

    #[ORM\ManyToOne(targetEntity: Phase::class)]
    #[ORM\JoinColumn(name: 'phase_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(['inscription-phase.get'])]
    private ?Phase $phase = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['inscription-phase.get'])]
    private ?int $status = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['inscription-phase.get'])]
    private ?array $extraFields = null;

    #[ORM\Column]
    #[Groups('inscription-phase.get')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    #[Groups('inscription-phase.get')]
    private ?DateTime $updatedAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups('inscription-phase.get')]
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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getAgent(): Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): void
    {
        $this->agent = $agent;
    }

    public function getPhase(): ?Phase
    {
        return $this->phase;
    }

    public function setPhase(?Phase $phase): void
    {
        $this->phase = $phase;
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

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): void
    {
        $this->organization = $organization;
    }

    public function getExtraFields(): ?array
    {
        return $this->extraFields;
    }

    public function setExtraFields(?array $extraFields): void
    {
        $this->extraFields = $extraFields;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id?->toRfc4122(),
            'agent' => $this->agent?->getId(),
            'organization' => $this->organization?->getId(),
            'phase' => $this->getPhase()->getId(),
            'status' => $this->status,
            'extraFields' => $this->extraFields,
            'createdAt' => $this->createdAt->format(DateFormatHelper::DEFAULT_FORMAT),
            'updatedAt' => $this->updatedAt?->format(DateFormatHelper::DEFAULT_FORMAT),
            'deletedAt' => $this->deletedAt?->format(DateFormatHelper::DEFAULT_FORMAT),
        ];
    }
}
