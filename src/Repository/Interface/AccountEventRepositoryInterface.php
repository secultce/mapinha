<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\AccountEvent;

interface AccountEventRepositoryInterface
{
    public function save(AccountEvent $accountEvent): AccountEvent;
}
