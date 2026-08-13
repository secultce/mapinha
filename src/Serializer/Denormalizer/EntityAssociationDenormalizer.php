<?php

declare(strict_types=1);

namespace App\Serializer\Denormalizer;

use App\Entity\Agent;
use App\Entity\EntityAssociation;
use App\Entity\Event;
use App\Entity\Initiative;
use App\Entity\Opportunity;
use App\Entity\Organization;
use App\Entity\Space;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Uid\Uuid;

readonly class EntityAssociationDenormalizer implements DenormalizerInterface
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

        if (EntityAssociation::class !== $type) {
            return $data;
        }

        $objectToPopulate = $context['object_to_populate'] ?? null;

        if (null === $objectToPopulate && isset($data['id'])) {
            $id = is_string($data['id']) ? Uuid::fromString($data['id']) : $data['id'];
            $objectToPopulate = $this->entityManager->getRepository(EntityAssociation::class)->find($id);
        }

        if ($objectToPopulate) {
            $context['object_to_populate'] = $objectToPopulate;
        }

        $entityAssociation = $this->denormalizer->denormalize($data, $type, $format, $context);

        if (isset($data['agent'])) {
            $agent = $this->entityManager->getRepository(Agent::class)->find($data['agent']);
            $entityAssociation->setAgent($agent);
        }
        if (isset($data['event'])) {
            $event = $this->entityManager->getRepository(Event::class)->find($data['event']);
            $entityAssociation->setEvent($event);
        }
        if (isset($data['initiative'])) {
            $initiative = $this->entityManager->getRepository(Initiative::class)->find($data['initiative']);
            $entityAssociation->setInitiative($initiative);
        }
        if (isset($data['opportunity'])) {
            $opportunity = $this->entityManager->getRepository(Opportunity::class)->find($data['opportunity']);
            $entityAssociation->setOpportunity($opportunity);
        }
        if (isset($data['organization'])) {
            $organization = $this->entityManager->getRepository(Organization::class)->find($data['organization']);
            $entityAssociation->setOrganization($organization);
        }
        if (isset($data['space'])) {
            $space = $this->entityManager->getRepository(Space::class)->find($data['space']);
            $entityAssociation->setSpace($space);
        }

        return $entityAssociation;
    }

    public function supportsDenormalization(mixed $data, string $type, ?string $format = null, array $context = []): bool
    {
        return EntityAssociation::class === $type;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            'object' => null,
            '*' => false,
            EntityAssociation::class => true,
        ];
    }
}
