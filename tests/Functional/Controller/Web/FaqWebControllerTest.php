<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web;

use App\Controller\Web\FaqWebController;
use App\Tests\AbstractWebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FaqWebControllerTest extends AbstractWebTestCase
{
    private FaqWebController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = static::getContainer()->get(FaqWebController::class);
    }

    public function testFaqPageExists(): void
    {
        $this->assertInstanceOf(Response::class, $this->controller->faq());
    }

    public function testFaqPageRenderHTMLWithSuccess(): void
    {
        $this->client->request('GET', '/faq');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $this->translator->trans('view.faq.title'));
    }
}
