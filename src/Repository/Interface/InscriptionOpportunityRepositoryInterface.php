<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\InscriptionOpportunity;
use App\Entity\InscriptionPhase;
use Symfony\Component\Uid\Uuid;

interface InscriptionOpportunityRepositoryInterface
{
    public function create(InscriptionOpportunity $inscriptionOpportunity, ?InscriptionPhase $inscriptionPhase = null): InscriptionOpportunity;

    public function findOneInscriptionOpportunity(string $inscriptionId, string $opportunityId, array $agents);

    public function findInscriptionsByOpportunity(string $opportunityId, array $agents, int $limit);

    public function findRecentByUser(Uuid $userId, int $limit = 4): array;

    public function findUserInscriptionsWithDetails(Uuid $agentId): iterable;

    public function findInscriptionWithDetails(Uuid $identifier, array $userAgents): ?array;

    public function findInscriptionWithDetailsByOrganizations(Uuid $identifier): ?array;
}
