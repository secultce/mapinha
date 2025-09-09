<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\OpportunityDto;
use App\Entity\Agent;
use App\Entity\Opportunity;
use App\Exception\Opportunity\OpportunityResourceNotFoundException;
use App\Exception\ValidatorException;
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
        $opportunity = $this->get($id);

        $opportunityDto = new OpportunityDto();
        $opportunityDto->image = $uploadedFile;

        $violations = $this->validator->validate($opportunityDto, groups: [OpportunityDto::UPDATE]);

        if ($violations->count() > 0) {
            throw new ValidatorException(violations: $violations);
        }

        if ($opportunity->getImage()) {
            $this->fileService->deleteFileByUrl($opportunity->getImage());
        }

        $uploadedImage = $this->fileService->uploadImage(
            $this->parameterBag->get(self::DIR_OPPORTUNITY_PROFILE),
            $uploadedFile
        );

        $relativePath = '/uploads'.$this->parameterBag->get(self::DIR_OPPORTUNITY_PROFILE).'/'.$uploadedImage->getFilename();
        $opportunity->setImage($relativePath);

        $opportunity->setUpdatedAt(new DateTime());

        $this->repository->save($opportunity);

        return $opportunity;
    }

    public function updateCoverImage(Uuid $id, UploadedFile $coverImage): Opportunity
    {
        $opportunity = $this->get($id);

        $opportunityDto = new OpportunityDto();
        $opportunityDto->image = $coverImage;

        $violations = $this->validator->validate($opportunityDto, groups: [OpportunityDto::UPDATE]);

        if ($violations->count() > 0) {
            throw new ValidatorException(violations: $violations);
        }

        $uploadedImage = $this->fileService->uploadImage(
            $this->parameterBag->get('app.dir.opportunity.cover'),
            $coverImage,
        );

        $extraFields = $opportunity->getExtraFields();
        $extraFields['coverImage'] = $this->fileService->getFileUrl($uploadedImage->getPathname());
        $opportunity->setUpdatedAt(new DateTime());
        $opportunity->setExtraFields($extraFields);

        $this->repository->save($opportunity);

        return $opportunity;
    }
}
