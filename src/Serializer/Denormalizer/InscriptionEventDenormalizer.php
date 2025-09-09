<?php

declare(strict_types=1);

namespace App\Serializer\Denormalizer;

use App\Entity\Agent;
use App\Entity\Event;
use App\Entity\InscriptionEvent;
use App\Enum\InscriptionEventStatusEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

readonly class InscriptionEventDenormalizer implements DenormalizerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        #[Autowire(service: 'serializer.normalizer.object')]
        private DenormalizerInterface $denormalizer,
    ) {
    }

    public function denormalize(mixed $data, string $type, ?string $format = null, array $context = []): mixed
    {
        if (false === is_array($data)) {
            return $this->denormalizer->denormalize(['id' => $data], $type, $format, $context);
        }

        if (InscriptionEvent::class !== $type) {
            return $data;
        }

        if (true === array_key_exists('status', $data)) {
            $data['status'] = $this->denormalizeInscriptionEventStatus($data['status']);
        }

        /** @var InscriptionEvent $inscriptionEvent */
        $inscriptionEvent = $this->denormalizer->denormalize($data, $type, $format, $context);

        if (true === array_key_exists('event', $data)) {
            $event = $this->entityManager->find(Event::class, $data['event']);
            $inscriptionEvent->setEvent($event);
        }

        if (true === array_key_exists('agent', $data)) {
            $agent = $this->entityManager->find(Agent::class, $data['agent']);
            $inscriptionEvent->setAgent($agent);
        }

        return $inscriptionEvent;
    }

    private function denormalizeInscriptionEventStatus(mixed $status): int
    {
        if ($status instanceof InscriptionEventStatusEnum) {
            return $status->value;
        }

        if (true === is_string($status)) {
            return InscriptionEventStatusEnum::fromName($status)->value;
        }

        return $status;
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return InscriptionEvent::class === $type;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            'object' => null,
            '*' => false,
            InscriptionEvent::class => true,
        ];
    }
}
