<?php

declare(strict_types=1);

namespace App\Repository\Interface;

use App\Entity\Invite;

interface InviteRepositoryInterface
{
    public function save(Invite $invite): Invite;
}
