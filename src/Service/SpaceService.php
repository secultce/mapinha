<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\SpaceDto;
use App\DTO\SpaceFilterDto;
use App\Entity\Agent;
use App\Entity\Space;
use App\Entity\SpaceAddress;
use App\Enum\EntityEnum;
use App\Exception\Space\SpaceResourceNotFoundException;
use App\Repository\Interface\SpaceRepositoryInterface;
use App\Service\Interface\CityServiceInterface;
use App\Service\Interface\FileServiceInterface;
use App\Service\Interface\PhotoServiceInterface;
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
    private const string DIR_SPACE_COVER = 'app.dir.space.cover';
    private const string DIR_SPACE_PORTFOLIO = 'app.dir.space.portfolio';

    public function __construct(
        private FileServiceInterface $fileService,
        private ParameterBagInterface $parameterBag,
        private SpaceRepositoryInterface $repository,
        private CityServiceInterface $cityService,
        private Security $security,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private EntityManagerInterface $entityManager,
        private PhotoServiceInterface $photoService,
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
        $params['isDraft'] = false;

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

        $addressData = $space['addressData'] ?? null;

        if (null !== $addressData) {
            $address = $spaceFromDB->getAddress() ?? new SpaceAddress();
            $city = $this->cityService->get($space['addressData']['city']);

            $address->setZipcode($space['addressData']['zipcode']);
            $address->setStreet($space['addressData']['street']);
            $address->setNumber($space['addressData']['number'] ?? '');
            $address->setNeighborhood($space['addressData']['neighborhood']);
            $address->setComplement($space['addressData']['complement']);
            $address->setCity($city);

            $address->setOwner($spaceFromDB);
            $spaceObj->setAddress($address);
        }

        $spaceObj->setUpdatedAt(new DateTime());

        return $this->repository->save($spaceObj);
    }

    public function updateImage(Uuid $id, UploadedFile $uploadedFile): Space
    {
        return $this->processFileUpload(
            id: $id,
            uploadedFile: $uploadedFile,
            dtoClass: SpaceDto::class,
            dtoProperty: 'profileImage',
            directoryParam: self::DIR_SPACE_PROFILE,
            getterMethod: 'getImage',
            setterMethod: 'setImage',
            validationGroups: [SpaceDto::UPDATE]
        );
    }

    public function updateCoverImage(Uuid $id, UploadedFile $uploadedFile): Space
    {
        return $this->processFileUpload(
            id: $id,
            uploadedFile: $uploadedFile,
            dtoClass: SpaceDto::class,
            dtoProperty: 'coverImage',
            directoryParam: self::DIR_SPACE_COVER,
            getterMethod: 'getCoverImage',
            setterMethod: 'setCoverImage',
            validationGroups: [SpaceDto::UPDATE]
        );
    }

    public function togglePublish(Uuid $id): void
    {
        $space = $this->get($id);
        $space->setIsDraft(!$space->isDraft());

        $this->repository->save($space);
    }

    public function addPortfolioImage(Space $space, UploadedFile $uploadedFile, ?string $description = null): Space
    {
        $photo = $this->photoService->create($uploadedFile, self::DIR_SPACE_PORTFOLIO, $description);

        $space->addPortfolio($photo);
        $space->setUpdatedAt(new DateTime());

        $this->entityManager->flush();

        return $space;
    }

    public function removePortfolioImage(Uuid $spaceId, Uuid $photoId): Space
    {
        $space = $this->get($spaceId);

        $photo = $this->photoService->get($photoId);

        if (null !== $photo) {
            $space->removePortfolio($photo);
            $this->photoService->delete($photo);
            $space->setUpdatedAt(new DateTime());

            $this->entityManager->flush();
        }

        return $space;
    }
}
