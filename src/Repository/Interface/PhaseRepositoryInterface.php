<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\Opportunity;
use App\Entity\Phase;
use DateTime;

interface PhaseRepositoryInterface
{
    public function save(Phase $phase): Phase;

    public function findCurrentPhase(DateTime $currentDate, Opportunity $opportunity): ?Phase;
}
