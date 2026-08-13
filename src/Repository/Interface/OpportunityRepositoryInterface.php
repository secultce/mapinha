<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\Opportunity;
use DateTime;

interface OpportunityRepositoryInterface
{
    public function save(Opportunity $opportunity): Opportunity;

    public function countRecentOpportunities(DateTime $startDate): int;

    public function countOpenedOpportunities(): int;

    public function countFinishedOpportunities(): int;
}
