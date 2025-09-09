<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\EventType;

interface EventTypeRepositoryInterface
{
    public function save(EventType $eventType): EventType;
}
