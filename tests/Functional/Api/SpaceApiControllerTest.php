<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\DataFixtures\Entity\ActivityAreaFixtures;
use App\DataFixtures\Entity\AgentFixtures;
use App\DataFixtures\Entity\ArchitecturalAccessibilityFixtures;
use App\DataFixtures\Entity\PhotoFixtures;
use App\DataFixtures\Entity\SpaceFixtures;
use App\DataFixtures\Entity\TagFixtures;
use App\Entity\Space;
use App\Tests\AbstractApiTestCase;
use App\Tests\Fixtures\ImageTestFixtures;
use App\Tests\Fixtures\SpaceTestFixtures;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class SpaceApiControllerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/spaces';

    private ?ParameterBagInterface $parameterBag = null;

    protected function setUp(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $this->parameterBag = $container->get(ParameterBagInterface::class);
    }

    public function testCanCreateWithPartialRequestBody(): void
    {
        $requestBody = SpaceTestFixtures::partial();
        $requestBody['isDraft'] = true;

        $client = static::apiClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        /** @var Space $space */
        $space = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Space::class, $requestBody['id']);

        $this->assertResponseBodySame([
            'id' => $requestBody['id'],
            'name' => $requestBody['name'],
            'shortDescription' => null,
            'longDescription' => null,
            'image' => null,
            'coverImage' => null,
            'site' => null,
            'email' => null,
            'phoneNumber' => null,
            'maxCapacity' => 100,
            'isAccessible' => true,
            'isDraft' => true,
            'address' => null,
            'createdBy' => ['id' => $requestBody['createdBy']],
            'parent' => [
                'id' => $requestBody['parent'],
                'name' => 'SECULT',
                'shortDescription' => $space->getParent()->getShortDescription(),
                'longDescription' => $space->getParent()->getLongDescription(),
                'image' => $space->getParent()->getImage(),
                'coverImage' => $space->getParent()->getCoverImage(),
                'site' => $space->getParent()->getSite(),
                'email' => $space->getParent()->getEmail(),
                'phoneNumber' => $space->getParent()->getPhoneNumber(),
                'maxCapacity' => $space->getParent()->getMaxCapacity(),
                'isAccessible' => $space->getParent()->isAccessible(),
                'isDraft' => $space->getParent()->isDraft(),
                'address' => [
                    'id' => 'b1b3eddd-3eac-4d96-97b5-1662767ae5f6',
                    'street' => 'Rua Doutor João Moreira',
                    'number' => '540',
                    'neighborhood' => 'Centro',
                    'complement' => 'Complexo Estação das Artes',
                    'city' => [
                        'id' => '97847c18-ac1c-4a00-93d4-b4a3e72a262c',
                    ],
                    'zipcode' => '60030000',
                ],
                'createdBy' => ['id' => AgentFixtures::AGENT_ID_1],
                'extraFields' => [
                    'type' => 'Instituição Cultural',
                    'description' => 'A Secretaria da Cultura (SECULT) é responsável por fomentar a arte e a cultura no estado, organizando eventos e oferecendo apoio a iniciativas locais.',
                    'location' => 'Complexo Estação das Artes - R. Dr. João Moreira, 540 - Centro, Fortaleza - CE, 60030-000',
                ],
                'activityAreas' => [
                    [
                        'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_2,
                        'name' => 'Música',
                    ],
                    [
                        'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_3,
                        'name' => 'Teatro',
                    ],
                    [
                        'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_10,
                        'name' => 'Fotografia',
                    ],
                ],
                'tags' => [
                    [
                        'id' => TagFixtures::TAG_ID_1,
                        'name' => 'Cultura',
                    ],
                    [
                        'id' => TagFixtures::TAG_ID_2,
                        'name' => 'Tecnologia',
                    ],
                ],
                'accessibilities' => [
                    [
                        'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_1,
                        'name' => 'Rampas',
                        'description' => 'Rampas de acesso para cadeirantes',
                    ],
                    [
                        'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_2,
                        'name' => 'Elevadores',
                        'description' => 'Elevadores adaptados para acessibilidade',
                    ],
                    [
                        'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_4,
                        'name' => 'Banheiros adaptados',
                        'description' => 'Banheiros com adaptações para pessoas com deficiência',
                    ],
                    [
                        'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_9,
                        'name' => 'Sinalização tátil',
                        'description' => 'Sinalização tátil para orientação de pessoas com deficiência visual',
                    ],
                ],
                'portfolio' => [
                    [
                        'id' => PhotoFixtures::PHOTO_ID_1,
                        'image' => $this->findImage($client, PhotoFixtures::PHOTO_ID_1),
                        'description' => 'Fachada principal do espaço cultural',
                        'createdAt' => '2024-07-10T11:30:00+00:00',
                        'updatedAt' => null,
                        'deletedAt' => null,
                    ],
                    [
                        'id' => PhotoFixtures::PHOTO_ID_2,
                        'image' => $this->findImage($client, PhotoFixtures::PHOTO_ID_2),
                        'description' => 'Auditório com capacidade para 200 pessoas',
                        'createdAt' => '2024-07-10T11:35:00+00:00',
                        'updatedAt' => null,
                        'deletedAt' => null,
                    ],
                    [
                        'id' => PhotoFixtures::PHOTO_ID_3,
                        'image' => $this->findImage($client, PhotoFixtures::PHOTO_ID_3),
                        'description' => 'Galeria de arte contemporânea',
                        'createdAt' => '2024-07-11T10:00:00+00:00',
                        'updatedAt' => null,
                        'deletedAt' => null,
                    ],
                ],
                'createdAt' => '2024-07-10T11:30:00+00:00',
                'updatedAt' => '2024-07-10T12:20:00+00:00',
                'deletedAt' => null,
                'spaceType' => $space->getParent()->getSpaceType()->toArray(),
            ],
            'extraFields' => null,
            'activityAreas' => [],
            'tags' => [],
            'accessibilities' => [],
            'portfolio' => [],
            'createdAt' => $space->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => null,
            'deletedAt' => null,
            'spaceType' => null,
        ]);
    }

    public function testCanCreateWithCompleteRequestBody(): void
    {
        $requestBody = SpaceTestFixtures::complete();

        $client = static::apiClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        /** @var Space $space */
        $space = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Space::class, $requestBody['id']);

        $this->assertResponseBodySame([
            'id' => $requestBody['id'],
            'name' => $requestBody['name'],
            'shortDescription' => $space->getShortDescription(),
            'longDescription' => $space->getLongDescription(),
            'image' => null,
            'coverImage' => $space->getCoverImage(),
            'site' => $space->getSite(),
            'email' => $space->getEmail(),
            'phoneNumber' => $space->getPhoneNumber(),
            'maxCapacity' => $space->getMaxCapacity(),
            'isAccessible' => $space->isAccessible(),
            'isDraft' => false,
            'address' => $space->getAddress(),
            'createdBy' => ['id' => $requestBody['createdBy']],
            'parent' => [
                'id' => $requestBody['parent'],
                'name' => 'SECULT',
                'shortDescription' => $space->getParent()->getShortDescription(),
                'longDescription' => $space->getParent()->getLongDescription(),
                'image' => $space->getParent()->getImage(),
                'coverImage' => $space->getParent()->getCoverImage(),
                'site' => $space->getParent()->getSite(),
                'email' => $space->getParent()->getEmail(),
                'phoneNumber' => $space->getParent()->getPhoneNumber(),
                'maxCapacity' => $space->getParent()->getMaxCapacity(),
                'isAccessible' => $space->getParent()->isAccessible(),
                'isDraft' => false,
                'address' => [
                    'id' => 'b1b3eddd-3eac-4d96-97b5-1662767ae5f6',
                    'street' => 'Rua Doutor João Moreira',
                    'number' => '540',
                    'neighborhood' => 'Centro',
                    'complement' => 'Complexo Estação das Artes',
                    'city' => [
                        'id' => '97847c18-ac1c-4a00-93d4-b4a3e72a262c',
                    ],
                    'zipcode' => '60030000',
                ],
                'createdBy' => ['id' => AgentFixtures::AGENT_ID_1],
                'extraFields' => [
                    'type' => 'Instituição Cultural',
                    'description' => 'A Secretaria da Cultura (SECULT) é responsável por fomentar a arte e a cultura no estado, organizando eventos e oferecendo apoio a iniciativas locais.',
                    'location' => 'Complexo Estação das Artes - R. Dr. João Moreira, 540 - Centro, Fortaleza - CE, 60030-000',
                ],
                'activityAreas' => [
                    [
                        'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_2,
                        'name' => 'Música',
                    ],
                    [
                        'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_3,
                        'name' => 'Teatro',
                    ],
                    [
                        'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_10,
                        'name' => 'Fotografia',
                    ],
                ],
                'tags' => [
                    [
                        'id' => TagFixtures::TAG_ID_1,
                        'name' => 'Cultura',
                    ],
                    [
                        'id' => TagFixtures::TAG_ID_2,
                        'name' => 'Tecnologia',
                    ],
                ],
                'accessibilities' => [
                    [
                        'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_1,
                        'name' => 'Rampas',
                        'description' => 'Rampas de acesso para cadeirantes',
                    ],
                    [
                        'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_2,
                        'name' => 'Elevadores',
                        'description' => 'Elevadores adaptados para acessibilidade',
                    ],
                    [
                        'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_4,
                        'name' => 'Banheiros adaptados',
                        'description' => 'Banheiros com adaptações para pessoas com deficiência',
                    ],
                    [
                        'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_9,
                        'name' => 'Sinalização tátil',
                        'description' => 'Sinalização tátil para orientação de pessoas com deficiência visual',
                    ],
                ],
                'portfolio' => [
                    [
                        'id' => PhotoFixtures::PHOTO_ID_1,
                        'image' => $this->findImage($client, PhotoFixtures::PHOTO_ID_1),
                        'description' => 'Fachada principal do espaço cultural',
                        'createdAt' => '2024-07-10T11:30:00+00:00',
                        'updatedAt' => null,
                        'deletedAt' => null,
                    ],
                    [
                        'id' => PhotoFixtures::PHOTO_ID_2,
                        'image' => $this->findImage($client, PhotoFixtures::PHOTO_ID_2),
                        'description' => 'Auditório com capacidade para 200 pessoas',
                        'createdAt' => '2024-07-10T11:35:00+00:00',
                        'updatedAt' => null,
                        'deletedAt' => null,
                    ],
                    [
                        'id' => PhotoFixtures::PHOTO_ID_3,
                        'image' => $this->findImage($client, PhotoFixtures::PHOTO_ID_3),
                        'description' => 'Galeria de arte contemporânea',
                        'createdAt' => '2024-07-11T10:00:00+00:00',
                        'updatedAt' => null,
                        'deletedAt' => null,
                    ],
                ],
                'createdAt' => '2024-07-10T11:30:00+00:00',
                'updatedAt' => '2024-07-10T12:20:00+00:00',
                'deletedAt' => null,
                'spaceType' => $space->getParent()->getSpaceType()->toArray(),
            ],
            'extraFields' => [
                'type' => 'Cultural',
                'description' => 'É um espaço cultural que reúne artesãos de todo o Brasil para celebrar a cultura nordestina.',
                'location' => 'Recife, Pernambuco',
                'capacity' => 100,
            ],
            'activityAreas' => [
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_1,
                    'name' => 'Artes Visuais',
                ],
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_2,
                    'name' => 'Música',
                ],
            ],
            'tags' => [],
            'accessibilities' => [],
            'portfolio' => [],
            'createdAt' => $space->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => null,
            'deletedAt' => null,
            'spaceType' => $space->getSpaceType()->toArray(),
        ]);
    }

    #[DataProvider('provideValidationCreateCases')]
    public function testValidationCreate(array $requestBody, array $expectedErrors): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationCreateCases(): array
    {
        $requestBody = SpaceTestFixtures::partial();

        return [
            'missing required fields' => [
                'requestBody' => [],
                'expectedErrors' => [
                    ['field' => 'id', 'message' => 'This value should not be blank.'],
                    ['field' => 'name', 'message' => 'This value should not be blank.'],
                    ['field' => 'createdBy', 'message' => 'This value should not be blank.'],
                ],
            ],
            'id is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['id' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'id', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'name is not a string' => [
                'requestBody' => array_merge($requestBody, ['name' => 123]),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value should be of type string.'],
                ],
            ],
            'name is too short' => [
                'requestBody' => array_merge($requestBody, ['name' => 'a']),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'name is too long' => [
                'requestBody' => array_merge($requestBody, ['name' => str_repeat('a', 101)]),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too long. It should have 100 characters or less.'],
                ],
            ],
            'createdBy should exist' => [
                'requestBody' => array_merge($requestBody, ['createdBy' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'createdBy', 'message' => 'This id does not exist.'],
                ],
            ],
            'parent should exist' => [
                'requestBody' => array_merge($requestBody, ['parent' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'parent', 'message' => 'This id does not exist.'],
                ],
            ],
            'extraFields should be a valid JSON' => [
                'requestBody' => array_merge($requestBody, ['extraFields' => 'invalid-json']),
                'expectedErrors' => [
                    ['field' => 'extraFields', 'message' => 'This value should be of type json object.'],
                ],
            ],
            'shortDescription should be string' => [
                'requestBody' => array_merge($requestBody, ['shortDescription' => 123]),
                'expectedErrors' => [
                    ['field' => 'shortDescription', 'message' => 'This value should be of type string.'],
                ],
            ],
            'shortDescription too long' => [
                'requestBody' => array_merge($requestBody, ['shortDescription' => str_repeat('a', 256)]),
                'expectedErrors' => [
                    ['field' => 'shortDescription', 'message' => 'This value is too long. It should have 255 characters or less.'],
                ],
            ],
            'longDescription should be string' => [
                'requestBody' => array_merge($requestBody, ['longDescription' => 123]),
                'expectedErrors' => [
                    ['field' => 'longDescription', 'message' => 'This value should be of type string.'],
                ],
            ],
            'site should be string' => [
                'requestBody' => array_merge($requestBody, ['site' => 123]),
                'expectedErrors' => [
                    ['field' => 'site', 'message' => 'This value should be of type string.'],
                ],
            ],
            'site too long' => [
                'requestBody' => array_merge($requestBody, ['site' => str_repeat('a', 256)]),
                'expectedErrors' => [
                    ['field' => 'site', 'message' => 'This value is too long. It should have 255 characters or less.'],
                ],
            ],
            'email should be string' => [
                'requestBody' => array_merge($requestBody, ['email' => 123]),
                'expectedErrors' => [
                    ['field' => 'email', 'message' => 'This value should be of type string.'],
                ],
            ],
            'email too long' => [
                'requestBody' => array_merge($requestBody, ['email' => str_repeat('a', 256)]),
                'expectedErrors' => [
                    ['field' => 'email', 'message' => 'This value is too long. It should have 255 characters or less.'],
                ],
            ],
            'email should be valid' => [
                'requestBody' => array_merge($requestBody, ['email' => 'invalid-email']),
                'expectedErrors' => [
                    ['field' => 'email', 'message' => 'This value is not a valid email address.'],
                ],
            ],
            'phoneNumber should be string' => [
                'requestBody' => array_merge($requestBody, ['phoneNumber' => 123]),
                'expectedErrors' => [
                    ['field' => 'phoneNumber', 'message' => 'This value should be of type string.'],
                ],
            ],
            'phoneNumber too long' => [
                'requestBody' => array_merge($requestBody, ['phoneNumber' => str_repeat('a', 21)]),
                'expectedErrors' => [
                    ['field' => 'phoneNumber', 'message' => 'This value is too long. It should have 20 characters or less.'],
                ],
            ],
            'maxCapacity should be integer' => [
                'requestBody' => array_merge($requestBody, ['maxCapacity' => 'invalid']),
                'expectedErrors' => [
                    ['field' => 'maxCapacity', 'message' => 'This value should be of type integer.'],
                ],
            ],
            'isAccessible should be boolean' => [
                'requestBody' => array_merge($requestBody, ['isAccessible' => 'invalid']),
                'expectedErrors' => [
                    ['field' => 'isAccessible', 'message' => 'This value should be of type boolean.'],
                ],
            ],
        ];
    }

    public function testGet(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_GET, self::BASE_URL);
        $response = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertCount(9, json_decode($response));

        /** @var Space $space */
        $space = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Space::class, SpaceFixtures::SPACE_ID_1);

        $this->assertJsonContains([
            'id' => SpaceFixtures::SPACE_ID_1,
            'name' => 'SECULT',
            'shortDescription' => $space->getShortDescription(),
            'image' => $space->getImage(),
            'isAccessible' => $space->isAccessible(),
            'isDraft' => false,
            'address' => [
                'id' => 'b1b3eddd-3eac-4d96-97b5-1662767ae5f6',
                'street' => 'Rua Doutor João Moreira',
                'number' => '540',
                'neighborhood' => 'Centro',
                'complement' => 'Complexo Estação das Artes',
                'city' => [
                    'id' => '97847c18-ac1c-4a00-93d4-b4a3e72a262c',
                ],
                'zipcode' => '60030000',
            ],
            'createdBy' => [
                'id' => AgentFixtures::AGENT_ID_1,
            ],
            'activityAreas' => [
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_2,
                    'name' => 'Música',
                ],
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_3,
                    'name' => 'Teatro',
                ],
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_10,
                    'name' => 'Fotografia',
                ],
            ],
            'tags' => [
                [
                    'id' => TagFixtures::TAG_ID_1,
                    'name' => 'Cultura',
                ],
                [
                    'id' => TagFixtures::TAG_ID_2,
                    'name' => 'Tecnologia',
                ],
            ],
            'accessibilities' => [
                [
                    'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_1,
                    'name' => 'Rampas',
                    'description' => 'Rampas de acesso para cadeirantes',
                ],
                [
                    'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_2,
                    'name' => 'Elevadores',
                    'description' => 'Elevadores adaptados para acessibilidade',
                ],
                [
                    'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_4,
                    'name' => 'Banheiros adaptados',
                    'description' => 'Banheiros com adaptações para pessoas com deficiência',
                ],
                [
                    'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_9,
                    'name' => 'Sinalização tátil',
                    'description' => 'Sinalização tátil para orientação de pessoas com deficiência visual',
                ],
            ],
            'createdAt' => '2024-07-10T11:30:00+00:00',
            'updatedAt' => '2024-07-10T12:20:00+00:00',
            'deletedAt' => null,
            'spaceType' => $space->getSpaceType()->toArray(),
        ]);
    }

    public function testGetItem(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, SpaceFixtures::SPACE_ID_3);

        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        /* @var Space $space */
        $space = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Space::class, SpaceFixtures::SPACE_ID_3);

        $this->assertResponseBodySame([
            'id' => '608756eb-4830-49f2-ae14-1160ca5252f4',
            'name' => 'Galeria Caatinga',
            'shortDescription' => 'Galeria de Arte',
            'longDescription' => 'A Galeria Caatinga é especializada em exposições de artistas regionais, com foco na arte nordestina e obras inspiradas pela fauna e flora do sertão.',
            'image' => $space->getImage(),
            'coverImage' => null,
            'site' => $space->getSite(),
            'email' => $space->getEmail(),
            'phoneNumber' => $space->getPhoneNumber(),
            'maxCapacity' => $space->getMaxCapacity(),
            'isAccessible' => $space->isAccessible(),
            'isDraft' => false,
            'address' => [
                'id' => '425bdb7a-1ea2-41b5-bcb8-3511ef8f750a',
                'street' => 'Quadra SQS 102',
                'number' => '5',
                'neighborhood' => 'Asa Sul',
                'complement' => 'Bloco A',
                'city' => [
                    'id' => 'f6005001-9abf-4295-8ab5-572d54ec1ba0',
                ],
                'zipcode' => '70330000',
            ],
            'createdBy' => [
                'id' => '84a5b3d1-a7a4-49a6-aff8-902a325f97f9',
            ],
            'parent' => [
                'id' => 'ae32b8a5-25a8-4b80-b415-4237a8484186',
                'name' => 'Sítio das Artes',
                'shortDescription' => 'Centro Cultural',
                'longDescription' => 'O Sítio das Artes é um espaço dedicado à promoção de atividades culturais e oficinas artísticas, com uma vasta programação para todas as idades.',
                'image' => $space->getParent()->getImage(),
                'coverImage' => null,
                'site' => $space->getParent()->getSite(),
                'email' => $space->getParent()->getEmail(),
                'phoneNumber' => $space->getParent()->getPhoneNumber(),
                'maxCapacity' => 100,
                'isAccessible' => true,
                'isDraft' => false,
                'address' => [
                    'id' => 'b8636a9e-3906-4751-b4a9-7a24995813aa',
                    'street' => 'Rua Vinte e Oito de Setembro',
                    'number' => '250',
                    'neighborhood' => 'Centro',
                    'complement' => 'Apto 202',
                    'city' => [
                        'id' => '60e37453-19a2-4ff8-bed7-f08e28d14f78',
                    ],
                    'zipcode' => '65010000',
                ],
                'createdBy' => [
                    'id' => '0cc8c682-b0cd-4cb3-bd9d-41a9161b3566',
                ],
                'parent' => null,
                'extraFields' => [
                    'type' => 'Centro Cultural',
                    'description' => 'O Sítio das Artes é um espaço dedicado à promoção de atividades culturais e oficinas artísticas, com uma vasta programação para todas as idades.',
                    'location' => 'Av. das Artes, 123 – Fortaleza/CE – CEP: 60123-123',
                ],
                'activityAreas' => [
                    [
                        'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_1,
                        'name' => 'Artes Visuais',
                    ],
                    [
                        'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_2,
                        'name' => 'Música',
                    ],
                    [
                        'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_4,
                        'name' => 'Dança',
                    ],
                ],
                'tags' => [
                    [
                        'id' => TagFixtures::TAG_ID_3,
                        'name' => 'Sustentabilidade',
                    ],
                    [
                        'id' => TagFixtures::TAG_ID_4,
                        'name' => 'Social',
                    ],
                ],
                'accessibilities' => [
                    [
                        'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_1,
                        'name' => 'Rampas',
                        'description' => 'Rampas de acesso para cadeirantes',
                    ],
                    [
                        'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_4,
                        'name' => 'Banheiros adaptados',
                        'description' => 'Banheiros com adaptações para pessoas com deficiência',
                    ],
                ],
                'portfolio' => [
                    [
                        'id' => PhotoFixtures::PHOTO_ID_4,
                        'image' => $this->findImage($client, PhotoFixtures::PHOTO_ID_4),
                        'description' => 'Espaço para oficinas e workshops',
                        'createdAt' => '2024-07-11T10:05:00+00:00',
                        'updatedAt' => null,
                        'deletedAt' => null,
                    ],
                    [
                        'id' => PhotoFixtures::PHOTO_ID_5,
                        'image' => $this->findImage($client, PhotoFixtures::PHOTO_ID_5),
                        'description' => 'Área de convivência',
                        'createdAt' => '2024-07-12T09:00:00+00:00',
                        'updatedAt' => null,
                        'deletedAt' => null,
                    ],
                ],
                'createdAt' => '2024-07-11T10:49:00+00:00',
                'updatedAt' => null,
                'deletedAt' => null,
                'spaceType' => $space->getParent()->getSpaceType()->toArray(),
            ],
            'extraFields' => [
                'type' => 'Galeria de Arte',
                'description' => 'A Galeria Caatinga é especializada em exposições de artistas regionais, com foco na arte nordestina e obras inspiradas pela fauna e flora do sertão.',
                'location' => 'Rua do Sertão, 123 – Fortaleza/CE – CEP: 60123-456',
            ],
            'activityAreas' => [
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_1,
                    'name' => 'Artes Visuais',
                ],
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_2,
                    'name' => 'Música',
                ],
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_10,
                    'name' => 'Fotografia',
                ],
            ],
            'tags' => [
                [
                    'id' => TagFixtures::TAG_ID_5,
                    'name' => 'Educação',
                ],
                [
                    'id' => TagFixtures::TAG_ID_6,
                    'name' => 'Tradição',
                ],
            ],
            'accessibilities' => [
                [
                    'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_2,
                    'name' => 'Elevadores',
                    'description' => 'Elevadores adaptados para acessibilidade',
                ],
                [
                    'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_4,
                    'name' => 'Banheiros adaptados',
                    'description' => 'Banheiros com adaptações para pessoas com deficiência',
                ],
                [
                    'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_9,
                    'name' => 'Sinalização tátil',
                    'description' => 'Sinalização tátil para orientação de pessoas com deficiência visual',
                ],
            ],
            'portfolio' => [
                [
                    'id' => PhotoFixtures::PHOTO_ID_6,
                    'image' => $this->findImage($client, PhotoFixtures::PHOTO_ID_6),
                    'description' => 'Biblioteca com acervo local',
                    'createdAt' => '2024-07-12T09:30:00+00:00',
                    'updatedAt' => null,
                    'deletedAt' => null,
                ],
            ],
            'createdAt' => '2024-07-16T17:22:00+00:00',
            'updatedAt' => null,
            'deletedAt' => null,
            'spaceType' => $space->getSpaceType()->toArray(),
        ]);
    }

    public function testGetAResourceWhenNotFound(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_GET, sprintf('%s/%s', self::BASE_URL, Uuid::v4()->toRfc4122()));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested Space was not found.',
            ],
        ]);
    }

    public function testDeleteAResourceWhenNotFound(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_DELETE, sprintf('%s/%s', self::BASE_URL, Uuid::v4()->toRfc4122()));

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested Space was not found.',
            ],
        ]);
    }

    public function testDeleteASpaceItemWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, SpaceFixtures::SPACE_ID_3);

        $client->request(Request::METHOD_DELETE, $url);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $client->request(Request::METHOD_GET, $url);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testCanUpdate(): void
    {
        $requestBody = SpaceTestFixtures::complete();
        unset($requestBody['id']);
        unset($requestBody['image']);

        $url = sprintf('%s/%s', self::BASE_URL, SpaceFixtures::SPACE_ID_4);
        $client = self::apiClient();

        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        /** @var Space $space */
        $space = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Space::class, SpaceFixtures::SPACE_ID_4);

        $this->assertResponseBodySame([
            'id' => SpaceFixtures::SPACE_ID_4,
            'name' => $requestBody['name'],
            'shortDescription' => $space->getShortDescription(),
            'longDescription' => $space->getLongDescription(),
            'image' => $space->getImage(),
            'coverImage' => $space->getCoverImage(),
            'site' => $space->getSite(),
            'email' => $space->getEmail(),
            'phoneNumber' => $space->getPhoneNumber(),
            'maxCapacity' => $space->getMaxCapacity(),
            'isAccessible' => $space->isAccessible(),
            'isDraft' => false,
            'address' => [
                'id' => 'fd64752a-c7ed-44ff-b092-44076dea4b4c',
                'street' => 'Rua dos Andradas',
                'number' => '896',
                'neighborhood' => 'Centro Histórico',
                'complement' => 'de 0835 a 0999 - lado ímpar',
                'city' => [
                    'id' => 'fb22ee01-1806-481f-af83-7deb980c89c3',
                ],
                'zipcode' => '90020005',
            ],
            'createdBy' => ['id' => AgentFixtures::AGENT_ID_1],
            'parent' => [
                'id' => SpaceFixtures::SPACE_ID_1,
                'name' => 'SECULT',
                'shortDescription' => 'Secretaria da Cultura',
                'longDescription' => 'A Secretaria da Cultura (SECULT) é responsável por fomentar a arte e a cultura no estado, organizando eventos e oferecendo apoio a iniciativas locais.',
                'image' => $space->getParent()->getImage(),
                'coverImage' => null,
                'site' => $space->getParent()->getSite(),
                'email' => $space->getParent()->getEmail(),
                'phoneNumber' => $space->getParent()->getPhoneNumber(),
                'maxCapacity' => 100,
                'isAccessible' => true,
                'isDraft' => false,
                'address' => [
                    'id' => 'b1b3eddd-3eac-4d96-97b5-1662767ae5f6',
                    'street' => 'Rua Doutor João Moreira',
                    'number' => '540',
                    'neighborhood' => 'Centro',
                    'complement' => 'Complexo Estação das Artes',
                    'city' => [
                        'id' => '97847c18-ac1c-4a00-93d4-b4a3e72a262c',
                    ],
                    'zipcode' => '60030000',
                ],
                'createdBy' => [
                    'id' => AgentFixtures::AGENT_ID_1,
                ],
                'extraFields' => [
                    'type' => 'Instituição Cultural',
                    'description' => 'A Secretaria da Cultura (SECULT) é responsável por fomentar a arte e a cultura no estado, organizando eventos e oferecendo apoio a iniciativas locais.',
                    'location' => 'Complexo Estação das Artes - R. Dr. João Moreira, 540 - Centro, Fortaleza - CE, 60030-000',
                ],
                'activityAreas' => [
                    [
                        'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_2,
                        'name' => 'Música',
                    ],
                    [
                        'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_3,
                        'name' => 'Teatro',
                    ],
                    [
                        'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_10,
                        'name' => 'Fotografia',
                    ],
                ],
                'tags' => [
                    [
                        'id' => TagFixtures::TAG_ID_1,
                        'name' => 'Cultura',
                    ],
                    [
                        'id' => TagFixtures::TAG_ID_2,
                        'name' => 'Tecnologia',
                    ],
                ],
                'accessibilities' => [
                    [
                        'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_1,
                        'name' => 'Rampas',
                        'description' => 'Rampas de acesso para cadeirantes',
                    ],
                    [
                        'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_2,
                        'name' => 'Elevadores',
                        'description' => 'Elevadores adaptados para acessibilidade',
                    ],
                    [
                        'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_4,
                        'name' => 'Banheiros adaptados',
                        'description' => 'Banheiros com adaptações para pessoas com deficiência',
                    ],
                    [
                        'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_9,
                        'name' => 'Sinalização tátil',
                        'description' => 'Sinalização tátil para orientação de pessoas com deficiência visual',
                    ],
                ],
                'portfolio' => [
                    [
                        'id' => PhotoFixtures::PHOTO_ID_1,
                        'image' => $this->findImage($client, PhotoFixtures::PHOTO_ID_1),
                        'description' => 'Fachada principal do espaço cultural',
                        'createdAt' => '2024-07-10T11:30:00+00:00',
                        'updatedAt' => null,
                        'deletedAt' => null,
                    ],
                    [
                        'id' => PhotoFixtures::PHOTO_ID_2,
                        'image' => $this->findImage($client, PhotoFixtures::PHOTO_ID_2),
                        'description' => 'Auditório com capacidade para 200 pessoas',
                        'createdAt' => '2024-07-10T11:35:00+00:00',
                        'updatedAt' => null,
                        'deletedAt' => null,
                    ],
                    [
                        'id' => PhotoFixtures::PHOTO_ID_3,
                        'image' => $this->findImage($client, PhotoFixtures::PHOTO_ID_3),
                        'description' => 'Galeria de arte contemporânea',
                        'createdAt' => '2024-07-11T10:00:00+00:00',
                        'updatedAt' => null,
                        'deletedAt' => null,
                    ],
                ],
                'createdAt' => '2024-07-10T11:30:00+00:00',
                'updatedAt' => '2024-07-10T12:20:00+00:00',
                'deletedAt' => null,
                'spaceType' => $space->getParent()->getSpaceType()->toArray(),
            ],
            'extraFields' => [
                'type' => 'Cultural',
                'description' => 'É um espaço cultural que reúne artesãos de todo o Brasil para celebrar a cultura nordestina.',
                'location' => 'Recife, Pernambuco',
                'capacity' => 100,
            ],
            'activityAreas' => [
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_1,
                    'name' => 'Artes Visuais',
                ],
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_2,
                    'name' => 'Música',
                ],
            ],
            'tags' => [
                [
                    'id' => TagFixtures::TAG_ID_7,
                    'name' => 'Juventude',
                ],
                [
                    'id' => TagFixtures::TAG_ID_8,
                    'name' => 'Oficina',
                ],
            ],
            'accessibilities' => [
                [
                    'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_1,
                    'name' => 'Rampas',
                    'description' => 'Rampas de acesso para cadeirantes',
                ],
                [
                    'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_4,
                    'name' => 'Banheiros adaptados',
                    'description' => 'Banheiros com adaptações para pessoas com deficiência',
                ],
            ],
            'portfolio' => [],
            'createdAt' => $space->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => $space->getUpdatedAt()->format(DateTimeInterface::ATOM),
            'deletedAt' => null,
            'spaceType' => $space->getSpaceType()->toArray(),
        ]);
    }

    public function testCanUpdateImageWithMultipartFormData(): void
    {
        $file = ImageTestFixtures::getImageValid();

        $url = sprintf('%s/%s/images', self::BASE_URL, SpaceFixtures::SPACE_ID_1);

        $client = self::apiClient();
        $client->request(
            Request::METHOD_POST,
            $url,
            files: ['image' => $file],
            server: [
                'CONTENT_TYPE' => 'multipart/form-data',
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        /* @var Space $space */
        $space = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Space::class, SpaceFixtures::SPACE_ID_1);

        $this->assertResponseBodySame([
            'id' => SpaceFixtures::SPACE_ID_1,
            'name' => 'SECULT',
            'shortDescription' => 'Secretaria da Cultura',
            'longDescription' => 'A Secretaria da Cultura (SECULT) é responsável por fomentar a arte e a cultura no estado, organizando eventos e oferecendo apoio a iniciativas locais.',
            'image' => $space->getImage(),
            'coverImage' => null,
            'site' => $space->getSite(),
            'email' => $space->getEmail(),
            'phoneNumber' => $space->getPhoneNumber(),
            'maxCapacity' => 100,
            'isAccessible' => true,
            'isDraft' => false,
            'address' => [
                'id' => 'b1b3eddd-3eac-4d96-97b5-1662767ae5f6',
                'street' => 'Rua Doutor João Moreira',
                'number' => '540',
                'neighborhood' => 'Centro',
                'complement' => 'Complexo Estação das Artes',
                'city' => [
                    'id' => '97847c18-ac1c-4a00-93d4-b4a3e72a262c',
                ],
                'zipcode' => '60030000',
            ],
            'createdBy' => [
                'id' => AgentFixtures::AGENT_ID_1,
            ],
            'parent' => null,
            'extraFields' => [
                'type' => 'Instituição Cultural',
                'description' => 'A Secretaria da Cultura (SECULT) é responsável por fomentar a arte e a cultura no estado, organizando eventos e oferecendo apoio a iniciativas locais.',
                'location' => 'Complexo Estação das Artes - R. Dr. João Moreira, 540 - Centro, Fortaleza - CE, 60030-000',
            ],
            'activityAreas' => [
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_2,
                    'name' => 'Música',
                ],
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_3,
                    'name' => 'Teatro',
                ],
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_10,
                    'name' => 'Fotografia',
                ],
            ],
            'tags' => [
                [
                    'id' => TagFixtures::TAG_ID_1,
                    'name' => 'Cultura',
                ],
                [
                    'id' => TagFixtures::TAG_ID_2,
                    'name' => 'Tecnologia',
                ],
            ],
            'accessibilities' => [
                [
                    'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_1,
                    'name' => 'Rampas',
                    'description' => 'Rampas de acesso para cadeirantes',
                ],
                [
                    'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_2,
                    'name' => 'Elevadores',
                    'description' => 'Elevadores adaptados para acessibilidade',
                ],
                [
                    'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_4,
                    'name' => 'Banheiros adaptados',
                    'description' => 'Banheiros com adaptações para pessoas com deficiência',
                ],
                [
                    'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_9,
                    'name' => 'Sinalização tátil',
                    'description' => 'Sinalização tátil para orientação de pessoas com deficiência visual',
                ],
            ],
            'portfolio' => [
                [
                    'id' => PhotoFixtures::PHOTO_ID_1,
                    'image' => $this->findImage($client, PhotoFixtures::PHOTO_ID_1),
                    'description' => 'Fachada principal do espaço cultural',
                    'createdAt' => '2024-07-10T11:30:00+00:00',
                    'updatedAt' => null,
                    'deletedAt' => null,
                ],
                [
                    'id' => PhotoFixtures::PHOTO_ID_2,
                    'image' => $this->findImage($client, PhotoFixtures::PHOTO_ID_2),
                    'description' => 'Auditório com capacidade para 200 pessoas',
                    'createdAt' => '2024-07-10T11:35:00+00:00',
                    'updatedAt' => null,
                    'deletedAt' => null,
                ],
                [
                    'id' => PhotoFixtures::PHOTO_ID_3,
                    'image' => $this->findImage($client, PhotoFixtures::PHOTO_ID_3),
                    'description' => 'Galeria de arte contemporânea',
                    'createdAt' => '2024-07-11T10:00:00+00:00',
                    'updatedAt' => null,
                    'deletedAt' => null,
                ],
            ],
            'createdAt' => $space->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => $space->getUpdatedAt()->format(DateTimeInterface::ATOM),
            'deletedAt' => null,
            'spaceType' => $space->getSpaceType()->toArray(),
        ]);
    }

    #[DataProvider('provideValidationUpdateCases')]
    public function testValidationUpdate(array $requestBody, array $expectedErrors): void
    {
        $client = self::apiClient();
        $url = sprintf('%s/%s', self::BASE_URL, SpaceFixtures::SPACE_ID_3);
        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationUpdateCases(): array
    {
        $requestBody = SpaceTestFixtures::partial();

        return [
            'name should be string' => [
                'requestBody' => array_merge($requestBody, ['name' => 123]),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value should be of type string.'],
                ],
            ],
            'name too short' => [
                'requestBody' => array_merge($requestBody, ['name' => 'a']),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'name too long' => [
                'requestBody' => array_merge($requestBody, ['name' => str_repeat('a', 101)]),
                'expectedErrors' => [
                    ['field' => 'name', 'message' => 'This value is too long. It should have 100 characters or less.'],
                ],
            ],
            'parent should exist' => [
                'requestBody' => array_merge($requestBody, ['parent' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'parent', 'message' => 'This id does not exist.'],
                ],
            ],
            'createdBy should exist' => [
                'requestBody' => array_merge($requestBody, ['createdBy' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'createdBy', 'message' => 'This id does not exist.'],
                ],
            ],
            'extraFields should be a valid JSON' => [
                'requestBody' => array_merge($requestBody, ['extraFields' => 'invalid-json']),
                'expectedErrors' => [
                    ['field' => 'extraFields', 'message' => 'This value should be of type json object.'],
                ],
            ],
            'shortDescription should be string' => [
                'requestBody' => array_merge($requestBody, ['shortDescription' => 123]),
                'expectedErrors' => [
                    ['field' => 'shortDescription', 'message' => 'This value should be of type string.'],
                ],
            ],
            'shortDescription too long' => [
                'requestBody' => array_merge($requestBody, ['shortDescription' => str_repeat('a', 256)]),
                'expectedErrors' => [
                    ['field' => 'shortDescription', 'message' => 'This value is too long. It should have 255 characters or less.'],
                ],
            ],
            'longDescription should be string' => [
                'requestBody' => array_merge($requestBody, ['longDescription' => 123]),
                'expectedErrors' => [
                    ['field' => 'longDescription', 'message' => 'This value should be of type string.'],
                ],
            ],
            'site should be string' => [
                'requestBody' => array_merge($requestBody, ['site' => 123]),
                'expectedErrors' => [
                    ['field' => 'site', 'message' => 'This value should be of type string.'],
                ],
            ],
            'site too long' => [
                'requestBody' => array_merge($requestBody, ['site' => str_repeat('a', 256)]),
                'expectedErrors' => [
                    ['field' => 'site', 'message' => 'This value is too long. It should have 255 characters or less.'],
                ],
            ],
            'email should be string' => [
                'requestBody' => array_merge($requestBody, ['email' => 123]),
                'expectedErrors' => [
                    ['field' => 'email', 'message' => 'This value should be of type string.'],
                ],
            ],
            'email too long' => [
                'requestBody' => array_merge($requestBody, ['email' => str_repeat('a', 256)]),
                'expectedErrors' => [
                    ['field' => 'email', 'message' => 'This value is too long. It should have 255 characters or less.'],
                ],
            ],
            'email should be valid' => [
                'requestBody' => array_merge($requestBody, ['email' => 'invalid-email']),
                'expectedErrors' => [
                    ['field' => 'email', 'message' => 'This value is not a valid email address.'],
                ],
            ],
            'phoneNumber should be string' => [
                'requestBody' => array_merge($requestBody, ['phoneNumber' => 123]),
                'expectedErrors' => [
                    ['field' => 'phoneNumber', 'message' => 'This value should be of type string.'],
                ],
            ],
            'phoneNumber too long' => [
                'requestBody' => array_merge($requestBody, ['phoneNumber' => str_repeat('a', 21)]),
                'expectedErrors' => [
                    ['field' => 'phoneNumber', 'message' => 'This value is too long. It should have 20 characters or less.'],
                ],
            ],
            'maxCapacity should be integer' => [
                'requestBody' => array_merge($requestBody, ['maxCapacity' => 'invalid']),
                'expectedErrors' => [
                    ['field' => 'maxCapacity', 'message' => 'This value should be of type integer.'],
                ],
            ],
            'maxCapacity should be at least 1' => [
                'requestBody' => array_merge($requestBody, ['maxCapacity' => 0]),
                'expectedErrors' => [
                    ['field' => 'maxCapacity', 'message' => 'This value should be 1 or more.'],
                ],
            ],
            'isAccessible should be boolean' => [
                'requestBody' => array_merge($requestBody, ['isAccessible' => 'invalid']),
                'expectedErrors' => [
                    ['field' => 'isAccessible', 'message' => 'This value should be of type boolean.'],
                ],
            ],
            'isDraft should be boolean' => [
                'requestBody' => array_merge($requestBody, ['isDraft' => 'invalid']),
                'expectedErrors' => [
                    ['field' => 'isDraft', 'message' => 'This value should be of type boolean.'],
                ],
            ],
        ];
    }

    #[DataProvider('provideValidationUpdateImageCases')]
    public function testValidationUpdateImage(array $requestBody, $file, array $expectedErrors): void
    {
        $url = sprintf('%s/%s/images', self::BASE_URL, SpaceFixtures::SPACE_ID_1);

        $client = self::apiClient();
        $client->request(
            Request::METHOD_POST,
            $url,
            files: ['image' => $file],
            server: [
                'CONTENT_TYPE' => 'multipart/form-data',
            ]
        );

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationUpdateImageCases(): array
    {
        $requestBody = SpaceTestFixtures::partial();
        unset($requestBody['id']);

        return [
            'image not supported' => [
                'requestBody' => $requestBody,
                'file' => ImageTestFixtures::getGif(),
                'expectedErrors' => [
                    ['field' => 'profileImage', 'message' => 'The mime type of the file is invalid ("image/gif"). Allowed mime types are "image/png", "image/jpg", "image/jpeg".'],
                ],
            ],
            'image size' => [
                'requestBody' => $requestBody,
                'file' => ImageTestFixtures::getImageMoreThan2mb(),
                'expectedErrors' => [
                    ['field' => 'profileImage', 'message' => 'The file is too large (2.5 MB). Allowed maximum size is 2 MB.'],
                ],
            ],
        ];
    }

    private function findImage(mixed $client, string $photoId): string
    {
        $response = json_decode($client->getResponse()->getContent(), true);

        $locations = [
            $response['portfolio'] ?? [],
            $response['parent']['portfolio'] ?? [],
        ];

        foreach ($locations as $portfolio) {
            foreach ($portfolio as $item) {
                if (($item['id'] ?? '') === $photoId) {
                    return $item['image'];
                }
            }
        }

        return 'image not found';
    }
}
