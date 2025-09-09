<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web;

use App\Controller\Web\HomepageWebController;
use App\Tests\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomepageWebControllerTest extends AbstractWebTestCase
{
    private HomepageWebController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = static::getContainer()->get(HomepageWebController::class);
    }

    public function testHomePageExists(): void
    {
        $this->assertInstanceOf(Response::class, $this->controller->homepage());
    }

    public function testHomePageRenderHTMLWithSuccess(): void
    {
        $this->client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Mapa das Periferias');
    }
}
