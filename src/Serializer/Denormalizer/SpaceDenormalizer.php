<?php

declare(strict_types=1);

namespace App\Serializer\Denormalizer;

use App\Entity\ActivityArea;
use App\Entity\Agent;
use App\Entity\ArchitecturalAccessibility;
use App\Entity\Photo;
use App\Entity\Space;
use App\Entity\SpaceType;
use App\Entity\Tag;
use App\Service\Interface\FileServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

readonly class SpaceDenormalizer implements DenormalizerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        #[Autowire(service: 'serializer.normalizer.object')]
        private DenormalizerInterface $denormalizer,
        private FileServiceInterface $fileService,
    ) {
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        if (false === is_array($data)) {
            return $this->denormalizer->denormalize(['id' => $data], $type, $format, $context);
        }

        if (Space::class !== $type) {
            return $data;
        }

        if (true === array_key_exists('image', $data)) {
            $this->uploadImage($data, $context['object_to_populate'] ?? null);
        }

        /** @var Space $space */
        $space = $this->denormalizer->denormalize($data, $type, $format, $context);

        if (isset($data['createdBy'])) {
            $data['createdBy'] = $this->entityManager->getRepository(Agent::class)->find($data['createdBy']);
            $space->setCreatedBy($data['createdBy']);
        }

        if (isset($data['parent'])) {
            $data['parent'] = $this->entityManager->getRepository(Space::class)->find($data['parent']);
            $space->setParent($data['parent']);
        }

        $activityAreas = array_map(
            fn (string $id) => $this->entityManager->getRepository(ActivityArea::class)->findOneBy(['id' => $id]),
            $data['activityAreas'] ?? []
        );

        if (true === array_key_exists('activityAreas', $data)) {
            $space->setActivityAreas(new ArrayCollection($activityAreas));
        }

        $accessibilities = array_map(
            fn (string $id) => $this->entityManager->getRepository(ArchitecturalAccessibility::class)->findOneBy(['id' => $id]),
            $data['accessibilities'] ?? []
        );

        if (true === array_key_exists('accessibilities', $data)) {
            $space->setAccessibilities(new ArrayCollection($accessibilities));
        }

        if (true === array_key_exists('tags', $data)) {
            $tags = array_map(
                fn (string $id) => $this->entityManager->find(Tag::class, $id),
                $data['tags']
            );

            $space->setTags(new ArrayCollection($tags));
        }

        if (true === array_key_exists('spaceType', $data)) {
            $spaceType = $this->entityManager->getRepository(SpaceType::class)->find($data['spaceType']);
            $space->setSpaceType($spaceType);
        }

        $portfolio = array_map(
            fn (string $id) => $this->entityManager->find(Photo::class, $id),
            $data['portfolio'] ?? []
        );

        if (true === array_key_exists('portfolio', $data)) {
            $space->setPortfolio(new ArrayCollection($portfolio));
        }

        return $space;
    }

    private function uploadImage(array &$data, ?Space $spaceFromDb = null): void
    {
        if (false === is_null($spaceFromDb) && true === is_string($spaceFromDb->getImage())) {
            $this->fileService->deleteFileByUrl($spaceFromDb->getImage());
        }

        if ($data['image'] instanceof File) {
            $data['image'] = $this->fileService->getFileUrl($data['image']->getPathname());
        }
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return Space::class === $type;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            'object' => null,
            '*' => false,
            Space::class => true,
        ];
    }
}
