<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\OpportunityDto;
use App\Entity\Agent;
use App\Entity\Opportunity;
use App\Exception\Opportunity\OpportunityResourceNotFoundException;
use App\Repository\Interface\OpportunityRepositoryInterface;
use App\Service\Interface\FileServiceInterface;
use App\Service\Interface\OpportunityServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class OpportunityService extends AbstractEntityService implements OpportunityServiceInterface
{
    private const string DIR_OPPORTUNITY_PROFILE = 'app.dir.opportunity.profile';
    private const string DIR_OPPORTUNITY_COVER = 'app.dir.opportunity.cover';

    public function __construct(
        private FileServiceInterface $fileService,
        private ParameterBagInterface $parameterBag,
        private OpportunityRepositoryInterface $repository,
        private Security $security,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct(
            $this->security,
            $this->serializer,
            $this->validator,
            $this->entityManager,
            Opportunity::class,
            $this->fileService,
            $this->parameterBag,
            self::DIR_OPPORTUNITY_PROFILE,
        );
    }

    public function count(?Agent $createdBy = null): int
    {
        $criteria = $this->getDefaultParams();

        if ($createdBy) {
            $criteria['createdBy'] = $createdBy;
        }

        return $this->repository->count($criteria);
    }

    public function countRecentOpportunities(int $days = 7): int
    {
        $startDate = new DateTime("-{$days} days");

        return $this->repository->countRecentOpportunities($startDate);
    }

    public function countOpenedOpportunities(): int
    {
        return $this->repository->countOpenedOpportunities();
    }

    public function countFinishedOpportunities(): int
    {
        return $this->repository->countFinishedOpportunities();
    }

    public function create(array $opportunity): Opportunity
    {
        $opportunity = $this->validateInput($opportunity, OpportunityDto::class, OpportunityDto::CREATE);

        $opportunityObj = $this->serializer->denormalize($opportunity, Opportunity::class);

        return $this->repository->save($opportunityObj);
    }

    public function findBy(array $params = [], int $limit = 50): array
    {
        return $this->repository->findBy(
            [...$params, ...$this->getUserParams()],
            ['createdAt' => 'DESC'],
            $limit
        );
    }

    public function findOneBy(array $params): ?Opportunity
    {
        return $this->repository->findOneBy(
            [...$params, ...$this->getDefaultParams()]
        );
    }

    public function get(Uuid $id): Opportunity
    {
        $opportunity = $this->repository->findOneBy([
            ...['id' => $id],
            ...$this->getDefaultParams(),
        ]);

        if (null === $opportunity) {
            throw new OpportunityResourceNotFoundException();
        }

        return $opportunity;
    }

    public function list(int $limit = 50, array $params = [], string $order = 'DESC'): array
    {
        return $this->repository->findBy(
            [...$params, ...$this->getDefaultParams()],
            ['createdAt' => $order],
            $limit
        );
    }

    public function remove(Uuid $id): void
    {
        $opportunity = $this->repository->findOneBy(
            [...['id' => $id], ...$this->getUserParams()]
        );

        if (null === $opportunity) {
            throw new OpportunityResourceNotFoundException();
        }

        $opportunity->setDeletedAt(new DateTime());

        if ($opportunity->getImage()) {
            $this->fileService->deleteFileByUrl($opportunity->getImage());
        }

        $this->repository->save($opportunity);
    }

    public function update(Uuid $id, array $opportunity): Opportunity
    {
        $opportunityFromDB = $this->get($id);

        $opportunityDto = $this->validateInput($opportunity, OpportunityDto::class, OpportunityDto::UPDATE);

        $opportunityObj = $this->serializer->denormalize($opportunityDto, Opportunity::class, context: [
            'object_to_populate' => $opportunityFromDB,
        ]);

        $opportunityObj->setUpdatedAt(new DateTime());

        return $this->repository->save($opportunityObj);
    }

    public function updateImage(Uuid $id, UploadedFile $uploadedFile): Opportunity
    {
        return $this->processFileUpload(
            id: $id,
            uploadedFile: $uploadedFile,
            dtoClass: OpportunityDto::class,
            dtoProperty: 'profileImage',
            directoryParam: self::DIR_OPPORTUNITY_PROFILE,
            getterMethod: 'getImage',
            setterMethod: 'setImage',
            validationGroups: [OpportunityDto::UPDATE]
        );
    }

    public function updateCoverImage(Uuid $id, UploadedFile $uploadedFile): Opportunity
    {
        return $this->processFileUpload(
            id: $id,
            uploadedFile: $uploadedFile,
            dtoClass: OpportunityDto::class,
            dtoProperty: 'coverImage',
            directoryParam: self::DIR_OPPORTUNITY_COVER,
            getterMethod: 'getCoverImage',
            setterMethod: 'setCoverImage',
            validationGroups: [OpportunityDto::UPDATE]
        );
    }
}
