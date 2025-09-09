<?php

declare(strict_types=1);

namespace App\DTO;

use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class EventFilterDto
{
    #[Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if (true === isset($this->draft) && null === filter_var($this->draft, \FILTER_VALIDATE_BOOLEAN, \FILTER_NULL_ON_FAILURE)) {
            $context->buildViolation('This value should be of type boolean.')
                ->atPath('draft')
                ->addViolation();
        }
        if (true === isset($this->free) && null === filter_var($this->free, \FILTER_VALIDATE_BOOLEAN, \FILTER_NULL_ON_FAILURE)) {
            $context->buildViolation('This value should be of type boolean.')
                ->atPath('free')
                ->addViolation();
        }
    }

    #[Sequentially([
        new Type(Types::STRING),
    ])]
    public mixed $name;

    #[Sequentially([
        new Type(Types::STRING),
    ])]
    public mixed $subtitle;

    #[Sequentially([
        new Type(Types::STRING),
    ])]
    public mixed $shortDescription;

    #[Sequentially([
        new Type(Types::STRING),
    ])]
    public mixed $longDescription;

    #[Sequentially([
        new Type(Types::STRING),
    ])]
    public mixed $site;

    #[Sequentially([
        new Type(Types::STRING),
    ])]
    public mixed $phoneNumber;

    #[Sequentially([
        new Type(Types::STRING),
    ])]
    public mixed $coverImage;

    #[Sequentially([
        new Uuid(),
    ])]
    public mixed $agentGroup;

    #[Sequentially([
        new Uuid(),
    ])]
    public mixed $space;

    #[Sequentially([
        new Uuid(),
    ])]
    public mixed $initiative;

    #[Sequentially([
        new Uuid(),
    ])]
    public mixed $parent;

    #[Sequentially([
        new Uuid(),
    ])]
    public mixed $createdBy;

    #[Sequentially([
        new Uuid(),
    ])]
    public mixed $activityAreas;

    #[Sequentially([
        new Uuid(),
    ])]
    public mixed $tags;

    #[Sequentially([
        new Type(Types::INTEGER),
    ])]
    public mixed $type;

    #[Sequentially([
        new Type(Types::DATETIME_MUTABLE),
    ])]
    public mixed $endDate;

    #[Sequentially([
        new Type(Types::INTEGER),
    ])]
    public mixed $maxCapacity;

    #[Sequentially([
        new Type(Types::INTEGER),
    ])]
    public mixed $accessibleAudio;

    #[Sequentially([
        new Type(Types::INTEGER),
    ])]
    public mixed $accessibleLibras;

    #[Sequentially([
        new Type(Types::BOOLEAN),
    ])]
    public mixed $free;

    #[Sequentially([
        new Type(Types::BOOLEAN),
    ])]
    public mixed $draft;

    #[Sequentially([
        new Type(Types::STRING),
    ])]
    public mixed $extraFields;
}
