<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\DataFixtures\Entity\ActivityAreaFixtures;
use App\DataFixtures\Entity\AgentFixtures;
use App\DataFixtures\Entity\CulturalLanguageFixtures;
use App\DataFixtures\Entity\EventFixtures;
use App\DataFixtures\Entity\EventTypeFixtures;
use App\DataFixtures\Entity\InitiativeFixtures;
use App\DataFixtures\Entity\SpaceFixtures;
use App\DataFixtures\Entity\TagFixtures;
use App\Entity\Event;
use App\Enum\AccessibilityInfoEnum;
use App\Enum\EventFormatEnum;
use App\Tests\AbstractApiTestCase;
use App\Tests\Fixtures\EventTestFixtures;
use App\Tests\Fixtures\ImageTestFixtures;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class EventApiControllerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/events';

    public function testCanCreateWithPartialRequestBody(): void
    {
        $client = static::apiClient();

        $requestBody = EventTestFixtures::partial();

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $event = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Event::class, $requestBody['id']);

        $this->assertResponseBodySame([
            'id' => $requestBody['id'],
            'name' => $requestBody['name'],
            'image' => null,
            'agentGroup' => ['id' => AgentFixtures::AGENT_ID_1],
            'space' => ['id' => SpaceFixtures::SPACE_ID_1],
            'initiative' => ['id' => InitiativeFixtures::INITIATIVE_ID_1],
            'parent' => null,
            'extraFields' => $event->getExtraFields(),
            'createdBy' => ['id' => self::getLoggedAgentId()],
            'coverImage' => null,
            'subtitle' => null,
            'shortDescription' => null,
            'longDescription' => null,
            'format' => EventFormatEnum::IN_PERSON->value,
            'eventType' => null,
            'startDate' => '2025-01-16T00:00:00+00:00',
            'endDate' => '2025-04-01T00:00:00+00:00',
            'activityAreas' => [],
            'tags' => [],
            'site' => null,
            'phoneNumber' => null,
            'maxCapacity' => 5000,
            'accessibleAudio' => AccessibilityInfoEnum::NOT_INFORMED->value,
            'accessibleLibras' => AccessibilityInfoEnum::NOT_INFORMED->value,
            'free' => true,
            'draft' => true,
            'culturalLanguages' => [],
            'createdAt' => $event->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => null,
            'deletedAt' => null,
        ]);
    }

    public function testCanCreateWithCompleteRequestBody(): void
    {
        $client = static::apiClient();

        $requestBody = EventTestFixtures::complete();

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $event = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Event::class, $requestBody['id']);

        $this->assertResponseBodySame([
            'id' => $requestBody['id'],
            'name' => $requestBody['name'],
            'image' => null,
            'agentGroup' => ['id' => AgentFixtures::AGENT_ID_1],
            'space' => ['id' => SpaceFixtures::SPACE_ID_1],
            'initiative' => ['id' => InitiativeFixtures::INITIATIVE_ID_1],
            'parent' => [
                'id' => EventFixtures::EVENT_ID_1,
                'name' => 'Festival Sertão Criativo',
                'image' => null,
                'agentGroup' => null,
                'space' => ['id' => SpaceFixtures::SPACE_ID_3],
                'initiative' => ['id' => InitiativeFixtures::INITIATIVE_ID_2],
                'parent' => null,
                'extraFields' => $event->getExtraFields(),
                'createdBy' => ['id' => AgentFixtures::AGENT_ID_1],
                'coverImage' => null,
                'subtitle' => 'Subtítulo de exemplo',
                'shortDescription' => null,
                'longDescription' => 'Uma descrição mais longa',
                'format' => EventFormatEnum::ONLINE->value,
                'eventType' => null,
                'startDate' => '2024-07-10T10:00:00+00:00',
                'endDate' => '2024-09-10T11:30:00+00:00',
                'activityAreas' => [
                    [
                        'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_1,
                        'name' => 'Artes Visuais',
                    ],
                    [
                        'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_3,
                        'name' => 'Teatro',
                    ],
                    [
                        'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_5,
                        'name' => 'Cinema',
                    ],
                ],
                'tags' => [],
                'site' => 'evento.com.br',
                'phoneNumber' => '8585998585',
                'maxCapacity' => 1000,
                'accessibleAudio' => AccessibilityInfoEnum::YES->value,
                'accessibleLibras' => AccessibilityInfoEnum::YES->value,
                'free' => false,
                'draft' => false,
                'culturalLanguages' => [
                    [
                        'id' => CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_1,
                        'name' => 'Arte e Música',
                        'description' => 'Pintura, escultura, literatura, dança, teatro, música e outras formas artísticas que comunicam ideias, histórias e sentimentos.',
                    ],
                    [
                        'id' => CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_2,
                        'name' => 'Costumes e Rituais',
                        'description' => 'Celebrações, festivais, cerimônias religiosas e ritos de passagem que expressam identidades culturais.',
                    ],
                    [
                        'id' => CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_3,
                        'name' => 'Moda e Vestuário',
                        'description' => 'Estilos de vestir que comunicam status social, afiliação cultural, crenças ou até protestos.',
                    ],
                ],
                'createdAt' => '2024-07-10T11:30:00+00:00',
                'updatedAt' => '2024-07-10T11:35:00+00:00',
                'deletedAt' => null,
            ],
            'extraFields' => $event->getExtraFields(),
            'createdBy' => ['id' => self::getLoggedAgentId()],
            'coverImage' => 'coverimage.jpg',
            'subtitle' => 'Subtítulo de exemplo',
            'shortDescription' => 'Descrição curta',
            'longDescription' => 'Uma descrição mais longa',
            'format' => EventFormatEnum::IN_PERSON->value,
            'eventType' => [
                'id' => EventTypeFixtures::EVENT_TYPE_ID_1,
                'name' => 'Show Musical',
            ],
            'startDate' => '2025-01-16T00:00:00+00:00',
            'endDate' => '2025-04-01T00:00:00+00:00',
            'activityAreas' => [
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_1,
                    'name' => 'Artes Visuais',
                ],
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_9,
                    'name' => 'Artesanato',
                ],
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_2,
                    'name' => 'Música',
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
            'site' => 'evento.com.br',
            'phoneNumber' => '8585998585',
            'maxCapacity' => 5000,
            'accessibleAudio' => AccessibilityInfoEnum::NOT_INFORMED->value,
            'accessibleLibras' => AccessibilityInfoEnum::NOT_INFORMED->value,
            'free' => true,
            'draft' => true,
            'culturalLanguages' => [],
            'createdAt' => $event->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => null,
            'deletedAt' => null,
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
        $requestBody = EventTestFixtures::partial();

        return [
            'missing required fields' => [
                'requestBody' => [],
                'expectedErrors' => [
                    ['field' => 'id', 'message' => 'This value should not be blank.'],
                    ['field' => 'name', 'message' => 'This value should not be blank.'],
                    ['field' => 'type', 'message' => 'This value should not be blank.'],
                    ['field' => 'startDate', 'message' => 'This value should not be blank.'],
                ],
            ],
            'id is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['id' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'id', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'name should be a string' => [
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
            'agentGroup is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['agentGroup' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'agentGroup', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'agentGroup should exist' => [
                'requestBody' => array_merge($requestBody, ['agentGroup' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'agentGroup', 'message' => 'This id does not exist.'],
                ],
            ],
            'space is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['space' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'space', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'space should exist' => [
                'requestBody' => array_merge($requestBody, ['space' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'space', 'message' => 'This id does not exist.'],
                ],
            ],
            'initiative is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['initiative' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'initiative', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'initiative should exist' => [
                'requestBody' => array_merge($requestBody, ['initiative' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'initiative', 'message' => 'This id does not exist.'],
                ],
            ],
            'parent is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['parent' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'parent', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'parent should exist' => [
                'requestBody' => array_merge($requestBody, ['parent' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'parent', 'message' => 'This id does not exist.'],
                ],
            ],
            'createdBy is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['createdBy' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'createdBy', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'createdBy should exist' => [
                'requestBody' => array_merge($requestBody, ['createdBy' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'createdBy', 'message' => 'This id does not exist.'],
                ],
            ],
            'coverImage should be a string' => [
                'requestBody' => array_merge($requestBody, ['coverImage' => 123]),
                'expectedErrors' => [
                    ['field' => 'coverImage', 'message' => 'This value should be of type string.'],
                ],
            ],
            'coverImage is too short' => [
                'requestBody' => array_merge($requestBody, ['coverImage' => 'a']),
                'expectedErrors' => [
                    ['field' => 'coverImage', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'coverImage is too long' => [
                'requestBody' => array_merge($requestBody, ['coverImage' => str_repeat('a', 256)]),
                'expectedErrors' => [
                    ['field' => 'coverImage', 'message' => 'This value is too long. It should have 255 characters or less.'],
                ],
            ],
            'subtitle should be a string' => [
                'requestBody' => array_merge($requestBody, ['subtitle' => 123]),
                'expectedErrors' => [
                    ['field' => 'subtitle', 'message' => 'This value should be of type string.'],
                ],
            ],
            'subtitle is too short' => [
                'requestBody' => array_merge($requestBody, ['subtitle' => 'a']),
                'expectedErrors' => [
                    ['field' => 'subtitle', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'subtitle is too long' => [
                'requestBody' => array_merge($requestBody, ['subtitle' => str_repeat('a', 256)]),
                'expectedErrors' => [
                    ['field' => 'subtitle', 'message' => 'This value is too long. It should have 255 characters or less.'],
                ],
            ],
            'shortDescription should be a string' => [
                'requestBody' => array_merge($requestBody, ['shortDescription' => 123]),
                'expectedErrors' => [
                    ['field' => 'shortDescription', 'message' => 'This value should be of type string.'],
                ],
            ],
            'shortDescription is too short' => [
                'requestBody' => array_merge($requestBody, ['shortDescription' => 'a']),
                'expectedErrors' => [
                    ['field' => 'shortDescription', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'shortDescription is too long' => [
                'requestBody' => array_merge($requestBody, ['shortDescription' => str_repeat('a', 256)]),
                'expectedErrors' => [
                    ['field' => 'shortDescription', 'message' => 'This value is too long. It should have 255 characters or less.'],
                ],
            ],
            'longDescription should be a string' => [
                'requestBody' => array_merge($requestBody, ['longDescription' => 123]),
                'expectedErrors' => [
                    ['field' => 'longDescription', 'message' => 'This value should be of type string.'],
                ],
            ],
            'type should be a integer' => [
                'requestBody' => array_merge($requestBody, ['type' => 'abc']),
                'expectedErrors' => [
                    ['field' => 'type', 'message' => 'This value should be of type integer.'],
                ],
            ],
            'type should be a valid choice' => [
                'requestBody' => array_merge($requestBody, ['type' => 5]),
                'expectedErrors' => [
                    ['field' => 'type', 'message' => 'The value you selected is not a valid choice.'],
                ],
            ],
            'endDate should be a string' => [
                'requestBody' => array_merge($requestBody, ['endDate' => 123]),
                'expectedErrors' => [
                    ['field' => 'endDate', 'message' => 'This value should be of type string.'],
                ],
            ],
            'endDate should be a valid choice' => [
                'requestBody' => array_merge($requestBody, ['endDate' => 'invalid-date']),
                'expectedErrors' => [
                    ['field' => 'endDate', 'message' => 'This value is not a valid datetime.'],
                ],
            ],
            'activityAreas should be an array' => [
                'requestBody' => array_merge($requestBody, ['activityAreas' => 123]),
                'expectedErrors' => [
                    ['field' => 'activityAreas', 'message' => 'This value should be of type iterable.'],
                ],
            ],
            'activityAreas item should be a uuid' => [
                'requestBody' => array_merge($requestBody, ['activityAreas' => ['invalid-uuid']]),
                'expectedErrors' => [
                    ['field' => 'activityAreas[0]', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'activityAreas should exists' => [
                'requestBody' => array_merge($requestBody, ['activityAreas' => [Uuid::v4()->toRfc4122()]]),
                'expectedErrors' => [
                    ['field' => 'activityAreas[0]', 'message' => 'This id does not exist.'],
                ],
            ],
            'tags should be an array' => [
                'requestBody' => array_merge($requestBody, ['tags' => 123]),
                'expectedErrors' => [
                    ['field' => 'tags', 'message' => 'This value should be of type iterable.'],
                ],
            ],
            'tags item should be a uuid' => [
                'requestBody' => array_merge($requestBody, ['tags' => ['invalid-uuid']]),
                'expectedErrors' => [
                    ['field' => 'tags[0]', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'tags should exists' => [
                'requestBody' => array_merge($requestBody, ['tags' => [Uuid::v4()->toRfc4122()]]),
                'expectedErrors' => [
                    ['field' => 'tags[0]', 'message' => 'This id does not exist.'],
                ],
            ],
            'site should be a string' => [
                'requestBody' => array_merge($requestBody, ['site' => 123]),
                'expectedErrors' => [
                    ['field' => 'site', 'message' => 'This value should be of type string.'],
                ],
            ],
            'site is too short' => [
                'requestBody' => array_merge($requestBody, ['site' => 'a']),
                'expectedErrors' => [
                    ['field' => 'site', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'site is too long' => [
                'requestBody' => array_merge($requestBody, ['site' => str_repeat('a', 256)]),
                'expectedErrors' => [
                    ['field' => 'site', 'message' => 'This value is too long. It should have 255 characters or less.'],
                ],
            ],
            'phoneNumber should be a string' => [
                'requestBody' => array_merge($requestBody, ['phoneNumber' => 123]),
                'expectedErrors' => [
                    ['field' => 'phoneNumber', 'message' => 'This value should be of type string.'],
                ],
            ],
            'phoneNumber is too short' => [
                'requestBody' => array_merge($requestBody, ['phoneNumber' => 'a']),
                'expectedErrors' => [
                    ['field' => 'phoneNumber', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'phoneNumber is too long' => [
                'requestBody' => array_merge($requestBody, ['phoneNumber' => str_repeat('a', 21)]),
                'expectedErrors' => [
                    ['field' => 'phoneNumber', 'message' => 'This value is too long. It should have 20 characters or less.'],
                ],
            ],
            'maxCapacity should be an integer' => [
                'requestBody' => array_merge($requestBody, ['maxCapacity' => '123']),
                'expectedErrors' => [
                    ['field' => 'maxCapacity', 'message' => 'This value should be of type integer.'],
                ],
            ],
            'accessibleAudio should be a string' => [
                'requestBody' => array_merge($requestBody, ['accessibleAudio' => 123]),
                'expectedErrors' => [
                    ['field' => 'accessibleAudio', 'message' => 'This value should be of type string.'],
                ],
            ],
            'accessibleAudio should be a valid choice' => [
                'requestBody' => array_merge($requestBody, ['accessibleAudio' => 'invalid-choice']),
                'expectedErrors' => [
                    ['field' => 'accessibleAudio', 'message' => 'The value you selected is not a valid choice.'],
                ],
            ],
            'accessibleLibras should be a string' => [
                'requestBody' => array_merge($requestBody, ['accessibleLibras' => 123]),
                'expectedErrors' => [
                    ['field' => 'accessibleLibras', 'message' => 'This value should be of type string.'],
                ],
            ],
            'accessibleLibras should be a valid choice' => [
                'requestBody' => array_merge($requestBody, ['accessibleLibras' => 'invalid-choice']),
                'expectedErrors' => [
                    ['field' => 'accessibleLibras', 'message' => 'The value you selected is not a valid choice.'],
                ],
            ],
            'free should be a boolean' => [
                'requestBody' => array_merge($requestBody, ['free' => 'true']),
                'expectedErrors' => [
                    ['field' => 'free', 'message' => 'This value should be of type boolean.'],
                ],
            ],
            'draft should be boolean' => [
                'requestBody' => array_merge($requestBody, ['draft' => 'invalid']),
                'expectedErrors' => [
                    ['field' => 'draft', 'message' => 'This value should be of type boolean.'],
                ],
            ],
        ];
    }

    public function testGetACollectionOfEvents(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_GET, self::BASE_URL);
        $response = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertCount(count(EventFixtures::EVENTS) - 1, json_decode($response));

        $this->assertJsonContains([
            'id' => EventFixtures::EVENT_ID_1,
            'name' => 'Festival Sertão Criativo',
            'image' => null,
            'agentGroup' => null,
            'space' => [
                'id' => SpaceFixtures::SPACE_ID_3,
            ],
            'initiative' => [
                'id' => InitiativeFixtures::INITIATIVE_ID_2,
            ],
            'parent' => null,
            'createdBy' => [
                'id' => AgentFixtures::AGENT_ID_1,
            ],
            'coverImage' => null,
            'subtitle' => 'Subtítulo de exemplo',
            'shortDescription' => null,
            'longDescription' => 'Uma descrição mais longa',
            'format' => EventFormatEnum::ONLINE->value,
            'eventType' => null,
            'startDate' => '2024-07-10T10:00:00+00:00',
            'endDate' => '2024-09-10T11:30:00+00:00',
            'activityAreas' => [
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_1,
                    'name' => 'Artes Visuais',
                ],
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_3,
                    'name' => 'Teatro',
                ],
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_5,
                    'name' => 'Cinema',
                ],
            ],
            'tags' => [],
            'site' => 'evento.com.br',
            'phoneNumber' => '8585998585',
            'maxCapacity' => 1000,
            'accessibleAudio' => AccessibilityInfoEnum::YES->value,
            'accessibleLibras' => AccessibilityInfoEnum::YES->value,
            'free' => false,
            'draft' => false,
            'culturalLanguages' => [
                [
                    'id' => CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_1,
                    'name' => 'Arte e Música',
                    'description' => 'Pintura, escultura, literatura, dança, teatro, música e outras formas artísticas que comunicam ideias, histórias e sentimentos.',
                ],
                [
                    'id' => CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_2,
                    'name' => 'Costumes e Rituais',
                    'description' => 'Celebrações, festivais, cerimônias religiosas e ritos de passagem que expressam identidades culturais.',
                ],
                [
                    'id' => CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_3,
                    'name' => 'Moda e Vestuário',
                    'description' => 'Estilos de vestir que comunicam status social, afiliação cultural, crenças ou até protestos.',
                ],
            ],
            'createdAt' => '2024-07-10T11:30:00+00:00',
            'updatedAt' => '2024-07-10T11:35:00+00:00',
            'deletedAt' => null,
        ]);
    }

    public function testGetAnEventItem(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, EventFixtures::EVENT_ID_6);

        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $event = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Event::class, EventFixtures::EVENT_ID_6);

        $this->assertResponseBodySame([
            'id' => EventFixtures::EVENT_ID_6,
            'name' => 'Cores do Sertão',
            'image' => null,
            'agentGroup' => null,
            'space' => [
                'id' => SpaceFixtures::SPACE_ID_3,
            ],
            'initiative' => [
                'id' => InitiativeFixtures::INITIATIVE_ID_10,
            ],
            'parent' => [
                'id' => EventFixtures::EVENT_ID_3,
                'name' => 'Músical o vento da Caatinga',
                'image' => $event->getParent()->getImage(),
                'agentGroup' => null,
                'space' => [
                    'id' => SpaceFixtures::SPACE_ID_5,
                ],
                'initiative' => [
                    'id' => InitiativeFixtures::INITIATIVE_ID_7,
                ],
                'parent' => null,
                'extraFields' => [
                    'ageRating' => '18 years',
                ],
                'createdBy' => [
                    'id' => AgentFixtures::AGENT_ID_2,
                ],
                'coverImage' => 'coverimage.jpg',
                'subtitle' => null,
                'shortDescription' => 'Descrição curta',
                'longDescription' => 'Uma descrição mais longa',
                'format' => EventFormatEnum::HYBRID->value,
                'eventType' => null,
                'startDate' => '2024-07-18T10:00:00+00:00',
                'endDate' => '2024-07-18T11:30:00+00:00',
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
                        'id' => TagFixtures::TAG_ID_10,
                        'name' => 'Feira',
                    ],
                ],
                'site' => 'evento.com.br',
                'phoneNumber' => '8585998585',
                'maxCapacity' => 300,
                'accessibleAudio' => AccessibilityInfoEnum::NOT_INFORMED->value,
                'accessibleLibras' => AccessibilityInfoEnum::NOT_INFORMED->value,
                'free' => true,
                'draft' => false,
                'culturalLanguages' => [
                    [
                        'id' => CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_6,
                        'name' => 'Sistemas de Simbolismo',
                        'description' => 'Símbolos, gestos, mitos e narrativas que comunicam significados compartilhados.',
                    ],
                ],
                'createdAt' => '2024-07-16T17:22:00+00:00',
                'updatedAt' => null,
                'deletedAt' => null,
            ],
            'extraFields' => $event->getExtraFields(),
            'createdBy' => [
                'id' => AgentFixtures::AGENT_ID_3,
            ],
            'coverImage' => 'coverimage.jpg',
            'subtitle' => 'Subtítulo de exemplo',
            'shortDescription' => 'Descrição curta',
            'longDescription' => null,
            'format' => EventFormatEnum::HYBRID->value,
            'eventType' => null,
            'startDate' => '2025-08-05T10:30:00+00:00',
            'endDate' => '2024-08-10T18:30:00+00:00',
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
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_9,
                    'name' => 'Artesanato',
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
            'site' => 'evento.com.br',
            'phoneNumber' => '8585998585',
            'maxCapacity' => 600,
            'accessibleAudio' => AccessibilityInfoEnum::NOT_INFORMED->value,
            'accessibleLibras' => AccessibilityInfoEnum::NOT_INFORMED->value,
            'free' => true,
            'draft' => false,
            'culturalLanguages' => [
                [
                    'id' => CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_1,
                    'name' => 'Arte e Música',
                    'description' => 'Pintura, escultura, literatura, dança, teatro, música e outras formas artísticas que comunicam ideias, histórias e sentimentos.',
                ],
                [
                    'id' => CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_8,
                    'name' => 'Oralidade e Tradição Popular',
                    'description' => 'Histórias, provérbios, músicas e conhecimentos transmitidos verbalmente entre gerações.',
                ],
            ],
            'createdAt' => $event->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => null,
            'deletedAt' => null,
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
                'description' => 'The requested Event was not found.',
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
                'description' => 'The requested Event was not found.',
            ],
        ]);
    }

    public function testDeleteAnEventItemWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, EventFixtures::EVENT_ID_4);

        $client->request(Request::METHOD_DELETE, $url);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $client->request(Request::METHOD_GET, $url);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testCanUpdateAnEvent(): void
    {
        $requestBody = EventTestFixtures::complete();
        unset($requestBody['id']);

        $url = sprintf('%s/%s', self::BASE_URL, EventFixtures::EVENT_ID_3);

        $client = self::apiClient();

        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $event = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Event::class, EventFixtures::EVENT_ID_3);

        $this->assertResponseBodySame([
            'id' => EventFixtures::EVENT_ID_3,
            'name' => 'Test Event',
            'image' => $event->getImage(),
            'agentGroup' => [
                'id' => AgentFixtures::AGENT_ID_1,
            ],
            'space' => ['id' => SpaceFixtures::SPACE_ID_1],
            'initiative' => ['id' => InitiativeFixtures::INITIATIVE_ID_1],
            'parent' => [
                'id' => EventFixtures::EVENT_ID_1,
                'name' => 'Festival Sertão Criativo',
                'image' => $event->getParent()->getImage(),
                'agentGroup' => null,
                'space' => ['id' => SpaceFixtures::SPACE_ID_3],
                'initiative' => ['id' => InitiativeFixtures::INITIATIVE_ID_2],
                'parent' => null,
                'extraFields' => $event->getExtraFields(),
                'createdBy' => ['id' => AgentFixtures::AGENT_ID_1],
                'coverImage' => null,
                'subtitle' => 'Subtítulo de exemplo',
                'shortDescription' => null,
                'longDescription' => 'Uma descrição mais longa',
                'format' => EventFormatEnum::ONLINE->value,
                'eventType' => null,
                'startDate' => '2024-07-10T10:00:00+00:00',
                'endDate' => '2024-09-10T11:30:00+00:00',
                'activityAreas' => [
                    [
                        'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_1,
                        'name' => 'Artes Visuais',
                    ],
                    [
                        'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_3,
                        'name' => 'Teatro',
                    ],
                    [
                        'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_5,
                        'name' => 'Cinema',
                    ],
                ],
                'tags' => [],
                'site' => 'evento.com.br',
                'phoneNumber' => '8585998585',
                'maxCapacity' => 1000,
                'accessibleAudio' => AccessibilityInfoEnum::YES->value,
                'accessibleLibras' => AccessibilityInfoEnum::YES->value,
                'free' => false,
                'draft' => false,
                'culturalLanguages' => [
                    [
                        'id' => CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_1,
                        'name' => 'Arte e Música',
                        'description' => 'Pintura, escultura, literatura, dança, teatro, música e outras formas artísticas que comunicam ideias, histórias e sentimentos.',
                    ],
                    [
                        'id' => CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_2,
                        'name' => 'Costumes e Rituais',
                        'description' => 'Celebrações, festivais, cerimônias religiosas e ritos de passagem que expressam identidades culturais.',
                    ],
                    [
                        'id' => CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_3,
                        'name' => 'Moda e Vestuário',
                        'description' => 'Estilos de vestir que comunicam status social, afiliação cultural, crenças ou até protestos.',
                    ],
                ],
                'createdAt' => '2024-07-10T11:30:00+00:00',
                'updatedAt' => '2024-07-10T11:35:00+00:00',
                'deletedAt' => null,
            ],
            'extraFields' => $requestBody['extraFields'],
            'createdBy' => ['id' => self::getLoggedAgentId()],
            'coverImage' => 'coverimage.jpg',
            'subtitle' => 'Subtítulo de exemplo',
            'shortDescription' => 'Descrição curta',
            'longDescription' => 'Uma descrição mais longa',
            'format' => EventFormatEnum::HYBRID->value,
            'eventType' => [
                'id' => EventTypeFixtures::EVENT_TYPE_ID_1,
                'name' => 'Show Musical',
            ],
            'startDate' => '2025-01-16T00:00:00+00:00',
            'endDate' => '2025-04-01T00:00:00+00:00',
            'activityAreas' => [
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_1,
                    'name' => 'Artes Visuais',
                ],
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_9,
                    'name' => 'Artesanato',
                ],
                [
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_2,
                    'name' => 'Música',
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
            'site' => 'evento.com.br',
            'phoneNumber' => '8585998585',
            'maxCapacity' => 5000,
            'accessibleAudio' => AccessibilityInfoEnum::NOT_INFORMED->value,
            'accessibleLibras' => AccessibilityInfoEnum::NOT_INFORMED->value,
            'free' => true,
            'draft' => false,
            'culturalLanguages' => [
                [
                    'id' => CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_6,
                    'name' => 'Sistemas de Simbolismo',
                    'description' => 'Símbolos, gestos, mitos e narrativas que comunicam significados compartilhados.',
                ],
            ],
            'createdAt' => $event->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => $event->getUpdatedAt()->format(DateTimeInterface::ATOM),
            'deletedAt' => null,
        ]);
    }

    #[DataProvider('provideValidationUpdateCases')]
    public function testValidationUpdate(array $requestBody, array $expectedErrors): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, EventFixtures::EVENT_ID_4);
        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationUpdateCases(): array
    {
        $requestBody = EventTestFixtures::partial();

        return [
            'name should be a string' => [
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
            'agentGroup is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['agentGroup' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'agentGroup', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'agentGroup should exist' => [
                'requestBody' => array_merge($requestBody, ['agentGroup' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'agentGroup', 'message' => 'This id does not exist.'],
                ],
            ],
            'space is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['space' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'space', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'space should exist' => [
                'requestBody' => array_merge($requestBody, ['space' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'space', 'message' => 'This id does not exist.'],
                ],
            ],
            'initiative is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['initiative' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'initiative', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'initiative should exist' => [
                'requestBody' => array_merge($requestBody, ['initiative' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'initiative', 'message' => 'This id does not exist.'],
                ],
            ],
            'parent is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['parent' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'parent', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'parent should exist' => [
                'requestBody' => array_merge($requestBody, ['parent' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'parent', 'message' => 'This id does not exist.'],
                ],
            ],
            'createdBy is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['createdBy' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'createdBy', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'createdBy should exist' => [
                'requestBody' => array_merge($requestBody, ['createdBy' => Uuid::v4()->toRfc4122()]),
                'expectedErrors' => [
                    ['field' => 'createdBy', 'message' => 'This id does not exist.'],
                ],
            ],
            'extra fields should be a valid json object' => [
                'requestBody' => array_merge($requestBody, ['extraFields' => 'invalid']),
                'expectedErrors' => [
                    ['field' => 'extraFields', 'message' => 'This value should be of type json object.'],
                ],
            ],
            'coverImage should be a string' => [
                'requestBody' => array_merge($requestBody, ['coverImage' => 123]),
                'expectedErrors' => [
                    ['field' => 'coverImage', 'message' => 'This value should be of type string.'],
                ],
            ],
            'coverImage is too short' => [
                'requestBody' => array_merge($requestBody, ['coverImage' => 'a']),
                'expectedErrors' => [
                    ['field' => 'coverImage', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'coverImage is too long' => [
                'requestBody' => array_merge($requestBody, ['coverImage' => str_repeat('a', 256)]),
                'expectedErrors' => [
                    ['field' => 'coverImage', 'message' => 'This value is too long. It should have 255 characters or less.'],
                ],
            ],
            'subtitle should be a string' => [
                'requestBody' => array_merge($requestBody, ['subtitle' => 123]),
                'expectedErrors' => [
                    ['field' => 'subtitle', 'message' => 'This value should be of type string.'],
                ],
            ],
            'subtitle is too short' => [
                'requestBody' => array_merge($requestBody, ['subtitle' => 'a']),
                'expectedErrors' => [
                    ['field' => 'subtitle', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'subtitle is too long' => [
                'requestBody' => array_merge($requestBody, ['subtitle' => str_repeat('a', 256)]),
                'expectedErrors' => [
                    ['field' => 'subtitle', 'message' => 'This value is too long. It should have 255 characters or less.'],
                ],
            ],
            'shortDescription should be a string' => [
                'requestBody' => array_merge($requestBody, ['shortDescription' => 123]),
                'expectedErrors' => [
                    ['field' => 'shortDescription', 'message' => 'This value should be of type string.'],
                ],
            ],
            'shortDescription is too short' => [
                'requestBody' => array_merge($requestBody, ['shortDescription' => 'a']),
                'expectedErrors' => [
                    ['field' => 'shortDescription', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'shortDescription is too long' => [
                'requestBody' => array_merge($requestBody, ['shortDescription' => str_repeat('a', 256)]),
                'expectedErrors' => [
                    ['field' => 'shortDescription', 'message' => 'This value is too long. It should have 255 characters or less.'],
                ],
            ],
            'longDescription should be a string' => [
                'requestBody' => array_merge($requestBody, ['longDescription' => 123]),
                'expectedErrors' => [
                    ['field' => 'longDescription', 'message' => 'This value should be of type string.'],
                ],
            ],
            'type should be a integer' => [
                'requestBody' => array_merge($requestBody, ['type' => 'abc']),
                'expectedErrors' => [
                    ['field' => 'type', 'message' => 'This value should be of type integer.'],
                ],
            ],
            'type should be a valid choice' => [
                'requestBody' => array_merge($requestBody, ['type' => 5]),
                'expectedErrors' => [
                    ['field' => 'type', 'message' => 'The value you selected is not a valid choice.'],
                ],
            ],
            'endDate should be a string' => [
                'requestBody' => array_merge($requestBody, ['endDate' => 123]),
                'expectedErrors' => [
                    ['field' => 'endDate', 'message' => 'This value should be of type string.'],
                ],
            ],
            'endDate should be a valid choice' => [
                'requestBody' => array_merge($requestBody, ['endDate' => 'invalid-date']),
                'expectedErrors' => [
                    ['field' => 'endDate', 'message' => 'This value is not a valid datetime.'],
                ],
            ],
            'activityAreas should be an array' => [
                'requestBody' => array_merge($requestBody, ['activityAreas' => 123]),
                'expectedErrors' => [
                    ['field' => 'activityAreas', 'message' => 'This value should be of type iterable.'],
                ],
            ],
            'activityAreas item should be a uuid' => [
                'requestBody' => array_merge($requestBody, ['activityAreas' => ['invalid-uuid']]),
                'expectedErrors' => [
                    ['field' => 'activityAreas[0]', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'activityAreas should exists' => [
                'requestBody' => array_merge($requestBody, ['activityAreas' => [Uuid::v4()->toRfc4122()]]),
                'expectedErrors' => [
                    ['field' => 'activityAreas[0]', 'message' => 'This id does not exist.'],
                ],
            ],
            'tags should be an array' => [
                'requestBody' => array_merge($requestBody, ['tags' => 123]),
                'expectedErrors' => [
                    ['field' => 'tags', 'message' => 'This value should be of type iterable.'],
                ],
            ],
            'tags item should be a uuid' => [
                'requestBody' => array_merge($requestBody, ['tags' => ['invalid-uuid']]),
                'expectedErrors' => [
                    ['field' => 'tags[0]', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'tags should exists' => [
                'requestBody' => array_merge($requestBody, ['tags' => [Uuid::v4()->toRfc4122()]]),
                'expectedErrors' => [
                    ['field' => 'tags[0]', 'message' => 'This id does not exist.'],
                ],
            ],
            'site should be a string' => [
                'requestBody' => array_merge($requestBody, ['site' => 123]),
                'expectedErrors' => [
                    ['field' => 'site', 'message' => 'This value should be of type string.'],
                ],
            ],
            'site is too short' => [
                'requestBody' => array_merge($requestBody, ['site' => 'a']),
                'expectedErrors' => [
                    ['field' => 'site', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'site is too long' => [
                'requestBody' => array_merge($requestBody, ['site' => str_repeat('a', 256)]),
                'expectedErrors' => [
                    ['field' => 'site', 'message' => 'This value is too long. It should have 255 characters or less.'],
                ],
            ],
            'phoneNumber should be a string' => [
                'requestBody' => array_merge($requestBody, ['phoneNumber' => 123]),
                'expectedErrors' => [
                    ['field' => 'phoneNumber', 'message' => 'This value should be of type string.'],
                ],
            ],
            'phoneNumber is too short' => [
                'requestBody' => array_merge($requestBody, ['phoneNumber' => 'a']),
                'expectedErrors' => [
                    ['field' => 'phoneNumber', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'phoneNumber is too long' => [
                'requestBody' => array_merge($requestBody, ['phoneNumber' => str_repeat('a', 21)]),
                'expectedErrors' => [
                    ['field' => 'phoneNumber', 'message' => 'This value is too long. It should have 20 characters or less.'],
                ],
            ],
            'maxCapacity should be an integer' => [
                'requestBody' => array_merge($requestBody, ['maxCapacity' => '123']),
                'expectedErrors' => [
                    ['field' => 'maxCapacity', 'message' => 'This value should be of type integer.'],
                ],
            ],
            'accessibleAudio should be a string' => [
                'requestBody' => array_merge($requestBody, ['accessibleAudio' => 123]),
                'expectedErrors' => [
                    ['field' => 'accessibleAudio', 'message' => 'This value should be of type string.'],
                ],
            ],
            'accessibleAudio should be a valid choice' => [
                'requestBody' => array_merge($requestBody, ['accessibleAudio' => 'invalid-choice']),
                'expectedErrors' => [
                    ['field' => 'accessibleAudio', 'message' => 'The value you selected is not a valid choice.'],
                ],
            ],
            'accessibleLibras should be a string' => [
                'requestBody' => array_merge($requestBody, ['accessibleLibras' => 123]),
                'expectedErrors' => [
                    ['field' => 'accessibleLibras', 'message' => 'This value should be of type string.'],
                ],
            ],
            'accessibleLibras should be a valid choice' => [
                'requestBody' => array_merge($requestBody, ['accessibleLibras' => 'invalid-choice']),
                'expectedErrors' => [
                    ['field' => 'accessibleLibras', 'message' => 'The value you selected is not a valid choice.'],
                ],
            ],
            'free should be a boolean' => [
                'requestBody' => array_merge($requestBody, ['free' => 'true']),
                'expectedErrors' => [
                    ['field' => 'free', 'message' => 'This value should be of type boolean.'],
                ],
            ],
            'draft should be boolean' => [
                'requestBody' => array_merge($requestBody, ['draft' => 'invalid']),
                'expectedErrors' => [
                    ['field' => 'draft', 'message' => 'This value should be of type boolean.'],
                ],
            ],
        ];
    }

    public function testCanUpdateImageWithMultipartFormData(): void
    {
        $file = ImageTestFixtures::getImageValid();

        $url = sprintf('%s/%s/images', self::BASE_URL, EventFixtures::EVENT_ID_8);

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

        $event = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Event::class, EventFixtures::EVENT_ID_8);

        $this->assertResponseBodySame([
            'id' => EventFixtures::EVENT_ID_8,
            'name' => 'Festival da Rapadura',
            'image' => $event->getImage(),
            'agentGroup' => null,
            'space' => [
                'id' => SpaceFixtures::SPACE_ID_8,
            ],
            'initiative' => [
                'id' => InitiativeFixtures::INITIATIVE_ID_2,
            ],
            'parent' => null,
            'extraFields' => $event->getExtraFields(),
            'createdBy' => [
                'id' => AgentFixtures::AGENT_ID_4,
            ],
            'coverImage' => 'coverimage.jpg',
            'subtitle' => 'Subtítulo de exemplo',
            'shortDescription' => 'Descrição curta',
            'longDescription' => null,
            'format' => EventFormatEnum::IN_PERSON->value,
            'eventType' => null,
            'startDate' => '2024-08-13T10:00:00+00:00',
            'endDate' => '2024-08-13T11:30:00+00:00',
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
                    'id' => ActivityAreaFixtures::ACTIVITY_AREA_ID_3,
                    'name' => 'Teatro',
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
            'site' => 'evento.com.br',
            'phoneNumber' => '8585998585',
            'maxCapacity' => 800,
            'accessibleAudio' => AccessibilityInfoEnum::NO->value,
            'accessibleLibras' => AccessibilityInfoEnum::NO->value,
            'free' => false,
            'draft' => false,
            'culturalLanguages' => [
                [
                    'id' => CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_2,
                    'name' => 'Costumes e Rituais',
                    'description' => 'Celebrações, festivais, cerimônias religiosas e ritos de passagem que expressam identidades culturais.',
                ],
                [
                    'id' => CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_4,
                    'name' => 'Gastronomia',
                    'description' => 'Pratos e modos de preparo que refletem tradições, identidades e até histórias de resistência ou adaptação cultural.',
                ],
                [
                    'id' => CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_6,
                    'name' => 'Sistemas de Simbolismo',
                    'description' => 'Símbolos, gestos, mitos e narrativas que comunicam significados compartilhados.',
                ],
            ],
            'createdAt' => $event->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => $event->getUpdatedAt()->format(DateTimeInterface::ATOM),
            'deletedAt' => null,
        ]);
    }

    #[DataProvider('provideValidationUpdateImageCases')]
    public function testValidationUpdateImage(array $requestBody, $file, array $expectedErrors): void
    {
        $url = sprintf('%s/%s/images', self::BASE_URL, EventFixtures::EVENT_ID_7);

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
        $requestBody = EventTestFixtures::partial();
        unset($requestBody['id']);

        return [
            'image not supported' => [
                'requestBody' => $requestBody,
                'file' => ImageTestFixtures::getGif(),
                'expectedErrors' => [
                    ['field' => 'image', 'message' => 'The mime type of the file is invalid ("image/gif"). Allowed mime types are "image/png", "image/jpg", "image/jpeg".'],
                ],
            ],
            'image size' => [
                'requestBody' => $requestBody,
                'file' => ImageTestFixtures::getImageMoreThan2mb(),
                'expectedErrors' => [
                    ['field' => 'image', 'message' => 'The file is too large (2.5 MB). Allowed maximum size is 2 MB.'],
                ],
            ],
        ];
    }
}
