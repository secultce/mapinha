<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web\Admin;

use App\Controller\Web\Admin\DashboardAdminController;
use App\Tests\AbstractAdminWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DashboardAdminControllerTest extends AbstractAdminWebTestCase
{
    private DashboardAdminController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = static::getContainer()->get(DashboardAdminController::class);
    }

    public function testIndexPageRenderHTMLWithSuccess(): void
    {
        $dashboardUrl = $this->router->generate('admin_dashboard');

        $this->client->request(Request::METHOD_GET, $dashboardUrl);

        $response = $this->client->getResponse();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseIsSuccessful();

        $this->assertSelectorExists('[data-cy="agent-card-dashboard"]');
        $this->assertSelectorExists('[data-cy="opportunity-card-dashboard"]');
        $this->assertSelectorExists('[data-cy="initiative-card-dashboard"]');

        $this->assertSelectorExists('[data-cy="event-card-dashboard"]');
        $this->assertSelectorExists('[data-cy="space-card-dashboard"]');

        $this->assertSelectorTextContains('h1, h2, h3', $this->translator->trans('dashboard'));

        $this->assertSelectorExists('.recent-registrations-card');
    }

    public function testControllerIndexMethodDirectly(): void
    {
        $this->assertInstanceOf(Response::class, $this->controller->index());
    }
}
