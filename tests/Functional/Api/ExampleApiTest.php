<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Tests\AbstractApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExampleApiTest extends AbstractApiTestCase
{
    public function testRouteNotExists(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $client = static::apiClient();
        $client->catchExceptions(false);

        $client->request(Request::METHOD_GET, '/api/not-existent', server: [
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_AUTHORIZATION' => self::getToken(),
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testRouteExistWithResponseIsSuccessful(): void
    {
        $client = static::apiClient();

        $client->request(Request::METHOD_GET, '/api/example', server: [
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_AUTHORIZATION' => self::getToken(),
        ]);
        $response = $client->getResponse()->getContent();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $this->assertEquals(json_encode([
            'message' => 'hello world',
        ]), $response);
    }
}
