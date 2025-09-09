<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\DataFixtures\Entity\FaqFixtures;
use App\Entity\Faq;
use App\Tests\AbstractApiTestCase;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class FaqApiControllerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/faqs';

    public function testCanCreateAFaqRequestBody(): void
    {
        $client = static::apiClient();

        $requestBody = [
            'id' => Uuid::v4()->toRfc4122(),
            'question' => 'Question test?',
            'answer' => 'Answer test.',
        ];

        $client->request(Request::METHOD_POST, self::BASE_URL, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $faq = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Faq::class, $requestBody['id']);

        $this->assertResponseBodySame([
            'id' => $requestBody['id'],
            'question' => 'Question test?',
            'answer' => 'Answer test.',
            'active' => true,
            'createdAt' => $faq->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => null,
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
        $requestBody = [
            'id' => Uuid::v4()->toRfc4122(),
            'question' => 'Question test?',
            'answer' => 'Answer test.',
        ];

        return [
            'missing required fields' => [
                'requestBody' => [],
                'expectedErrors' => [
                    ['field' => 'id', 'message' => 'This value should not be blank.'],
                    ['field' => 'question', 'message' => 'This value should not be blank.'],
                    ['field' => 'answer', 'message' => 'This value should not be blank.'],
                ],
            ],
            'id is not a valid UUID' => [
                'requestBody' => array_merge($requestBody, ['id' => 'invalid-uuid']),
                'expectedErrors' => [
                    ['field' => 'id', 'message' => 'This value is not a valid UUID.'],
                ],
            ],
            'question should be a string' => [
                'requestBody' => array_merge($requestBody, ['question' => 123]),
                'expectedErrors' => [
                    ['field' => 'question', 'message' => 'This value should be of type string.'],
                ],
            ],
            'answer should be a string' => [
                'requestBody' => array_merge($requestBody, ['answer' => 123]),
                'expectedErrors' => [
                    ['field' => 'answer', 'message' => 'This value should be of type string.'],
                ],
            ],
            'answer is too short' => [
                'requestBody' => array_merge($requestBody, ['answer' => 'a']),
                'expectedErrors' => [
                    ['field' => 'answer', 'message' => 'This value is too short. It should have 2 characters or more.'],
                ],
            ],
            'answer is too long' => [
                'requestBody' => array_merge($requestBody, ['answer' => str_repeat('a', 258)]),
                'expectedErrors' => [
                    ['field' => 'answer', 'message' => 'This value is too long. It should have 255 characters or less.'],
                ],
            ],
        ];
    }

    public function testCanUpdate(): void
    {
        $requestBody = [
            'question' => 'Question test 2?',
            'answer' => 'Answer test 2.',
        ];

        $url = sprintf('%s/%s', self::BASE_URL, FaqFixtures::FAQ_ID_1);

        $client = self::apiClient();

        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $faq = $client->getContainer()->get(EntityManagerInterface::class)
            ->find(Faq::class, FaqFixtures::FAQ_ID_1);

        $this->assertResponseBodySame([
            'id' => FaqFixtures::FAQ_ID_1,
            'question' => 'Question test 2?',
            'answer' => 'Answer test 2.',
            'active' => true,
            'createdAt' => $faq->getCreatedAt()->format(DateTimeInterface::ATOM),
            'updatedAt' => $faq->getUpdatedAt()->format(DateTimeInterface::ATOM),
        ]);
    }

    #[DataProvider('provideValidationUpdateCases')]
    public function testValidationUpdate(array $requestBody, array $expectedErrors): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, FaqFixtures::FAQ_ID_3);
        $client->request(Request::METHOD_PATCH, $url, content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertResponseBodySame([
            'error_message' => 'not_valid',
            'error_details' => $expectedErrors,
        ]);
    }

    public static function provideValidationUpdateCases(): array
    {
        $requestBody = [
            'question' => 'Question test 3?',
            'answer' => 'Answer test 3.',
        ];

        return [
            'active should be a boolean' => [
                'requestBody' => array_merge($requestBody, ['active' => 123]),
                'expectedErrors' => [
                    ['field' => 'active', 'message' => 'This value should be of type boolean.'],
                ],
            ],
            'question should be a string' => [
                'requestBody' => array_merge($requestBody, ['question' => 123]),
                'expectedErrors' => [
                    ['field' => 'question', 'message' => 'This value should be of type string.'],
                ],
            ],
            'answer should be a string' => [
                'requestBody' => array_merge($requestBody, ['answer' => 123]),
                'expectedErrors' => [
                    ['field' => 'answer', 'message' => 'This value should be of type string.'],
                ],
            ],
        ];
    }

    public function testGetForRetrieveFaqList(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_GET, self::BASE_URL);
        $response = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertCount(8, json_decode($response));
        $this->assertResponseBodySame([
            [
                'id' => FaqFixtures::FAQ_ID_8,
                'question' => 'Como me inscrever para cursos gratuitos do SENAI?',
                'answer' => 'Acesse o site oficial do SENAI de sua região, procure pelos cursos disponíveis e siga as orientações para inscrição online.',
                'active' => true,
                'createdAt' => '2024-08-12T14:24:00+00:00',
                'updatedAt' => null,
            ],
            [
                'id' => FaqFixtures::FAQ_ID_7,
                'question' => 'Há editais para iniciativas ambientais?',
                'answer' => 'Sim, o Fundo Amazônia e o programa Petrobras Socioambiental oferecem editais para apoiar projetos focados em sustentabilidade e conservação ambiental.',
                'active' => true,
                'createdAt' => '2024-08-11T15:54:00+00:00',
                'updatedAt' => null,
            ],
            [
                'id' => FaqFixtures::FAQ_ID_6,
                'question' => 'Quais iniciativas sociais apoiam mulheres empreendedoras no Brasil?',
                'answer' => 'Programas como o “Ela Pode” (Instituto Rede Mulher Empreendedora) oferecem capacitação gratuita, e o “WE Ventures” apoia startups lideradas por mulheres.',
                'active' => true,
                'createdAt' => '2024-08-10T11:26:00+00:00',
                'updatedAt' => null,
            ],
            [
                'id' => FaqFixtures::FAQ_ID_5,
                'question' => 'Como cadastrar meu projeto em plataformas de crowdfunding?',
                'answer' => 'Plataformas como Catarse e Benfeitoria exigem que você crie um perfil, elabore uma apresentação atraente para o projeto e estabeleça metas de arrecadação claras.',
                'active' => true,
                'createdAt' => '2024-07-22T16:20:00+00:00',
                'updatedAt' => null,
            ],
            [
                'id' => FaqFixtures::FAQ_ID_4,
                'question' => 'Quais oportunidades existem para pesquisadores acadêmicos no Brasil?',
                'answer' => 'O CNPq e a Capes oferecem bolsas para pesquisa acadêmica. O edital Universal do CNPq está aberto para submissões em diversas áreas do conhecimento.',
                'active' => true,
                'createdAt' => '2024-07-17T15:12:00+00:00',
                'updatedAt' => null,
            ],
            [
                'id' => FaqFixtures::FAQ_ID_3,
                'question' => 'Como faço para me inscrever em um programa de bolsa de estudos internacional?',
                'answer' => 'Você pode verificar programas como Fulbright, Chevening e Erasmus Mundus. Consulte os requisitos diretamente nos sites oficiais e prepare sua documentação com antecedência.',
                'active' => true,
                'createdAt' => '2024-07-16T17:22:00+00:00',
                'updatedAt' => null,
            ],
            [
                'id' => FaqFixtures::FAQ_ID_2,
                'question' => 'Quais iniciativas apoiam startups no Brasil atualmente?',
                'answer' => 'Iniciativas como o programa Startup Brasil, Sebrae Startup SP e o BNDES Garagem oferecem suporte financeiro, capacitação e mentoria para startups.',
                'active' => true,
                'createdAt' => '2024-07-11T10:49:00+00:00',
                'updatedAt' => null,
            ],
            [
                'id' => FaqFixtures::FAQ_ID_1,
                'question' => 'Onde posso acessar editais para projetos culturais no Brasil?',
                'answer' => 'Você pode encontrar editais no site do Ministério da Cultura (Minc), na Funarte e nas Secretarias Estaduais de Cultura. Por exemplo, o Edital de Fomento à Cultura Negra 2024 está disponível na Funarte.',
                'active' => true,
                'createdAt' => '2024-07-10T11:30:00+00:00',
                'updatedAt' => null,
            ],
        ]);
    }

    public function testGetAnFaqItemWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, FaqFixtures::FAQ_ID_1);

        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseBodySame([
            'id' => FaqFixtures::FAQ_ID_1,
            'question' => 'Onde posso acessar editais para projetos culturais no Brasil?',
            'answer' => 'Você pode encontrar editais no site do Ministério da Cultura (Minc), na Funarte e nas Secretarias Estaduais de Cultura. Por exemplo, o Edital de Fomento à Cultura Negra 2024 está disponível na Funarte.',
            'active' => true,
            'createdAt' => '2024-07-10T11:30:00+00:00',
            'updatedAt' => null,
        ]);
    }

    public function testGetAResourceWhenNotFound(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, Uuid::v4()->toRfc4122());

        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested Faq was not found.',
            ],
        ]);
    }

    public function testCannotGetAResourceDeactivated(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, FaqFixtures::FAQ_ID_9);

        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested Faq was not found.',
            ],
        ]);
    }

    public function testDeleteAResourceWithSuccess(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, FaqFixtures::FAQ_ID_1);

        $client->request(Request::METHOD_DELETE, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }

    public function testDeleteAResourceWhenNotFound(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, Uuid::v4()->toRfc4122());

        $client->request(Request::METHOD_DELETE, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested Faq was not found.',
            ],
        ]);
    }

    public function testCannotDeleteAResourceAlreadyDeleted(): void
    {
        $client = static::apiClient();

        $url = sprintf('%s/%s', self::BASE_URL, FaqFixtures::FAQ_ID_9);

        $client->request(Request::METHOD_DELETE, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this->assertResponseBodySame([
            'error_message' => 'not_found',
            'error_details' => [
                'description' => 'The requested Faq was not found.',
            ],
        ]);
    }
}
