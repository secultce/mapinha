<?php

declare(strict_types=1);

namespace App\Exception\Invite;

use App\Exception\ResourceNotFoundException;

class InviteResourceNotFoundException extends ResourceNotFoundException
{
    protected const string RESOURCE = 'Invite';
}
