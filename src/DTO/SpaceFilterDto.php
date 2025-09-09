<?php

declare(strict_types=1);

namespace App\DTO;

use App\Validator\Constraints\NotNull;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class SpaceFilterDto
{
    #[Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if (true === isset($this->isDraft) && null === filter_var($this->isDraft, \FILTER_VALIDATE_BOOLEAN, \FILTER_NULL_ON_FAILURE)) {
            $context->buildViolation('This value should be of type boolean.')
                ->atPath('isDraft')
                ->addViolation();
        }
    }

    #[Sequentially([
        new NotNull(),
        new Type(Types::STRING),
    ])]
    public mixed $name;

    #[Sequentially([
        new NotNull(),
    ])]
    public mixed $isDraft;

    #[Sequentially([
        new NotNull(),
        new Uuid(),
    ])]
    public mixed $spaceType;

    #[Sequentially([
        new NotNull(),
        new Uuid(),
    ])]
    public mixed $accessibilities;

    #[Sequentially([
        new NotNull(),
        new Uuid(),
    ])]
    public mixed $activityAreas;

    #[Sequentially([
        new NotNull(),
        new Uuid(),
    ])]
    public mixed $tags;

    #[Sequentially([
        new NotNull(),
        new Uuid(),
    ])]
    public mixed $state;

    #[Sequentially([
        new NotNull(),
        new Type(Types::STRING),
    ])]
    public mixed $address;
}
