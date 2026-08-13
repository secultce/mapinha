<?php

declare(strict_types=1);

namespace App\Serializer\Denormalizer;

use App\Entity\ActivityArea;
use App\Entity\Agent;
use App\Entity\CulturalLanguage;
use App\Entity\Event;
use App\Entity\EventType;
use App\Entity\Initiative;
use App\Entity\Space;
use App\Entity\Tag;
use App\Enum\AccessibilityInfoEnum;
use App\Enum\EventFormatEnum;
use App\Service\Interface\FileServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

readonly class EventDenormalizer implements DenormalizerInterface
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

        if (Event::class !== $type) {
            return $data;
        }

        if (true === array_key_exists('image', $data)) {
            $this->uploadImage($data, $context['object_to_populate'] ?? null);
        }

        if (true === array_key_exists('coverImage', $data)) {
            $this->uploadCoverImage($data, $context['object_to_populate'] ?? null);
        }

        if (true === array_key_exists('format', $data)) {
            $data['format'] = $this->denormalizeEventType($data['format']);
        }

        if (true === array_key_exists('accessibleAudio', $data)) {
            $data['accessibleAudio'] = $this->denormalizeAccessibilityInfo($data['accessibleAudio']);
        }

        if (true === array_key_exists('accessibleLibras', $data)) {
            $data['accessibleLibras'] = $this->denormalizeAccessibilityInfo($data['accessibleLibras']);
        }

        /** @var Event $event */
        $event = $this->denormalizer->denormalize($data, Event::class, $format, $context);

        if (isset($data['agentGroup'])) {
            $data['agentGroup'] = $this->entityManager->getRepository(Agent::class)->find($data['agentGroup']);
            $event->setAgentGroup($data['agentGroup']);
        }

        if (isset($data['space'])) {
            $data['space'] = $this->entityManager->getRepository(Space::class)->find($data['space']);
            $event->setSpace($data['space']);
        }

        if (isset($data['initiative'])) {
            $data['initiative'] = $this->entityManager->getRepository(Initiative::class)->find($data['initiative']);
            $event->setInitiative($data['initiative']);
        }

        if (isset($data['parent'])) {
            $data['parent'] = $this->entityManager->getRepository(Event::class)->find($data['parent']);
            $event->setParent($data['parent']);
        }

        if (isset($data['createdBy'])) {
            $data['createdBy'] = $this->entityManager->getRepository(Agent::class)->find($data['createdBy']);
            $event->setCreatedBy($data['createdBy']);
        }

        if (true === array_key_exists('activityAreas', $data)) {
            $activityAreas = array_map(
                fn (string $id) => $this->entityManager->find(ActivityArea::class, $id),
                $data['activityAreas']
            );

            $event->setActivityAreas(new ArrayCollection($activityAreas));
        }

        if (true === array_key_exists('tags', $data)) {
            $tags = array_map(
                fn (string $id) => $this->entityManager->find(Tag::class, $id),
                $data['tags']
            );

            $event->setTags(new ArrayCollection($tags));
        }

        if (true === array_key_exists('culturalLanguages', $data)) {
            $culturalLanguages = array_map(
                fn (string $id) => $this->entityManager->find(CulturalLanguage::class, $id),
                $data['culturalLanguages']
            );

            $event->setCulturalLanguages(new ArrayCollection($culturalLanguages));
        }

        if (true === array_key_exists('eventType', $data)) {
            $eventType = $this->entityManager->getRepository(EventType::class)->find($data['eventType']);
            $event->setEventType($eventType);
        }

        return $event;
    }

    private function uploadImage(array &$data, ?Event $eventFromDb = null): void
    {
        if (false === is_null($eventFromDb) && true === is_string($eventFromDb->getImage())) {
            $this->fileService->deleteFileByUrl($eventFromDb->getImage());
        }

        if ($data['image'] instanceof File) {
            $data['image'] = $this->fileService->getFileUrl($data['image']->getPathname());
        }
    }

    private function uploadCoverImage(array &$data, ?Event $eventFromDb = null): void
    {
        if (false === is_null($eventFromDb) && true === is_string($eventFromDb->getCoverImage())) {
            $this->fileService->deleteFileByUrl($eventFromDb->getCoverImage());
        }

        if ($data['coverImage'] instanceof File) {
            $data['coverImage'] = $this->fileService->getFileUrl($data['coverImage']->getPathname());
        }
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return Event::class === $type;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            'object' => null,
            '*' => false,
            Event::class => true,
        ];
    }

    private function denormalizeEventType(mixed $eventType): int
    {
        if (true === is_string($eventType)) {
            $choice = EventFormatEnum::fromName($eventType);

            return $choice->value;
        }

        if ($eventType instanceof EventFormatEnum) {
            return $eventType->value;
        }

        return (int) $eventType;
    }

    private function denormalizeAccessibilityInfo(mixed $accessibilityInfo): int
    {
        if (true === is_string($accessibilityInfo)) {
            $choice = AccessibilityInfoEnum::fromName($accessibilityInfo);

            return $choice->value;
        }

        if ($accessibilityInfo instanceof AccessibilityInfoEnum) {
            return $accessibilityInfo->value;
        }

        return (int) $accessibilityInfo;
    }
}
