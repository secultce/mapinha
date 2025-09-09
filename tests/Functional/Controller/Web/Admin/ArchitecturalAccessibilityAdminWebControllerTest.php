<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web\Admin;

use App\Controller\Web\Admin\ArchitecturalAccessibilityAdminController;
use App\DataFixtures\Entity\ArchitecturalAccessibilityFixtures;
use App\Tests\AbstractAdminWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class ArchitecturalAccessibilityAdminWebControllerTest extends AbstractAdminWebTestCase
{
    private ArchitecturalAccessibilityAdminController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = static::getContainer()->get(ArchitecturalAccessibilityAdminController::class);
    }

    public function testListPageRenderHTMLWithSuccess(): void
    {
        $listUrl = $this->router->generate('admin_architectural_accessibility_list');
        $this->client->request(Request::METHOD_GET, $listUrl);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', $this->translator->trans('architectural_accessibility'));
        $this->assertSelectorTextContains('tr th:nth-of-type(1)', $this->translator->trans('name'));
        $this->assertSelectorTextContains('tr th:nth-of-type(2)', $this->translator->trans('actions'));
    }

    public function testControllerListMethodDirectly(): void
    {
        $this->assertInstanceOf(Response::class, $this->controller->list());
    }

    public function testCreatePageRenderHTML(): void
    {
        $createUrl = $this->router->generate('admin_architectural_accessibility_add');
        $this->client->request(Request::METHOD_GET, $createUrl);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', $this->translator->trans('view.architectural_accessibility.create'));
    }

    public function testCreateWithValidData(): void
    {
        $createUrl = $this->router->generate('admin_architectural_accessibility_add');

        $request = $this->client->request(Request::METHOD_GET, $createUrl);

        $token = $request->filter('input[name="token"]')->attr('value');

        $formData = [
            'token' => $token,
            'name' => 'New Accessibility',
        ];

        $this->client->request(Request::METHOD_POST, $createUrl, $formData);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseRedirects($this->router->generate('admin_architectural_accessibility_list'));
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.toast.success .toast-body', $this->translator->trans('view.architectural_accessibility.message.created'));
    }

    public function testCreateWithInvalidData(): void
    {
        $createUrl = $this->router->generate('admin_architectural_accessibility_add');

        $request = $this->client->request(Request::METHOD_GET, $createUrl);

        $token = $request->filter('input[name="token"]')->attr('value');

        $formData = [
            'token' => $token,
            'name' => 'T',
        ];

        $this->client->request(Request::METHOD_POST, $createUrl, $formData);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.toast.danger .toast-body', 'Nome: O valor é muito curto. Deveria de ter 2 caracteres ou mais.');
    }

    public function testEditPageRenderHTML(): void
    {
        $editUrl = $this->router->generate('admin_architectural_accessibility_edit', [
            'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_9,
        ]);
        $this->client->request(Request::METHOD_GET, $editUrl);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', $this->translator->trans('view.architectural_accessibility.edit'));
    }

    public function testEditWithValidData(): void
    {
        $editUrl = $this->router->generate('admin_architectural_accessibility_edit', [
            'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_9,
        ]);

        $request = $this->client->request(Request::METHOD_GET, $editUrl);

        $token = $request->filter('input[name="token"]')->attr('value');

        $formData = [
            'token' => $token,
            'name' => 'Updated Accessibility',
        ];

        $this->client->request(Request::METHOD_POST, $editUrl, $formData);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseRedirects($this->router->generate('admin_architectural_accessibility_list'));
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.toast.success .toast-body', $this->translator->trans('view.architectural_accessibility.message.updated'));
    }

    public function testEditWithInvalidData(): void
    {
        $editUrl = $this->router->generate('admin_architectural_accessibility_edit', [
            'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_9,
        ]);

        $request = $this->client->request(Request::METHOD_GET, $editUrl);

        $token = $request->filter('input[name="token"]')->attr('value');

        $formData = [
            'token' => $token,
            'name' => 'T',
        ];

        $this->client->request(Request::METHOD_POST, $editUrl, $formData);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.toast.danger .toast-body', 'Nome: O valor é muito curto. Deveria de ter 2 caracteres ou mais.');
    }

    public function testEditWithoutAccessibility(): void
    {
        $editUrl = $this->router->generate('admin_architectural_accessibility_edit', [
            'id' => Uuid::v4()->toRfc4122(),
        ]);
        $this->client->request(Request::METHOD_GET, $editUrl);

        $this->client->followRedirect();

        $this->assertSelectorTextContains('.toast-body', 'The requested ArchitecturalAccessibility was not found.');
    }

    public function testRemove(): void
    {
        $removeUrl = $this->router->generate('admin_architectural_accessibility_remove', [
            'id' => ArchitecturalAccessibilityFixtures::ARCHITECTURAL_ACCESSIBILITY_ID_9,
        ]);

        $this->client->request(Request::METHOD_GET, $removeUrl);

        $redirectUrl = $this->router->generate('admin_architectural_accessibility_list');

        $this->assertResponseRedirects($redirectUrl, Response::HTTP_FOUND);
    }

    public function testRemoveNotFound(): void
    {
        $removeUrl = $this->router->generate('admin_architectural_accessibility_remove', [
            'id' => Uuid::v4()->toRfc4122(),
        ]);

        $this->client->request(Request::METHOD_GET, $removeUrl);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
