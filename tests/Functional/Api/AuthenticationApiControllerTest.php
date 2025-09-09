<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\DataFixtures\Entity\UserFixtures;
use App\Tests\AbstractApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationApiControllerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/login';
    private const string AGENT_URL = '/api/agents';
    private const string LOGOUT_URL = '/api/logout';

    public function testCanLogin(): void
    {
        $requestBody = [
            'username' => 'henriquelopeslima@example.com',
            'password' => UserFixtures::DEFAULT_PASSWORD,
        ];

        $client = self::createClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, server: [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ], content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseArray = self::getCurrentResponseArray();

        self::assertArrayHasKey('token', $responseArray);
        self::assertSame($requestBody['username'], $responseArray['user']);
    }

    public function testCannotLoginIfWaitingActivation(): void
    {
        $requestBody = [
            'username' => 'abnercarvalho@example.com',
            'password' => UserFixtures::DEFAULT_PASSWORD,
        ];

        $client = self::createClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, server: [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ], content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $responseArray = self::getCurrentResponseArray();

        self::assertSame($responseArray['error_message'], 'error_general');
        self::assertSame($responseArray['error_details']['description'], 'Your account is not activated, please contact the administrator or verify your email.');
    }

    public function testCannotLogin(): void
    {
        $requestBody = [
            'username' => 'henriquelopeslima@example.com',
            'password' => 'invalid',
        ];

        $client = self::createClient();

        $client->request(Request::METHOD_POST, self::BASE_URL, server: [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ], content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        self::assertResponseBodySame([
            'message' => 'unauthorized',
            'details' => [
                'The credentials you provided are invalid. Please try again.',
            ],
        ]);
    }

    private function loginAndGetToken($client, string $username, string $password): string
    {
        $requestBody = [
            'username' => $username,
            'password' => $password,
        ];

        $client->request(Request::METHOD_POST, self::BASE_URL, server: [
            'HTTP_ACCEPT' => 'application/json',
            'CONTENT_TYPE' => 'application/json',
        ], content: json_encode($requestBody));

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $responseArray = self::getCurrentResponseArray();
        self::assertArrayHasKey('token', $responseArray);

        return $responseArray['token'];
    }

    public function testCanLogout(): void
    {
        $client = self::createClient();

        $token = $this->loginAndGetToken($client, 'talysonsoares@example.com', UserFixtures::DEFAULT_PASSWORD);

        $client->request(Request::METHOD_GET, self::AGENT_URL, server: [
            'HTTP_AUTHORIZATION' => 'Bearer '.$token,
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $this->assertResponseIsSuccessful();

        $client->request(Request::METHOD_POST, self::LOGOUT_URL, server: [
            'HTTP_AUTHORIZATION' => 'Bearer '.$token,
            'HTTP_ACCEPT' => 'application/json',
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $client->request(Request::METHOD_GET, self::AGENT_URL, server: [
            'HTTP_AUTHORIZATION' => 'Bearer '.$token,
            'HTTP_ACCEPT' => 'application/json',
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        self::assertResponseBodySame([
            'code' => 401,
            'message' => 'Invalid JWT Token',
        ]);
    }
}
