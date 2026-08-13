<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\InitiativeDto;
use App\Entity\Agent;
use App\Entity\Initiative;
use App\Exception\Initiative\InitiativeResourceNotFoundException;
use App\Exception\ValidatorException;
use App\Repository\Interface\InitiativeRepositoryInterface;
use App\Service\Interface\CityServiceInterface;
use App\Service\Interface\FileServiceInterface;
use App\Service\Interface\InitiativeServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class InitiativeService extends AbstractEntityService implements InitiativeServiceInterface
{
    private const string DIR_INITIATIVE_PROFILE = 'app.dir.initiative.profile';

    public function __construct(
        private FileServiceInterface $fileService,
        private ParameterBagInterface $parameterBag,
        private InitiativeRepositoryInterface $repository,
        private Security $security,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private EntityManagerInterface $entityManager,
        private CityServiceInterface $cityService,
    ) {
        parent::__construct(
            $this->security,
            $this->serializer,
            $this->validator,
            $this->entityManager,
            Initiative::class,
            $this->fileService,
            $this->parameterBag,
            self::DIR_INITIATIVE_PROFILE,
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

    public function create(array $initiative): Initiative
    {
        $initiative = $this->validateInput($initiative, InitiativeDto::class, InitiativeDto::CREATE);

        $initiativeObj = $this->serializer->denormalize($initiative, Initiative::class);

        return $this->repository->save($initiativeObj);
    }

    public function findBy(array $params = [], int $limit = 50): array
    {
        return $this->repository->findBy(
            [...$params, ...$this->getUserParams()],
            ['createdAt' => 'DESC'],
            $limit
        );
    }

    public function findOneBy(array $params): Initiative
    {
        return $this->repository->findOneBy(
            [...$params, ...$this->getDefaultParams()]
        );
    }

    public function get(Uuid $id): Initiative
    {
        $initiative = $this->repository->findOneBy([
            ...['id' => $id],
            ...$this->getDefaultParams(),
        ]);

        if (null === $initiative) {
            throw new InitiativeResourceNotFoundException();
        }

        return $initiative;
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
        $initiative = $this->get($id);
        $initiative->setDeletedAt(new DateTime());

        if ($initiative->getImage()) {
            $this->fileService->deleteFileByUrl($initiative->getImage());
        }

        $this->repository->save($initiative);
    }

    public function update(Uuid $id, array $initiative): Initiative
    {
        $initiativeFromDB = $this->get($id);

        $initiativeDto = $this->validateInput($initiative, InitiativeDto::class, InitiativeDto::UPDATE);

        $initiativeObj = $this->serializer->denormalize(
            $initiativeDto,
            Initiative::class,
            context: ['object_to_populate' => $initiativeFromDB]
        );

        $initiativeObj->setUpdatedAt(new DateTime());

        return $this->repository->save($initiativeObj);
    }

    public function updateImage(Uuid $id, UploadedFile $uploadedFile): Initiative
    {
        $initiative = $this->get($id);

        $initiativeDto = new InitiativeDto();
        $initiativeDto->image = $uploadedFile;

        $violations = $this->validator->validate($initiativeDto, groups: [InitiativeDto::UPDATE]);

        if ($violations->count() > 0) {
            throw new ValidatorException(violations: $violations);
        }

        if ($initiative->getImage()) {
            $this->fileService->deleteFileByUrl($initiative->getImage());
        }

        $uploadedImage = $this->fileService->uploadImage(
            $this->parameterBag->get(self::DIR_INITIATIVE_PROFILE),
            $uploadedFile
        );

        $initiative->setImage($this->fileService->urlOfImage($uploadedImage->getFilename()));

        $initiative->setUpdatedAt(new DateTime());

        $this->repository->save($initiative);

        return $initiative;
    }

    public function countByStatus(string $status): int
    {
        return $this->repository->countByStatus($status);
    }

    public function listFiltered(?string $region, ?string $state, ?string $cityId, ?string $status): array
    {
        $cityName = null;

        if ($cityId && $state) {
            $cityName = $this->cityService->get($cityId)->getName().'-'.$state;
        }

        return $this->repository->findByFilters($region, $state, $cityName, $status);
    }
}
