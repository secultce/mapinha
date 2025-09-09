<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web\Admin;

use App\Controller\Web\Admin\TagAdminController;
use App\DataFixtures\Entity\TagFixtures;
use App\Service\TagService;
use App\Tests\AbstractAdminWebTestCase;
use App\Tests\Utils\CsrfTokenHelper;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final class TagAdminControllerTest extends AbstractAdminWebTestCase
{
    private TagAdminController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = static::getContainer()->get(TagAdminController::class);
    }

    public function testListPageRenderHTMLWithSuccess(): void
    {
        $listUrl = $this->router->generate('admin_tag_list');

        $this->client->request(Request::METHOD_GET, $listUrl);

        $response = $this->client->getResponse();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', $this->translator->trans('tags'));
        $this->assertSelectorTextContains('tr th:nth-of-type(1)', $this->translator->trans('name'));
        $this->assertSelectorTextContains('tr th:nth-of-type(2)', $this->translator->trans('actions'));
    }

    public function testControllerListMethodDirectly(): void
    {
        $this->assertInstanceOf(Response::class, $this->controller->list());
    }

    public function testCreatePageRenderHTMLWithSuccess(): void
    {
        $createUrl = $this->router->generate('admin_tag_create');

        $this->client->request(Request::METHOD_GET, $createUrl);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', $this->translator->trans('view.tag.create'));
    }

    public function testCreateWithFormData(): void
    {
        $createUrl = $this->router->generate('admin_tag_create');
        $request = $this->client->request(Request::METHOD_GET, $createUrl);

        $token = $request->filter('input[name="token"]')->attr('value');

        $formData = [
            'token' => $token,
            'name' => 'tag test',
        ];

        $this->client->request(Request::METHOD_POST, $createUrl, $formData);

        $listUrl = $this->router->generate('admin_tag_list');

        $this->assertResponseRedirects($listUrl, Response::HTTP_FOUND);
    }

    public function testCreateWithInvalidFormData(): void
    {
        $createUrl = $this->router->generate('admin_tag_create');
        $request = $this->client->request(Request::METHOD_GET, $createUrl);

        $token = $request->filter('input[name="token"]')->attr('value');

        $formData = [
            'token' => $token,
            'name' => '',
        ];

        $this->client->request(Request::METHOD_POST, $createUrl, $formData);

        $this->assertSelectorTextContains('.toast-body', 'Nome: Este valor não deveria ser vazio.');
    }

    public function testCreateThrowGeneralException(): void
    {
        $serviceStub = $this->createMock(TagService::class);
        $serviceStub->expects($this->once())
            ->method('create')
            ->willThrowException(new Exception('Generic error'));

        $formData = [
            'token' => CsrfTokenHelper::getValidToken(TagAdminController::CREATE_FORM_ID, $this->client),
            'name' => 'tag test',
        ];

        $translator = self::getContainer()->get('translator');
        $controller = new TagAdminController($serviceStub, $translator);
        $controller->setContainer($this->client->getContainer());

        $request = new Request(request: $formData);
        $request->setMethod(Request::METHOD_POST);

        $this->expectException(Exception::class);
        $controller->create($request);

        $this->expectExceptionMessage('Generic Error');
    }

    public function testEditPageRenderHTMLWithSuccess(): void
    {
        $editUrl = $this->router->generate('admin_tag_edit', [
            'id' => Uuid::fromString(TagFixtures::TAG_ID_3),
        ]);

        $this->client->request(Request::METHOD_GET, $editUrl);

        $this->assertResponseIsSuccessful();
    }

    public function testEditWithFormData(): void
    {
        $editUrl = $this->router->generate('admin_tag_edit', [
            'id' => TagFixtures::TAG_ID_2,
        ]);
        $request = $this->client->request(Request::METHOD_GET, $editUrl);

        $token = $request->filter('input[name="token"]')->attr('value');

        $formData = [
            'token' => $token,
            'name' => 'tag test edit',
        ];

        $this->client->request(Request::METHOD_POST, $editUrl, $formData);

        $listUrl = $this->router->generate('admin_tag_list');

        $this->assertResponseRedirects($listUrl, Response::HTTP_FOUND);
    }

    public function testEditWithInvalidFormData(): void
    {
        $editUrl = $this->router->generate('admin_tag_edit', [
            'id' => TagFixtures::TAG_ID_2,
        ]);
        $request = $this->client->request(Request::METHOD_GET, $editUrl);

        $token = $request->filter('input[name="token"]')->attr('value');

        $formData = [
            'token' => $token,
            'name' => 't',
        ];

        $this->client->request(Request::METHOD_POST, $editUrl, $formData);

        $this->assertSelectorTextContains('.toast-body', 'Nome: O valor é muito curto. Deveria de ter 2 caracteres ou mais.');
    }

    public function testEditWithoutTag(): void
    {
        $editUrl = $this->router->generate('admin_tag_edit', [
            'id' => Uuid::v4()->toRfc4122(),
        ]);

        $this->client->request(Request::METHOD_GET, $editUrl);

        $this->client->followRedirect();

        $this->assertSelectorTextContains('.toast-body', 'The requested Tag was not found.');
    }

    public function testEditThrowGeneralException(): void
    {
        $serviceStub = $this->createMock(TagService::class);
        $serviceStub->expects($this->once())
            ->method('update')
            ->willThrowException(new Exception('Generic error'));

        $formData = [
            'token' => CsrfTokenHelper::getValidToken(TagAdminController::EDIT_FORM_ID, $this->client),
            'name' => 'tag test edit',
        ];

        $translator = self::getContainer()->get('translator');
        $controller = new TagAdminController($serviceStub, $translator);
        $controller->setContainer($this->client->getContainer());

        $request = new Request(request: $formData);
        $request->setMethod(Request::METHOD_POST);

        $this->expectException(Exception::class);
        $controller->edit($request, TagFixtures::TAG_ID_2);

        $this->expectExceptionMessage('Generic Error');
    }

    public function testRemove(): void
    {
        $removeUrl = $this->router->generate('admin_tag_remove', [
            'id' => Uuid::fromString(TagFixtures::TAG_ID_2),
        ]);

        $this->client->request(Request::METHOD_GET, $removeUrl);

        $redirectUrl = $this->router->generate('admin_tag_list');

        $this->assertResponseRedirects($redirectUrl, Response::HTTP_FOUND);
    }

    public function testRemoveThrowGeneralException(): void
    {
        $serviceStub = $this->createMock(TagService::class);
        $serviceStub->expects($this->once())
            ->method('remove')
            ->willThrowException(new Exception('Generic error'));

        $translator = self::getContainer()->get('translator');
        $controller = new TagAdminController($serviceStub, $translator);
        $controller->setContainer($this->client->getContainer());

        $this->expectException(Exception::class);
        $controller->remove(TagFixtures::TAG_ID_2);

        $this->expectExceptionMessage('Generic Error');
    }
}
