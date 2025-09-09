<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api;

use App\Tests\AbstractApiTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StateApiControllerTest extends AbstractApiTestCase
{
    private const string BASE_URL = '/api/states';

    public function testGetListStates(): void
    {
        $client = static::apiClient();
        $client->request(Request::METHOD_GET, self::BASE_URL);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $response = json_decode($client->getResponse()->getContent(), true);

        foreach ($response as $state) {
            $this->assertArrayHasKey('id', $state, 'Each state must have an "id" key.');
            $this->assertArrayHasKey('name', $state, 'Each state must have a "name" key.');
            $this->assertArrayHasKey('acronym', $state, 'Each state must have an "acronym" key.');
            $this->assertArrayHasKey('capital', $state, 'Each state must have a "capital" key.');

            $this->assertIsArray($state['capital'], 'The "capital" key must be an array.');
            $this->assertArrayHasKey('id', $state['capital'], 'Each capital must have an "id" key.');
            $this->assertArrayHasKey('name', $state['capital'], 'Each capital must have a "name" key.');
        }

        $this->assertIsArray($response);
        $this->assertNotEmpty($response);
    }

    public function testGetListCitiesByState(): void
    {
        $stateId = '5ae1c5ab-bdc9-4659-9873-cb737df23a0a';
        $url = sprintf('/api/states/%s/cities', $stateId);

        $client = static::apiClient();
        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
        $this->assertNotEmpty($response);

        foreach ($response as $city) {
            $this->assertArrayHasKey('id', $city, 'Each city must have an "id" key.');
            $this->assertArrayHasKey('name', $city, 'Each city must have a "name" key.');
            $this->assertArrayHasKey('state', $city, 'Each city must have a "state" key.');
            $this->assertArrayHasKey('cityCode', $city, 'Each city must have a "cityCode" key.');
        }

        $firstCity = $response[0];
        $this->assertEquals('Fortaleza', $firstCity['name'], 'The first city must be Fortaleza.');
        $this->assertEquals(2304400, $firstCity['cityCode'], 'The first city must have the city code 2304400.');
    }

    public function testStateNotFound(): void
    {
        $invalidStateId = '00000000-0000-0000-0000-000000000000';
        $url = sprintf('/api/states/%s/cities', $invalidStateId);

        $client = static::apiClient();
        $client->request(Request::METHOD_GET, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);

        $response = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($response);
        $this->assertEquals('not_found', $response['error_message'], 'The error_message should be "not_found".');
        $this->assertEquals('The requested State was not found.', $response['error_details']['description'], 'The description is incorrect.');
    }
}
