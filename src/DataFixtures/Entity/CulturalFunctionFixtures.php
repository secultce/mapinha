<?php

declare(strict_types=1);

namespace App\DataFixtures\Entity;

use App\Entity\CulturalFunction;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class CulturalFunctionFixtures extends AbstractFixture
{
    public const string CULTURAL_FUNCTION_ID_PREFIX = 'cultural_function';
    public const string CULTURAL_FUNCTION_ID_1 = '96401272-590e-40b6-b0bf-5bfdc3a178c6';
    public const string CULTURAL_FUNCTION_ID_2 = '10f2356e-291c-459c-9d81-2229e8d75621';
    public const string CULTURAL_FUNCTION_ID_3 = '7e0ab3fd-fda4-4534-a454-0bc98fe12678';
    public const string CULTURAL_FUNCTION_ID_4 = '93dc6e16-0ec2-48ba-829d-dd11dee94297';

    public const array CULTURAL_FUNCTIONS = [
        [
            'id' => self::CULTURAL_FUNCTION_ID_1,
            'name' => 'Produtor',
        ],
        [
            'id' => self::CULTURAL_FUNCTION_ID_2,
            'name' => 'Músico',
        ],
        [
            'id' => self::CULTURAL_FUNCTION_ID_3,
            'name' => 'Roteirista',
        ],
        [
            'id' => self::CULTURAL_FUNCTION_ID_4,
            'name' => 'Curso',
        ],
    ];

    public const array CULTURAL_FUNCTIONS_UPDATED = [
        [
            'id' => self::CULTURAL_FUNCTION_ID_1,
            'name' => 'Produtor Cultural',
        ],
    ];

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected TokenStorageInterface $tokenStorage,
        private readonly SerializerInterface $serializer,
    ) {
        parent::__construct($entityManager, $tokenStorage);
    }

    public function load(ObjectManager $manager): void
    {
        $this->createCulturalFunction($manager);
        $this->updateCulturalFunction($manager);
        $this->manualLogout();
    }

    private function createCulturalFunction(ObjectManager $manager): void
    {
        foreach (self::CULTURAL_FUNCTIONS as $culturalFunctionData) {
            $culturalFunction = $this->serializer->denormalize($culturalFunctionData, CulturalFunction::class);

            $this->setReference(sprintf('%s-%s', self::CULTURAL_FUNCTION_ID_PREFIX, $culturalFunctionData['id']), $culturalFunction);

            $manager->persist($culturalFunction);
        }

        $manager->flush();
    }

    private function updateCulturalFunction(ObjectManager $manager): void
    {
        foreach (self::CULTURAL_FUNCTIONS_UPDATED as $culturalFunctionData) {
            $culturalFunctionObj = $this->getReference(sprintf('%s-%s', self::CULTURAL_FUNCTION_ID_PREFIX, $culturalFunctionData['id']), CulturalFunction::class);

            $culturalFunction = $this->serializer->denormalize($culturalFunctionData, CulturalFunction::class, context: ['object_to_populate' => $culturalFunctionObj]);

            $manager->persist($culturalFunction);
        }

        $manager->flush();
    }
}
