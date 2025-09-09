<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Enum\UserRolesEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractVoter extends Voter
{
    protected array $actions = [];

    protected string $class = '';

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (false === in_array($attribute, $this->actions)) {
            return false;
        }

        if (true === is_object($subject) && false === $subject instanceof $this->class) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return true;
    }

    protected function isUserAdmin(UserInterface $user): bool
    {
        return in_array(UserRolesEnum::ROLE_ADMIN->value, $user->getRoles());
    }
}
