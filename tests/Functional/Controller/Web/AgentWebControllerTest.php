<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web;

use App\Controller\Web\AgentWebController;
use App\DataFixtures\Entity\AgentFixtures;
use App\Tests\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class AgentWebControllerTest extends AbstractWebTestCase
{
    public function testListRouteRendersHTMLSuccessfully(): void
    {
        $this->client->request('GET', '/agentes');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.agent-card');
    }

    public function testGetOneRouteNotFound(): void
    {
        $this->client->request('GET', '/agentes/'.Uuid::v4()->toRfc4122());
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testGetOneRouteForExistingAgent(): void
    {
        $existingUuid = AgentFixtures::AGENT_ID_1;
        $this->client->request('GET', '/agentes/'.$existingUuid);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Alessandro');
        $this->assertStringContainsString($existingUuid, $this->client->getResponse()->getContent());
    }

    public function testControllerGetOneMethodDirectly(): void
    {
        $controller = self::getContainer()->get(AgentWebController::class);
        $controller->setContainer(self::getContainer());
        $response = $controller->getOne(Uuid::fromString(AgentFixtures::AGENT_ID_1));
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('Alessandro', $response->getContent());
    }

    public function testControllerListMethodDirectly(): void
    {
        $controller = self::getContainer()->get(AgentWebController::class);
        $controller->setContainer(self::getContainer());
        $request = new Request();
        $request->attributes->set('_route', 'web_agent_list');
        $requestStack = self::getContainer()->get('request_stack');
        $requestStack->push($request);
        $response = $controller->list($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('agent-card', $response->getContent());
        $requestStack->pop();
    }
}
