<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web;

use App\Controller\Web\OrganizationWebController;
use App\DataFixtures\Entity\OrganizationFixtures;
use App\Tests\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class OrganizationWebControllerTest extends AbstractWebTestCase
{
    public function testListRouteRendersHTMLSuccessfully(): void
    {
        $this->client->request('GET', '/organizacoes');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.dashboard-card');
    }

    public function testGetOneRouteNotFound(): void
    {
        $this->client->request('GET', '/organizacoes/'.Uuid::v4()->toRfc4122());
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testGetOneRouteForExistingOrganization(): void
    {
        $existingUuid = OrganizationFixtures::ORGANIZATION_ID_1;
        $this->client->request('GET', '/organizacoes/'.$existingUuid);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Fortaleza');
    }

    public function testControllerGetOneMethodDirectly(): void
    {
        $controller = self::getContainer()->get(OrganizationWebController::class);
        $controller->setContainer(self::getContainer());

        $response = $controller->getOne(Uuid::fromString(OrganizationFixtures::ORGANIZATION_ID_1));
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('Fortaleza', $response->getContent());
    }

    public function testControllerListMethodDirectly(): void
    {
        $controller = self::getContainer()->get(OrganizationWebController::class);
        $controller->setContainer(self::getContainer());

        $request = new Request();
        $request->attributes->set('_route', 'web_organization_list');
        $requestStack = self::getContainer()->get('request_stack');
        $requestStack->push($request);

        $response = $controller->list($request);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('Municípios Encontrados', $response->getContent());

        $requestStack->pop();
    }
}
