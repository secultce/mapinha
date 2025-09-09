<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CulturalLanguageDto;
use App\Entity\CulturalLanguage;
use App\Exception\CulturalLanguage\CulturalLanguageResourceNotFoundException;
use App\Repository\Interface\CulturalLanguageRepositoryInterface;
use App\Service\Interface\CulturalLanguageServiceInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

readonly class CulturalLanguageService extends AbstractEntityService implements CulturalLanguageServiceInterface
{
    public function __construct(
        private CulturalLanguageRepositoryInterface $repository,
        private Security $security,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {
        parent::__construct($this->security, $this->serializer, $this->validator);
    }

    public function create(array $culturalLanguage): CulturalLanguage
    {
        $culturalLanguage = $this->validateInput($culturalLanguage, CulturalLanguageDto::class, CulturalLanguageDto::CREATE);

        $culturalLanguageObj = $this->serializer->denormalize($culturalLanguage, CulturalLanguage::class);

        return $this->repository->save($culturalLanguageObj);
    }

    public function get(Uuid $id): CulturalLanguage
    {
        $culturalLanguage = $this->findOneBy(['id' => $id]);

        if (null === $culturalLanguage) {
            throw new CulturalLanguageResourceNotFoundException();
        }

        return $culturalLanguage;
    }

    public function list(int $limit = 50, array $params = []): array
    {
        return $this->repository->findBy(
            $params,
            ['name' => 'ASC'],
            $limit
        );
    }

    public function remove(Uuid $id): void
    {
        $culturalLanguage = $this->findOneBy(['id' => $id]);

        if (null === $culturalLanguage) {
            throw new CulturalLanguageResourceNotFoundException();
        }

        $this->repository->remove($culturalLanguage);
    }

    private function findOneBy(array $array): ?CulturalLanguage
    {
        return $this->repository->findOneBy($array);
    }

    public function update(Uuid $id, array $culturalLanguage): CulturalLanguage
    {
        $culturalLanguageFromDB = $this->get($id);

        $culturalLanguageDto = $this->validateInput($culturalLanguage, CulturalLanguageDto::class, CulturalLanguageDto::UPDATE);

        $culturalLanguageObj = $this->serializer->denormalize($culturalLanguageDto, CulturalLanguage::class, context: [
            'object_to_populate' => $culturalLanguageFromDB,
        ]);

        return $this->repository->save($culturalLanguageObj);
    }
}
