<?php

declare(strict_types=1);

namespace App\Event\Invite;

use App\Entity\Invite;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\Event;

class AcceptInviteEvent extends Event
{
    public const string TITLE = 'Invite was accepted';

    public function __construct(public readonly Invite $invite, public readonly User|UserInterface $user)
    {
    }
}
