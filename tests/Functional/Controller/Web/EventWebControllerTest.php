<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web;

use App\Controller\Web\EventWebController;
use App\DataFixtures\Entity\EventFixtures;
use App\Tests\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class EventWebControllerTest extends AbstractWebTestCase
{
    public function testListRouteRendersHTMLSuccessfully(): void
    {
        $this->client->request('GET', '/eventos');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.event-card');
    }

    public function testGetOneRouteNotFound(): void
    {
        $this->client->request('GET', '/eventos/'.Uuid::v4()->toRfc4122());
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testGetOneRouteForExistingEvent(): void
    {
        $existingUuid = EventFixtures::EVENT_ID_1;
        $this->client->request('GET', '/eventos/'.$existingUuid);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h2.name__entity-details');
    }

    public function testControllerShowMethodDirectly(): void
    {
        $controller = self::getContainer()->get(EventWebController::class);
        $controller->setContainer(self::getContainer());
        $response = $controller->show(Uuid::fromString(EventFixtures::EVENT_ID_1));
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('name__entity-details', $response->getContent());
    }

    public function testControllerListMethodDirectly(): void
    {
        $controller = self::getContainer()->get(EventWebController::class);
        $controller->setContainer(self::getContainer());
        $request = new Request();
        $request->attributes->set('_route', 'web_event_list');
        $requestStack = self::getContainer()->get('request_stack');
        $requestStack->push($request);
        $response = $controller->list($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('event-card', $response->getContent());
        $requestStack->pop();
    }
}
