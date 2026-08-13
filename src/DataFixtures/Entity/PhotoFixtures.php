<?php

declare(strict_types=1);

namespace App\DataFixtures\Entity;

use App\Entity\Photo;
use App\Service\Interface\FileServiceInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class PhotoFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public const string PHOTO_ID_PREFIX = 'photo';
    public const string PHOTO_ID_1 = 'b2a9d34f-1770-49e8-b3e6-b9a7d314578e';
    public const string PHOTO_ID_2 = 'a55f1057-4c96-4fd1-909c-b156a9472df7';
    public const string PHOTO_ID_3 = '28df9c70-dba9-4b0e-a488-87e244b2f252';
    public const string PHOTO_ID_4 = '8856793b-667a-44f6-a48c-fa361c5a8347';
    public const string PHOTO_ID_5 = '26665e0c-8e8d-4cd1-99d5-8eaefeaa7c7b';
    public const string PHOTO_ID_6 = '77091337-a0ae-47c7-af66-fc4cd4802e2c';
    public const string PHOTO_ID_7 = '9d37d503-73ef-49da-99f1-9f1dcea83033';
    public const string PHOTO_ID_8 = '3e3fc4ab-25d0-444a-8fa4-0ddcc4b77b4e';

    public const array PHOTOS = [
        [
            'id' => self::PHOTO_ID_1,
            'description' => 'Fachada principal do espaço cultural',
            'createdAt' => '2024-07-10T11:30:00+00:00',
        ],
        [
            'id' => self::PHOTO_ID_2,
            'description' => 'Auditório com capacidade para 200 pessoas',
            'createdAt' => '2024-07-10T11:35:00+00:00',
        ],
        [
            'id' => self::PHOTO_ID_3,
            'description' => 'Galeria de arte contemporânea',
            'createdAt' => '2024-07-11T10:00:00+00:00',
        ],
        [
            'id' => self::PHOTO_ID_4,
            'description' => 'Espaço para oficinas e workshops',
            'createdAt' => '2024-07-11T10:05:00+00:00',
        ],
        [
            'id' => self::PHOTO_ID_5,
            'description' => 'Área de convivência',
            'createdAt' => '2024-07-12T09:00:00+00:00',
        ],
        [
            'id' => self::PHOTO_ID_6,
            'description' => 'Biblioteca com acervo local',
            'createdAt' => '2024-07-12T09:30:00+00:00',
        ],
        [
            'id' => self::PHOTO_ID_7,
            'description' => 'Palco para apresentações musicais',
            'createdAt' => '2024-07-13T14:00:00+00:00',
        ],
        [
            'id' => self::PHOTO_ID_8,
            'description' => 'Área externa para eventos ao ar livre',
            'createdAt' => '2024-07-13T14:30:00+00:00',
        ],
    ];

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected TokenStorageInterface $tokenStorage,
        private readonly SerializerInterface $serializer,
        private readonly FileServiceInterface $fileService,
        private readonly ParameterBagInterface $parameterBag,
    ) {
        parent::__construct($entityManager, $tokenStorage);
    }

    public function getDependencies(): array
    {
        return [
            AgentFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $this->createPhotos($manager);
        $this->manualLogout();
    }

    private function createPhotos(ObjectManager $manager): void
    {
        $this->manualLoginByAgent(AgentFixtures::AGENT_ID_1);

        foreach (self::PHOTOS as $photoData) {
            $file = $this->fileService->uploadImage(
                $this->parameterBag->get('app.dir.space.portfolio'),
                ImageFixtures::getSpacePortfolioImage()
            );

            $relativePath = '/uploads'.$this->parameterBag->get('app.dir.space.portfolio').'/'.$file->getFilename();

            $photo = $this->mountPhoto($photoData, $relativePath);

            $this->setReference(sprintf('%s-%s', self::PHOTO_ID_PREFIX, $photoData['id']), $photo);

            $manager->persist($photo);
        }

        $manager->flush();
    }

    private function mountPhoto(array $photoData, string $imagePath): Photo
    {
        /** @var Photo $photo */
        $photo = $this->serializer->denormalize($photoData, Photo::class);
        $photo->setImage($imagePath);

        return $photo;
    }
}
