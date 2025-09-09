<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Agent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class AgentVoter extends AbstractVoter
{
    protected array $actions = [
        'get',
        'remove',
        'edit',
    ];

    protected string $class = Agent::class;

    /**
     * @param Agent $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        return $this->isUserAdmin($user) || $user == $subject->getUser();
    }
}
