<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\InscriptionOpportunityDto;
use App\Entity\InscriptionOpportunity;
use App\Entity\InscriptionPhase;
use App\Entity\Opportunity;
use App\Entity\Phase;
use App\Enum\InscriptionPhaseStatusEnum;
use App\Exception\InscriptionOpportunity\AlreadyInscriptionOpportunityException;
use App\Exception\InscriptionOpportunity\InscriptionOpportunityResourceNotFoundException;
use App\Exception\UnauthorizedException;
use App\Repository\Interface\InscriptionOpportunityRepositoryInterface;
use App\Service\Interface\InscriptionOpportunityServiceInterface;
use App\Service\Interface\OpportunityServiceInterface;
use App\Service\Interface\PhaseServiceInterface;
use DateTime;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class InscriptionOpportunityService extends AbstractEntityService implements InscriptionOpportunityServiceInterface
{
    public function __construct(
        private Security $security,
        private InscriptionOpportunityRepositoryInterface $repository,
        private OpportunityServiceInterface $opportunityService,
        private PhaseServiceInterface $phaseService,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {
        parent::__construct($security, $serializer, $validator);
    }

    public function create(Uuid $opportunity, array $inscriptionOpportunity): InscriptionOpportunity
    {
        $inscriptionOpportunity['opportunity'] = $opportunity->toRfc4122();

        $inscriptionOpportunityDto = $this->validateInput($inscriptionOpportunity, InscriptionOpportunityDto::class, InscriptionOpportunityDto::CREATE);

        $inscriptionOpportunityObj = $this->serializer->denormalize($inscriptionOpportunityDto, InscriptionOpportunity::class);

        $firstPhase = current($this->phaseService->list($inscriptionOpportunityObj->getOpportunity()->getId(), 1));

        $inscriptionPhase = null;
        if (false !== $firstPhase) {
            $inscriptionPhase = new InscriptionPhase();
            $inscriptionPhase->setId(Uuid::v4());
            $inscriptionPhase->setAgent($inscriptionOpportunityObj->getAgent());
            $inscriptionPhase->setPhase($firstPhase);
            $inscriptionPhase->setStatus(InscriptionPhaseStatusEnum::ACTIVE->value);
        }

        try {
            return $this->repository->create($inscriptionOpportunityObj, $inscriptionPhase);
        } catch (UniqueConstraintViolationException) {
            throw new AlreadyInscriptionOpportunityException();
        }
    }

    public function get(Uuid $opportunity, Uuid $id): InscriptionOpportunity
    {
        $inscriptionOpportunity = $this->repository->findOneInscriptionOpportunity($id->toRfc4122(), $opportunity->toRfc4122(), $this->security->getUser()->getAgents()->getValues());

        if (null === $inscriptionOpportunity) {
            throw new InscriptionOpportunityResourceNotFoundException();
        }

        return $inscriptionOpportunity;
    }

    public function list(Uuid $opportunity, int $limit = 50): array
    {
        return $this->repository->findInscriptionsByOpportunity(
            $opportunity->toRfc4122(),
            $this->security->getUser()->getAgents()->getValues(),
            $limit
        );
    }

    public function findUserInscriptionsWithDetails(): array
    {
        $userParams = $this->getUserParams()['agent'][0];
        $agent = $userParams->getId();

        $inscriptionsWithDetails = $this->repository->findUserInscriptionsWithDetails($agent);

        $phaseData = [];

        foreach ($inscriptionsWithDetails as $inscription) {
            if ($inscription instanceof Phase) {
                $phaseData[] = [
                    'opportunity' => $inscription->getOpportunity()->getName(),
                    'phase' => $inscription->getName(),
                    'phaseDescription' => $inscription->getDescription(),
                    'startDate' => $inscription->getStartDate(),
                    'endDate' => $inscription->getEndDate(),
                ];
            }
        }

        return $phaseData;
    }

    public function remove(Uuid $opportunity, Uuid $id): void
    {
        $inscriptionOpportunity = $this->repository->findOneBy(
            [...['id' => $id, 'opportunity' => $opportunity], ...$this->getUserParams()]
        );

        if (null === $inscriptionOpportunity) {
            throw new InscriptionOpportunityResourceNotFoundException();
        }

        $inscriptionOpportunity->setDeletedAt(new DateTime());

        $this->repository->save($inscriptionOpportunity);
    }

    public function update(Uuid $opportunity, Uuid $identifier, array $inscriptionOpportunity): InscriptionOpportunity
    {
        $inscriptionOpportunity['opportunity'] = $opportunity->toRfc4122();

        $inscriptionOpportunityFromDB = $this->repository->findOneBy(
            [...['id' => $identifier, 'opportunity' => $opportunity], ...$this->getUserParams()]
        );

        if (null === $inscriptionOpportunityFromDB) {
            throw new InscriptionOpportunityResourceNotFoundException();
        }

        $inscriptionOpportunityDto = $this->validateInput($inscriptionOpportunity, InscriptionOpportunityDto::class, InscriptionOpportunityDto::UPDATE);

        $agent = $inscriptionOpportunity['agent'] ?? $inscriptionOpportunityFromDB->getAgent()->getId()->toRfc4122();

        $this->opportunityShouldNotBelongsToAgent($opportunity, $agent);

        $inscriptionOpportunityObj = $this->serializer->denormalize($inscriptionOpportunityDto, InscriptionOpportunity::class, context: [
            'object_to_populate' => $inscriptionOpportunityFromDB,
        ]);

        $inscriptionOpportunityObj->setUpdatedAt(new DateTime());

        return $this->repository->save($inscriptionOpportunityObj);
    }

    private function opportunityShouldNotBelongsToAgent(Uuid $opportunity, string $agent): void
    {
        $opportunity = $this->opportunityService->get($opportunity);

        if ($opportunity->getCreatedBy()->getId()->toRfc4122() === $agent) {
            throw new UnauthorizedException();
        }
    }

    public function getUserParams(): array
    {
        $params = parent::DEFAULT_FILTERS;

        if (null !== $this->security->getUser()) {
            $agents = $this->security->getUser()->getAgents()->getValues();

            $params['agent'] = $agents;
        }

        return $params;
    }

    public function findRecentByUser(Uuid $userId, int $limit = 4): array
    {
        return $this->repository->findRecentByUser($userId, $limit);
    }

    public function findInscriptionWithDetails(Uuid $identifier): array
    {
        $inscription = $this->repository->findInscriptionWithDetailsByOrganizations(
            $identifier
        );

        if (null === $inscription) {
            throw new InscriptionOpportunityResourceNotFoundException();
        }

        return $inscription;
    }

    public function findOpportunityByOrganization(Uuid $organizationId): Opportunity
    {
        $inscriptionOpportunity = $this->repository->findOneBy([
            'organization' => $organizationId,
        ]);

        if (null === $inscriptionOpportunity) {
            throw new InscriptionOpportunityResourceNotFoundException();
        }

        return $inscriptionOpportunity->getOpportunity();
    }
}
