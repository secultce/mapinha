<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\InscriptionEvent;

interface InscriptionEventRepositoryInterface
{
    public function findMyInscriptions(string $agent, int $limit): array;

    public function findInscriptionsByEvent(string $eventId, int $limit): array;

    public function findOneInscriptionEvent(string $inscriptionId, string $eventId): ?InscriptionEvent;

    public function save(InscriptionEvent $inscriptionEvent): InscriptionEvent;
}
