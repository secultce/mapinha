<?php

declare(strict_types=1);

namespace App\Service\Interface;

use App\Entity\InscriptionEvent;
use Symfony\Component\Uid\Uuid;

interface InscriptionEventServiceInterface
{
    public function list(Uuid $event, int $limit = 50): array;

    public function get(Uuid $event, Uuid $id): InscriptionEvent;

    public function create(Uuid $event, array $inscriptionEvent): InscriptionEvent;

    public function remove(Uuid $event, Uuid $id): void;

    public function suspend(Uuid $event, Uuid $id): void;

    public function checkIn(Uuid $event, Uuid $id): void;

    public function update(Uuid $event, Uuid $identifier, array $inscriptionEvent): InscriptionEvent;

    public function listMyInscriptions(): array;

    public function getUserInscription(Uuid $eventId): ?InscriptionEvent;
}
