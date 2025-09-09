<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web\Admin;

use App\Controller\Web\Admin\SealAdminController;
use App\DataFixtures\Entity\SealFixtures;
use App\Service\SealService;
use App\Tests\AbstractAdminWebTestCase;
use App\Tests\Utils\CsrfTokenHelper;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final class SealAdminControllerTest extends AbstractAdminWebTestCase
{
    private SealAdminController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = static::getContainer()->get(SealAdminController::class);
    }

    public function testListPageRenderHTMLWithSuccess(): void
    {
        $listUrl = $this->router->generate('admin_seal_list');

        $this->client->request(Request::METHOD_GET, $listUrl);

        $response = $this->client->getResponse();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', $this->translator->trans('seals'));
        $this->assertSelectorTextContains('tr  th:nth-of-type(1)', $this->translator->trans('name'));
        $this->assertSelectorTextContains('tr  th:nth-of-type(2)', $this->translator->trans('status'));
        $this->assertSelectorTextContains('tr  th:nth-of-type(3)', $this->translator->trans('created_at'));
        $this->assertSelectorTextContains('tr  th:nth-of-type(4)', $this->translator->trans('actions'));
    }

    public function testControllerListMethodDirectly(): void
    {
        $this->assertInstanceOf(Response::class, $this->controller->list());
    }

    public function testControllerGetOneMethodDirectly(): void
    {
        $this->assertInstanceOf(Response::class, $this->controller->getOne(1));
    }

    public function testCreatePageRenderHTMLWithSuccess(): void
    {
        $createUrl = $this->router->generate('admin_seal_add');

        $this->client->request(Request::METHOD_GET, $createUrl);

        $this->assertResponseIsSuccessful();
    }

    public function testCreateWithFormData(): void
    {
        $createUrl = $this->router->generate('admin_seal_add');
        $request = $this->client->request(Request::METHOD_GET, $createUrl);

        $token = $request->filter('input[name="token"]')->attr('value');

        $formData = [
            'token' => $token,
            'name' => 'seal test',
            'description' => 'seal test description and informations',
        ];

        $this->client->request(Request::METHOD_POST, $createUrl, $formData);

        $listUrl = $this->router->generate('admin_seal_list');

        $this->assertResponseRedirects($listUrl, Response::HTTP_FOUND);
    }

    public function testCreateWithInvalidFormData(): void
    {
        $createUrl = $this->router->generate('admin_seal_add');

        $request = $this->client->request(Request::METHOD_GET, $createUrl);

        $form = $request->selectButton('Salvar')->form([]);

        $this->client->submit($form);

        $this->assertSelectorTextContains('.toast-body', 'Nome: Este valor não deveria ser vazio.');
    }

    public function testCreateThrowGeneralException(): void
    {
        $serviceStub = $this->createMock(SealService::class);
        $serviceStub->expects($this->once())
            ->method('create')
            ->willThrowException(new Exception('Generic error'));

        $formData = [
            'token' => CsrfTokenHelper::getValidToken(SealAdminController::CREATE_FORM_ID, $this->client),
            'name' => 'seal test',
            'description' => 'seal test description and informations',
        ];

        $translator = self::getContainer()->get('translator');
        $controller = new SealAdminController($serviceStub, $translator);
        $controller->setContainer($this->client->getContainer());

        $request = new Request(request: $formData);
        $request->setMethod(Request::METHOD_POST);

        $this->expectException(Exception::class);
        $controller->add($request);

        $this->expectExceptionMessage('Generic Error');
    }

    public function testEditPageRenderHTMLWithSuccess(): void
    {
        $editUrl = $this->router->generate('admin_seal_edit', [
            'id' => Uuid::fromString(SealFixtures::SEAL_ID_3),
        ]);

        $this->client->request(Request::METHOD_GET, $editUrl);

        $this->assertResponseIsSuccessful();
    }

    public function testEditWithFormData(): void
    {
        $editUrl = $this->router->generate('admin_seal_edit', [
            'id' => SealFixtures::SEAL_ID_2,
        ]);
        $request = $this->client->request(Request::METHOD_GET, $editUrl);

        $token = $request->filter('input[name="token"]')->attr('value');

        $formData = [
            'token' => $token,
            'name' => 'seal test edit',
            'active' => false,
            'description' => 'seal test edit description and informations',
        ];

        $this->client->request(Request::METHOD_POST, $editUrl, $formData);

        $listUrl = $this->router->generate('admin_seal_list');

        $this->assertResponseRedirects($listUrl, Response::HTTP_FOUND);
    }

    public function testEditWithInvalidFormData(): void
    {
        $editUrl = $this->router->generate('admin_seal_edit', [
            'id' => SealFixtures::SEAL_ID_2,
        ]);
        $request = $this->client->request(Request::METHOD_GET, $editUrl);

        $form = $request->selectButton('Salvar')->form([
            'name' => 'a',
        ]);

        $this->client->submit($form);

        $this->assertSelectorTextContains('.toast-body', 'Nome: O valor é muito curto. Deveria de ter 2 caracteres ou mais.');
    }

    public function testEditWithoutSeal(): void
    {
        $editUrl = $this->router->generate('admin_seal_edit', [
            'id' => Uuid::v4()->toRfc4122(),
        ]);

        $this->client->request(Request::METHOD_GET, $editUrl);

        $this->client->followRedirect();

        $this->assertSelectorTextContains('.toast-body', 'The requested Seal was not found.');
    }

    public function testEditThrowGeneralException(): void
    {
        $serviceStub = $this->createMock(SealService::class);
        $serviceStub->expects($this->once())
            ->method('update')
            ->willThrowException(new Exception('Generic error'));

        $formData = [
            'token' => CsrfTokenHelper::getValidToken(SealAdminController::EDIT_FORM_ID, $this->client),
            'name' => 'seal test edit',
        ];

        $translator = self::getContainer()->get('translator');
        $controller = new SealAdminController($serviceStub, $translator);
        $controller->setContainer($this->client->getContainer());

        $request = new Request(request: $formData);
        $request->setMethod(Request::METHOD_POST);

        $this->expectException(Exception::class);
        $controller->edit(SealFixtures::SEAL_ID_2, $request);

        $this->expectExceptionMessage('Generic Error');
    }

    public function testRemove(): void
    {
        $removeUrl = $this->router->generate('admin_seal_remove', [
            'id' => Uuid::fromString(SealFixtures::SEAL_ID_2),
        ]);

        $this->client->request(Request::METHOD_GET, $removeUrl);

        $redirectUrl = $this->router->generate('admin_seal_list');

        $this->assertResponseRedirects($redirectUrl, Response::HTTP_FOUND);
    }

    public function testRemoveNotFound(): void
    {
        $removeUrl = $this->router->generate('admin_seal_remove', [
            'id' => Uuid::v4()->toRfc4122(),
        ]);

        $this->client->request(Request::METHOD_GET, $removeUrl);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
