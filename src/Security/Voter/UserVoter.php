<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\User;
use App\Enum\UserRolesEnum;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class UserVoter extends AbstractVoter
{
    protected array $actions = [
        'get',
        'edit',
    ];

    protected string $class = User::class;

    private array $allowedRoles = [
        UserRolesEnum::ROLE_ADMIN->value,
        UserRolesEnum::ROLE_MANAGER->value,
    ];

    /**
     * @param User $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        $isUserAdminOrManager = false === empty(array_intersect($user->getRoles(), $this->allowedRoles));

        return $isUserAdminOrManager || $user == $subject;
    }
}
