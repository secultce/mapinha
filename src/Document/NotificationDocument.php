<?php

declare(strict_types=1);

namespace App\Document;

use DateTime;
use DateTimeInterface;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'notifications')]
class NotificationDocument
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\Field(type: 'string')]
    private string $sender;

    #[ODM\Field(type: 'string')]
    private string $target;

    #[ODM\Field(type: 'bool')]
    private bool $visited = false;

    #[ODM\Field(type: 'string', nullable: true)]
    private ?string $context = null;

    #[ODM\Field(type: 'string')]
    private string $content;

    #[ODM\Field(type: 'date')]
    private DateTime $createdAt;

    #[ODM\Field(type: 'date', nullable: true)]
    private ?DateTime $visitedAt = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getSender(): string
    {
        return $this->sender;
    }

    public function setSender(string $sender): void
    {
        $this->sender = $sender;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function setTarget(string $target): void
    {
        $this->target = $target;
    }

    public function isVisited(): bool
    {
        return $this->visited;
    }

    public function setVisited(bool $visited): void
    {
        $this->visited = $visited;
    }

    public function markAsVisited(): void
    {
        $this->visited = true;
        $this->visitedAt = new DateTime();
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(?string $context): void
    {
        $this->context = $context;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getVisitedAt(): ?DateTime
    {
        return $this->visitedAt;
    }

    public function setVisitedAt(?DateTime $visitedAt): void
    {
        $this->visitedAt = $visitedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'sender' => $this->sender,
            'target' => $this->target,
            'visited' => $this->visited,
            'context' => $this->context,
            'content' => $this->content,
            'createdAt' => $this->createdAt->format(DateTimeInterface::ATOM),
            'visitedAt' => $this->visitedAt?->format(DateTimeInterface::ATOM),
        ];
    }
}
