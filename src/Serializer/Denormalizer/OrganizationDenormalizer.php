<?php

declare(strict_types=1);

namespace App\Serializer\Denormalizer;

use App\Entity\ActivityArea;
use App\Entity\Agent;
use App\Entity\Organization;
use App\Service\Interface\FileServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

readonly class OrganizationDenormalizer implements DenormalizerInterface
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

        if (Organization::class !== $type) {
            return $data;
        }

        if (true === array_key_exists('image', $data)) {
            $this->uploadImage($data, $context['object_to_populate'] ?? null);
        }

        $agents = array_map(
            fn (string $id) => $this->entityManager->getRepository(Agent::class)->findOneBy(['id' => $id]),
            $data['agents'] ?? []
        );

        /** @var Organization $organization */
        $organization = $this->denormalizer->denormalize($this->filterData($data), $type, $format, $context);

        if (true === array_key_exists('agents', $data)) {
            $organization->setAgents(new ArrayCollection($agents));
        }

        $activityAreas = array_map(
            fn (string $id) => $this->entityManager->getRepository(ActivityArea::class)->findOneBy(['id' => $id]),
            $data['activityAreas'] ?? []
        );

        if (true === array_key_exists('activityAreas', $data)) {
            $organization->setActivityAreas(new ArrayCollection($activityAreas));
        }

        if (true === array_key_exists('createdBy', $data)) {
            $createdBy = $this->entityManager->getRepository(Agent::class)->find($data['createdBy']);
            $organization->setCreatedBy($createdBy);
        }

        if (true === array_key_exists('owner', $data)) {
            $owner = $this->entityManager->getRepository(Agent::class)->find($data['owner']);
            $organization->setOwner($owner);
        }

        return $organization;
    }

    private function uploadImage(array &$data, ?Organization $organizationFromDb = null): void
    {
        if (false === is_null($organizationFromDb) && true === is_string($organizationFromDb->getImage())) {
            $this->fileService->deleteFileByUrl($organizationFromDb->getImage());
        }

        if ($data['image'] instanceof File) {
            $data['image'] = $this->fileService->getFileUrl($data['image']->getPathname());
        }
    }

    private function filterData(array $data): array
    {
        unset($data['agents']);

        return $data;
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return Organization::class === $type;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            'object' => null,
            '*' => false,
            Organization::class => true,
        ];
    }
}
