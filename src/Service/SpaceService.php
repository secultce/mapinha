<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\SpaceDto;
use App\DTO\SpaceFilterDto;
use App\Entity\Agent;
use App\Entity\Space;
use App\Enum\EntityEnum;
use App\Exception\Space\SpaceResourceNotFoundException;
use App\Exception\ValidatorException;
use App\Repository\Interface\SpaceRepositoryInterface;
use App\Service\Interface\FileServiceInterface;
use App\Service\Interface\SpaceServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class SpaceService extends AbstractEntityService implements SpaceServiceInterface
{
    private const string DIR_SPACE_PROFILE = 'app.dir.space.profile';

    public function __construct(
        private FileServiceInterface $fileService,
        private ParameterBagInterface $parameterBag,
        private SpaceRepositoryInterface $repository,
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
            Space::class,
            $this->fileService,
            $this->parameterBag,
            self::DIR_SPACE_PROFILE,
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

    public function create(array $space): Space
    {
        $space = $this->validateInput($space, SpaceDto::class, SpaceDto::CREATE);

        $spaceObj = $this->serializer->denormalize($space, Space::class);

        return $this->repository->save($spaceObj);
    }

    public function findBy(array $params = [], int $limit = 50): array
    {
        return $this->repository->findBy(
            [...$params, ...$this->getUserParams()],
            ['createdAt' => 'DESC'],
            $limit
        );
    }

    public function findOneBy(array $params): Space
    {
        return $this->repository->findOneBy(
            [...$params, ...$this->getDefaultParams()]
        );
    }

    public function get(Uuid $id): Space
    {
        $space = $this->repository->findOneBy([
            ...['id' => $id],
            ...$this->getDefaultParams(),
        ]);

        if (null === $space) {
            throw new SpaceResourceNotFoundException();
        }

        return $space;
    }

    public function list(int $limit = 50, array $params = [], string $order = 'DESC'): array
    {
        $filters = $this->validateInput($params, SpaceFilterDto::class);

        if (true === array_key_exists('associationWith', $params)) {
            return $this->repository->findByNameAndEntityAssociation(
                name: $params['name'] ?? null,
                entityAssociation: EntityEnum::fromName($params['associationWith']),
                limit: $limit
            );
        }

        return $this->repository->findByFilters(
            filters: $filters,
            orderBy: ['createdAt' => $order],
            limit: $limit
        );
    }

    public function remove(Uuid $id): void
    {
        $space = $this->repository->findOneBy(
            [...['id' => $id], ...$this->getUserParams()]
        );

        if (null === $space) {
            throw new SpaceResourceNotFoundException();
        }

        $space->setDeletedAt(new DateTime());

        if ($space->getImage()) {
            $this->fileService->deleteFileByUrl($space->getImage());
        }

        $this->repository->save($space);
    }

    public function update(Uuid $identifier, array $space): Space
    {
        $spaceFromDB = $this->get($identifier);

        $spaceDto = $this->validateInput($space, SpaceDto::class, SpaceDto::UPDATE);

        $spaceObj = $this->serializer->denormalize($spaceDto, Space::class, context: [
            'object_to_populate' => $spaceFromDB,
        ]);

        $spaceObj->setUpdatedAt(new DateTime());

        return $this->repository->save($spaceObj);
    }

    public function updateImage(Uuid $id, UploadedFile $uploadedFile): Space
    {
        $space = $this->get($id);

        $spaceDto = new SpaceDto();
        $spaceDto->image = $uploadedFile;

        $violations = $this->validator->validate($spaceDto, groups: [SpaceDto::UPDATE]);

        if ($violations->count() > 0) {
            throw new ValidatorException(violations: $violations);
        }

        if ($space->getImage()) {
            $this->fileService->deleteFileByUrl($space->getImage());
        }

        $uploadedImage = $this->fileService->uploadImage(
            $this->parameterBag->get(self::DIR_SPACE_PROFILE),
            $uploadedFile
        );

        $space->setImage($this->fileService->urlOfImage($uploadedImage->getFilename()));

        $space->setUpdatedAt(new DateTime());

        $this->repository->save($space);

        return $space;
    }

    public function togglePublish(Uuid $id): void
    {
        $space = $this->get($id);
        $space->setIsDraft(!$space->isDraft());

        $this->repository->save($space);
    }
}
