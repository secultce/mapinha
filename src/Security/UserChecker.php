<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Enum\UserStatusEnum;
use App\Exception\AccountEvent\AccountNotActivatedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (UserStatusEnum::AWAITING_CONFIRMATION->value === $user->getStatus()) {
            throw new AccountNotActivatedException();
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }
}
