<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\PhotoDto;
use App\Entity\Photo;
use App\Exception\ValidatorException;
use App\Service\Interface\FileServiceInterface;
use App\Service\Interface\PhotoServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class PhotoService implements PhotoServiceInterface
{
    public function __construct(
        private FileServiceInterface $fileService,
        private ParameterBagInterface $parameterBag,
        private ValidatorInterface $validator,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function create(UploadedFile $uploadedFile, string $directoryParam, ?string $description = null): Photo
    {
        $dto = new PhotoDto();
        $dto->id = Uuid::v4();
        $dto->image = $uploadedFile;
        $dto->description = $description;

        $violations = $this->validator->validate($dto, groups: [PhotoDto::CREATE]);

        if ($violations->count() > 0) {
            throw new ValidatorException(violations: $violations);
        }

        $uploadedImage = $this->fileService->uploadImage(
            $this->parameterBag->get($directoryParam),
            $uploadedFile
        );

        $relativePath = '/uploads'.$this->parameterBag->get($directoryParam).'/'.$uploadedImage->getFilename();

        $photo = new Photo();
        $photo->setId($dto->id);
        $photo->setImage($relativePath);
        $photo->setDescription($description);

        $this->entityManager->persist($photo);

        return $photo;
    }

    public function get(Uuid $id): ?Photo
    {
        return $this->entityManager->find(Photo::class, $id);
    }

    public function delete(Photo $photo): void
    {
        if ($photo->getImage()) {
            $this->fileService->deleteFileByUrl($photo->getImage());
        }

        $photo->setDeletedAt(new DateTime());
    }
}
