<?php

declare(strict_types=1);

namespace App\Document;

use App\Entity\User;

abstract class AbstractDocument
{
    public ?User $author = null;

    public function assignAuthor(User $user): void
    {
        $this->author = $user;
    }

    public function toArray(): array
    {
        return [
            'author' => $this->author?->toArray() ?? null,
            'id' => $this->getId(),
            'userId' => $this->getUserId(),
            'resourceId' => $this->getResourceId(),
            'title' => $this->getTitle(),
            'priority' => $this->getPriority(),
            'datetime' => $this->getDatetime(),
            'device' => $this->getDevice(),
            'platform' => $this->getPlatform(),
            'from' => $this->getFrom(),
            'to' => $this->getTo(),
        ];
    }
}
