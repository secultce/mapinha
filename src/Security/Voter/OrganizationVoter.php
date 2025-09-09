<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Organization;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class OrganizationVoter extends AbstractVoter
{
    protected array $actions = [
        'get',
        'get_form',
        'edit',
    ];

    protected string $class = Organization::class;

    /**
     * @param Organization $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        return $this->isUserAdmin($user) || $user == $subject->getOwner()->getUser();
    }
}
