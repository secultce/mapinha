<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Initiative;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class InitiativeVoter extends AbstractVoter
{
    protected array $actions = [
        'get',
        'get-file',
        'edit',
        'remove',
    ];

    protected string $class = Initiative::class;

    /**
     * @param Initiative $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        return $this->isUserAdmin($user) || $user == $subject->getCreatedBy()->getUser();
    }
}
