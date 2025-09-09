<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\PhaseDto;
use App\Entity\Phase;
use App\Environment\ConfigEnvironment;
use App\Exception\Phase\PhaseResourceNotFoundException;
use App\Repository\Interface\PhaseRepositoryInterface;
use App\Service\Interface\PhaseServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class PhaseService extends AbstractEntityService implements PhaseServiceInterface
{
    public function __construct(
        private Security $security,
        private PhaseRepositoryInterface $repository,
        private EntityManagerInterface $entityManager,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private ConfigEnvironment $configEnvironment,
        private OpportunityService $opportunityService,
    ) {
        parent::__construct($this->security, $serializer, $validator);
    }

    public function create(Uuid $opportunity, array $phase): Phase
    {
        $phase['opportunity'] = $opportunity->toRfc4122();

        $phase = $this->validateInput($phase, PhaseDto::class, PhaseDto::CREATE);

        $phaseObj = $this->serializer->denormalize($phase, Phase::class);

        $phaseObj->setCreatedBy(
            $this->getAgentsFromLoggedUser()[0]
        );
        $phaseObj->setSequence($this->findSequenceNumber(Phase::class, $opportunity));

        return $this->repository->save($phaseObj);
    }

    public function get(Uuid $opportunity, Uuid $id): Phase
    {
        $phase = $this->repository->findOneBy(['id' => $id, 'opportunity' => $opportunity]);

        if (null === $phase) {
            throw new PhaseResourceNotFoundException();
        }

        return $phase;
    }

    public function list(Uuid $opportunity, int $limit = 50): array
    {
        return $this->repository->findBy(
            ['opportunity' => $opportunity],
            ['sequence' => 'ASC'],
            $limit
        );
    }

    public function remove(Uuid $opportunity, Uuid $id): void
    {
        $phase = $this->repository->findOneBy(
            [...['id' => $id, 'opportunity' => $opportunity], ...$this->getUserParams()]
        );

        if (null === $phase) {
            throw new PhaseResourceNotFoundException();
        }

        $phase->setDeletedAt(new DateTime());

        $this->repository->save($phase);
    }

    public function update(Uuid $opportunity, Uuid $identifier, array $phase): Phase
    {
        $phase['opportunity'] = $opportunity->toRfc4122();

        $phaseFromDB = $this->get($opportunity, $identifier);

        $phaseDto = $this->validateInput($phase, PhaseDto::class, PhaseDto::UPDATE);

        $phaseObj = $this->serializer->denormalize($phaseDto, Phase::class, context: [
            'object_to_populate' => $phaseFromDB,
        ]);

        $phaseObj->setUpdatedAt(new DateTime());

        return $this->repository->save($phaseObj);
    }

    private function findSequenceNumber(string $entityName, Uuid $opportunity): int
    {
        return $this->entityManager->createQueryBuilder()
            ->select('(coalesce(max(entity.sequence), 0) + 1)')
            ->from($entityName, 'entity')
            ->where('entity.opportunity = :opportunity')
            ->setParameter('opportunity', $opportunity)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function isPhaseActive(Uuid $opportunityId): bool
    {
        $opportunity = $this->opportunityService->get($opportunityId);

        $currentPhase = $this->repository->findCurrentPhase(new DateTime(), $opportunity);

        if (null === $currentPhase) {
            return false;
        }

        $currentDate = new DateTime();

        return $currentPhase->getStartDate() <= $currentDate && $currentPhase->getEndDate() >= $currentDate;
    }
}
