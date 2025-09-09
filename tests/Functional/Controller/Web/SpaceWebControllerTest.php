<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web;

use App\Controller\Web\SpaceWebController;
use App\DataFixtures\Entity\SpaceFixtures;
use App\Tests\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class SpaceWebControllerTest extends AbstractWebTestCase
{
    public function testListRouteRendersHTMLSuccessfully(): void
    {
        $this->client->request('GET', '/espacos');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.space-card');
    }

    public function testGetOneRouteNotFound(): void
    {
        $this->client->request('GET', '/espacos/'.Uuid::v4()->toRfc4122());
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testGetOneRouteForExistingSpace(): void
    {
        $existingUuid = SpaceFixtures::SPACE_ID_1;
        $this->client->request('GET', '/espacos/'.$existingUuid);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'SECULT');
    }

    public function testControllerGetOneMethodDirectly(): void
    {
        $controller = self::getContainer()->get(SpaceWebController::class);
        $controller->setContainer(self::getContainer());
        $response = $controller->getOne(Uuid::fromString(SpaceFixtures::SPACE_ID_1));
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('SECULT', $response->getContent());
    }

    public function testControllerListMethodDirectly(): void
    {
        $controller = self::getContainer()->get(SpaceWebController::class);
        $controller->setContainer(self::getContainer());
        $request = new Request();
        $request->attributes->set('_route', 'web_space_list');
        $requestStack = self::getContainer()->get('request_stack');
        $requestStack->push($request);
        $response = $controller->list($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('space-card', $response->getContent());
        $requestStack->pop();
    }
}
