<?php

declare(strict_types=1);

namespace App\Service\Interface;

use App\Entity\InscriptionOpportunity;
use App\Entity\Opportunity;
use Symfony\Component\Uid\Uuid;

interface InscriptionOpportunityServiceInterface
{
    public function create(Uuid $opportunity, array $inscriptionOpportunity): InscriptionOpportunity;

    public function get(Uuid $opportunity, Uuid $id);

    public function list(Uuid $opportunity, int $limit = 50): array;

    public function findUserInscriptionsWithDetails(): array;

    public function remove(Uuid $opportunity, Uuid $id): void;

    public function update(Uuid $opportunity, Uuid $identifier, array $inscriptionOpportunity): InscriptionOpportunity;

    public function findRecentByUser(Uuid $userId, int $limit = 4): array;

    public function findInscriptionWithDetails(Uuid $identifier): array;

    public function findOpportunityByOrganization(Uuid $organizationId): Opportunity;
}
