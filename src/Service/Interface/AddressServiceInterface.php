<?php

declare(strict_types=1);

namespace App\Service\Interface;

use App\Entity\Agent;

interface AddressServiceInterface
{
    public function create(Agent $agent, array $addressData): void;
}
