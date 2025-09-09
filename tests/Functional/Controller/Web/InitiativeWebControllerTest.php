<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web;

use App\Controller\Web\InitiativeWebController;
use App\DataFixtures\Entity\InitiativeFixtures;
use App\Exception\Initiative\InitiativeResourceNotFoundException;
use App\Tests\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class InitiativeWebControllerTest extends AbstractWebTestCase
{
    private InitiativeWebController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = static::getContainer()->get(InitiativeWebController::class);
    }

    public function testInitiativePageShowRenderHTMLWithSuccess(): void
    {
        $this->client->request('GET', '/iniciativas/'.InitiativeFixtures::INITIATIVE_ID_1);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Vozes do Sertão');
    }

    public function testInitiativePageShowExist(): void
    {
        $uuid = Uuid::fromString(InitiativeFixtures::INITIATIVE_ID_1);

        $this->assertInstanceOf(Response::class, $this->controller->show($uuid));
    }

    public function testGetOneInitiativeNotFound(): void
    {
        $uuid = Uuid::v4();

        $this->expectException(InitiativeResourceNotFoundException::class);

        $this->controller->show($uuid);
    }

    public function testInitiativePageListRenderHTMLWithSuccess(): void
    {
        $this->client->request('GET', '/iniciativas');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', $this->translator->trans('initiatives'));
        $this->assertSelectorTextContains('.justify-content-between > .fw-bold', $this->translator->trans('view.initiative.quantity.total'));
    }

    public function testInitiativePageListExist(): void
    {
        $this->assertInstanceOf(Response::class, $this->controller->list(new Request(['order' => 'DESC'])));
    }
}
