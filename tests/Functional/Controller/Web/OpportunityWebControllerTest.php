<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web;

use App\Controller\Web\OpportunityWebController;
use App\DataFixtures\Entity\OpportunityFixtures;
use App\Tests\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final class OpportunityWebControllerTest extends AbstractWebTestCase
{
    public const BASE_URL = '/oportunidades';

    public function testListRouteRendersHTMLSuccessfully(): void
    {
        $this->client->request('GET', self::BASE_URL);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Oportunidades');
    }

    public function testDetailsRouteNotFound(): void
    {
        $url = sprintf('%s/%s', self::BASE_URL, Uuid::v4()->toRfc4122());
        $this->client->request('GET', $url);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testDetailsRouteForExistingOpportunity(): void
    {
        $url = sprintf('%s/%s', self::BASE_URL, OpportunityFixtures::OPPORTUNITY_ID_1);
        $this->client->request('GET', $url);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Inscrição para o Concurso de Cordelistas');
        $this->assertStringContainsString(OpportunityFixtures::OPPORTUNITY_ID_1, $this->client->getResponse()->getContent());
    }

    public function testControllerDetailsMethodDirectly(): void
    {
        $controller = self::getContainer()->get(OpportunityWebController::class);
        $controller->setContainer(self::getContainer());

        $response = $controller->details(Uuid::fromString(OpportunityFixtures::OPPORTUNITY_ID_1));

        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('Inscrição para o Concurso de Cordelistas', $response->getContent());
    }

    public function testControllerListMethodDirectly(): void
    {
        $controller = self::getContainer()->get(OpportunityWebController::class);
        $controller->setContainer(self::getContainer());

        $request = new Request();
        $request->attributes->set('_route', 'web_opprotunity_list');
        $requestStack = self::getContainer()->get('request_stack');
        $requestStack->push($request);

        $response = $controller->list($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertStringContainsString('Oportunidades', $response->getContent());

        $requestStack->pop();
    }
}
