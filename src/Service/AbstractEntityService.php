<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\UserRolesEnum;
use App\Exception\EntityManagerAndEntityClassNotSetException;
use App\Exception\NoEntitiesProvidedForExportException;
use App\Exception\ValidatorException;
use App\Service\Interface\FileServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract readonly class AbstractEntityService
{
    protected const array DEFAULT_FILTERS = [
        'deletedAt' => null,
    ];

    public function __construct(
        private Security $security,
        private ?SerializerInterface $serializer = null,
        private ?ValidatorInterface $validator = null,
        private ?EntityManagerInterface $entityManager = null,
        private ?string $entityClass = null,
        private ?FileServiceInterface $fileService = null,
        private ?ParameterBagInterface $parameterBag = null,
        private ?string $imagePath = null
    ) {
    }

    public function getDefaultParams(): array
    {
        return self::DEFAULT_FILTERS;
    }

    public function getUserParams(): array
    {
        if (null !== $this->security->getUser() && $this->security->getUser()->isRole(UserRolesEnum::ROLE_ADMIN)) {
            return [];
        }

        $params = self::DEFAULT_FILTERS;

        if (null !== $this->security->getUser()) {
            $agents = $this->security->getUser()->getAgents()->getValues();

            $params['createdBy'] = $agents;
        }

        return $params;
    }

    public function getAgentsFromLoggedUser(): array
    {
        return $this->security->getUser()->getAgents()->getValues();
    }

    public function countRecentRecords(int $days): int
    {
        if (null === $this->entityManager and null === $this->entityClass) {
            throw new EntityManagerAndEntityClassNotSetException();
        }

        $metadata = $this->entityManager->getClassMetadata($this->entityClass);
        $tableName = $metadata->getTableName();

        $sql = sprintf(
            "SELECT COUNT(*) as count FROM %s WHERE created_at >= NOW() - INTERVAL '%d DAY'",
            $tableName,
            $days
        );

        $statement = $this->entityManager->getConnection()->prepare($sql);

        return (int) $statement->executeQuery()->fetchAssociative()['count'];
    }

    public function denormalizeDto(array $data, string $dtoClass): object
    {
        $callbacks = [];
        if (
            null !== $this->imagePath
            && isset($data['image'])
            && null !== $this->fileService
            && null !== $this->parameterBag
        ) {
            $callbacks['image'] = function () use ($data) {
                return $this->fileService->uploadImage(
                    $this->parameterBag->get($this->imagePath),
                    $data['image']
                );
            };
        }

        return $this->serializer->denormalize($data, $dtoClass, context: [
            AbstractNormalizer::CALLBACKS => $callbacks,
        ]);
    }

    public function validateInput(array $data, string $dtoClass, string $group = 'Default'): array
    {
        $dto = $this->denormalizeDto($data, $dtoClass);
        $violations = $this->validator->validate($dto, groups: $group);
        if ($violations->count() > 0) {
            throw new ValidatorException(violations: $violations);
        }

        return $data;
    }

    public function generateCsv(array $entities, string $filename, ?string $type): StreamedResponse
    {
        if (empty($entities)) {
            throw new NoEntitiesProvidedForExportException();
        }

        $response = new StreamedResponse(function () use ($entities, $type): void {
            $handle = fopen('php://output', 'w+');

            fputcsv($handle, $this->getCsvHeaders($type));

            foreach ($entities as $entity) {
                fputcsv($handle, $this->getCsvRow($entity, $type));
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");

        return $response;
    }
}
