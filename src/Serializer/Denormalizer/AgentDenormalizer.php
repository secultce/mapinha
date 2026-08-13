<?php

declare(strict_types=1);

namespace App\Serializer\Denormalizer;

use App\Entity\Agent;
use App\Entity\CulturalFunction;
use App\Entity\Organization;
use App\Entity\Photo;
use App\Entity\User;
use App\Service\Interface\FileServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

readonly class AgentDenormalizer implements DenormalizerInterface
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

        if (Agent::class !== $type) {
            return $data;
        }

        $organizations = array_map(
            fn (string $id) => $this->entityManager->getRepository(Organization::class)->findOneBy(['id' => $id]),
            $data['organizations'] ?? []
        );

        if (true === array_key_exists('image', $data)) {
            $this->uploadImage($data, $context['object_to_populate'] ?? null);
        }

        /** @var Agent $agent */
        $agent = $this->denormalizer->denormalize($this->filterData($data), $type, $format, $context);

        if (true === array_key_exists('user', $data)) {
            $data['user'] = $this->entityManager->getRepository(User::class)->find($data['user']);
            $agent->setUser($data['user']);
        }

        if (true === array_key_exists('organizations', $data)) {
            $agent->setOrganizations(new ArrayCollection($organizations));
        }

        $culturalFunctions = array_map(
            fn (string $id) => $this->entityManager->getRepository(CulturalFunction::class)->findOneBy(['id' => $id]),
            $data['culturalFunction'] ?? []
        );

        if (true === array_key_exists('culturalFunction', $data)) {
            $culturalFunctions = array_filter($culturalFunctions);
            $agent->setCulturalFunction(new ArrayCollection($culturalFunctions));
        }

        $portfolio = array_map(
            fn (string $id) => $this->entityManager->find(Photo::class, $id),
            $data['portfolio'] ?? []
        );

        if (true === array_key_exists('portfolio', $data)) {
            $agent->setPortfolio(new ArrayCollection($portfolio));
        }

        return $agent;
    }

    private function uploadImage(array &$data, ?Agent $agentFromDb = null): void
    {
        if (false === is_null($agentFromDb) && true === is_string($agentFromDb->getImage())) {
            $this->fileService->deleteFileByUrl($agentFromDb->getImage());
        }

        if ($data['image'] instanceof File) {
            $data['image'] = $this->fileService->getFileUrl($data['image']->getPathname());
        }
    }

    private function filterData(array $data): array
    {
        unset($data['organizations'], $data['culturalFunction'], $data['portfolio']);

        return $data;
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return Agent::class === $type;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            'object' => null,
            '*' => false,
            Agent::class => true,
        ];
    }
}
