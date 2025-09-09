<?php

declare(strict_types=1);

namespace App\DataFixtures\Entity;

use App\Entity\CulturalLanguage;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class CulturalLanguageFixtures extends AbstractFixture
{
    public const string CULTURAL_LANGUAGE_ID_PREFIX = 'cultural_language';
    public const string CULTURAL_LANGUAGE_ID_1 = '5ae9bdcb-2bb7-46e0-957b-2062f99882b3';
    public const string CULTURAL_LANGUAGE_ID_2 = 'aeb66adc-af1f-411b-8cb8-c7c8e0b3a0ca';
    public const string CULTURAL_LANGUAGE_ID_3 = 'c42ef524-6a5e-4ff4-81be-218dea3974a1';
    public const string CULTURAL_LANGUAGE_ID_4 = 'a9fc3262-30ae-49d1-861c-e59a4e347233';
    public const string CULTURAL_LANGUAGE_ID_5 = '0f13fbaf-0fb7-488e-bd30-b0a1fa77fc3c';
    public const string CULTURAL_LANGUAGE_ID_6 = 'bc33db9a-e591-4237-8193-3753cd308e03';
    public const string CULTURAL_LANGUAGE_ID_7 = '6eceb016-8977-4088-a0c8-fe83ac946ecf';
    public const string CULTURAL_LANGUAGE_ID_8 = 'f49197bd-8349-478c-9540-32bc520c91d5';

    public const array CULTURAL_LANGUAGES = [
        [
            'id' => self::CULTURAL_LANGUAGE_ID_1,
            'name' => 'Arte e Música',
            'description' => 'Pintura, escultura, literatura, dança, teatro, música e outras formas artísticas que comunicam ideias, histórias e sentimentos.',
        ],
        [
            'id' => self::CULTURAL_LANGUAGE_ID_2,
            'name' => 'Costumes e Rituais',
            'description' => 'Celebrações, festivais, cerimônias religiosas e ritos de passagem que expressam identidades culturais.',
        ],
        [
            'id' => self::CULTURAL_LANGUAGE_ID_3,
            'name' => 'Moda e Vestuário',
            'description' => 'Estilos de vestir que comunicam status social, afiliação cultural, crenças ou até protestos.',
        ],
        [
            'id' => self::CULTURAL_LANGUAGE_ID_4,
            'name' => 'Gastronomia',
            'description' => 'Pratos e modos de preparo que refletem tradições, identidades e até histórias de resistência ou adaptação cultural.',
        ],
        [
            'id' => self::CULTURAL_LANGUAGE_ID_5,
            'name' => 'Arquitetura e Design',
            'description' => 'Formas e estilos que refletem valores estéticos, religiosos ou funcionais de uma cultura.',
        ],
        [
            'id' => self::CULTURAL_LANGUAGE_ID_6,
            'name' => 'Sistemas de Simbolismo',
            'description' => 'Símbolos, gestos, mitos e narrativas que comunicam significados compartilhados.',
        ],
        [
            'id' => self::CULTURAL_LANGUAGE_ID_7,
            'name' => 'Tecnologia e Ferramentas',
            'description' => 'Maneiras específicas de usar tecnologias ou criar ferramentas que têm significado cultural.',
        ],
        [
            'id' => self::CULTURAL_LANGUAGE_ID_8,
            'name' => 'Oralidade e Tradição Popular',
            'description' => 'Histórias, provérbios, músicas e conhecimentos transmitidos verbalmente entre gerações.',
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
        $this->createCulturalLanguages($manager);
    }

    private function createCulturalLanguages(ObjectManager $manager): void
    {
        foreach (self::CULTURAL_LANGUAGES as $culturalLanguageData) {
            $culturalLanguage = $this->serializer->denormalize($culturalLanguageData, CulturalLanguage::class);

            $this->setReference(
                sprintf('%s-%s', self::CULTURAL_LANGUAGE_ID_PREFIX, $culturalLanguageData['id']),
                $culturalLanguage
            );

            $manager->persist($culturalLanguage);
        }
        $manager->flush();
    }
}
