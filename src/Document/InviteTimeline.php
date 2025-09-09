<?php

declare(strict_types=1);

namespace App\Document;

use DateTime;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\Types\Type;

#[ODM\Document(collection: 'invite_timeline')]
class InviteTimeline extends AbstractDocument
{
    #[ODM\Id]
    private ?string $id = null;

    #[ODM\Field]
    private ?string $userId = null;

    #[ODM\Field]
    private string $organizationId;

    #[ODM\Field]
    private string $resourceId;

    #[ODM\Field]
    private string $title;

    #[ODM\Field]
    private int $priority;

    #[ODM\Field]
    private DateTime $datetime;

    #[ODM\Field]
    private string $device;

    #[ODM\Field]
    private string $platform;

    #[ODM\Field(type: Type::HASH)]
    private array $data;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    public function getOrganizationId(): string
    {
        return $this->organizationId;
    }

    public function setOrganizationId(string $organizationId): void
    {
        $this->organizationId = $organizationId;
    }

    public function getResourceId(): string
    {
        return $this->resourceId;
    }

    public function setResourceId(string $resourceId): void
    {
        $this->resourceId = $resourceId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }

    public function getDatetime(): DateTime
    {
        return $this->datetime;
    }

    public function setDatetime(DateTime $datetime): void
    {
        $this->datetime = $datetime;
    }

    public function getDevice(): string
    {
        return $this->device;
    }

    public function setDevice(string $device): void
    {
        $this->device = $device;
    }

    public function getPlatform(): string
    {
        return $this->platform;
    }

    public function setPlatform(string $platform): void
    {
        $this->platform = $platform;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function toArray(): array
    {
        return [
            'author' => $this->author?->toArray() ?? null,
            'id' => $this->getId(),
            'userId' => $this->getUserId(),
            'organizationId' => $this->getOrganizationId(),
            'resourceId' => $this->getResourceId(),
            'title' => $this->getTitle(),
            'priority' => $this->getPriority(),
            'datetime' => $this->getDatetime(),
            'device' => $this->getDevice(),
            'platform' => $this->getPlatform(),
            'data' => $this->getData(),
        ];
    }
}
