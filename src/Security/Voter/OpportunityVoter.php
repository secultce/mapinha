<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Opportunity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class OpportunityVoter extends AbstractVoter
{
    protected array $actions = [
        'get',
        'edit',
        'remove',
    ];

    protected string $class = Opportunity::class;

    /**
     * @param Opportunity $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        return $this->isUserAdmin($user) || $user == $subject->getCreatedBy()->getUser();
    }
}
