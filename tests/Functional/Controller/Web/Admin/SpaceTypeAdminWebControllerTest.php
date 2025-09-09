<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web\Admin;

use App\Controller\Web\Admin\SpaceTypeAdminController;
use App\DataFixtures\Entity\SpaceTypeFixtures;
use App\Service\Interface\SpaceTypeServiceInterface;
use App\Tests\AbstractAdminWebTestCase;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class SpaceTypeAdminWebControllerTest extends AbstractAdminWebTestCase
{
    private SpaceTypeAdminController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = static::getContainer()->get(SpaceTypeAdminController::class);
    }

    public function testListPageRenderHTMLWithSuccess(): void
    {
        $listUrl = $this->router->generate('admin_space_type_list');
        $this->client->request(Request::METHOD_GET, $listUrl);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', $this->translator->trans('space_type'));
        $this->assertSelectorTextContains('tr  th:nth-of-type(2)', $this->translator->trans('name'));
        $this->assertSelectorTextContains('tr  th:nth-of-type(3)', $this->translator->trans('actions'));
    }

    public function testControllerListMethodDirectly(): void
    {
        $this->assertInstanceOf(Response::class, $this->controller->list());
    }

    public function testCreatePageRenderHTML(): void
    {
        $createUrl = $this->router->generate('admin_space_type_create');
        $this->client->request(Request::METHOD_GET, $createUrl);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', $this->translator->trans('view.space_type.create'));
    }

    public function testCreateWithValidData(): void
    {
        $createUrl = $this->router->generate('admin_space_type_create');
        $this->client->request(Request::METHOD_POST, $createUrl, [
            'name' => 'New Space Type',
        ]);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseRedirects($this->router->generate('admin_space_type_list'));
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.toast.success .toast-body', $this->translator->trans('view.space_type.message.created'));
    }

    public function testCreateWithInvalidData(): void
    {
        $createUrl = $this->router->generate('admin_space_type_create');

        $this->client->request(Request::METHOD_POST, $createUrl, [
            'name' => 'T',
        ]);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.toast.danger .toast-body', 'Nome: O valor é muito curto. Deveria de ter 2 caracteres ou mais.');
    }

    public function testCreateWithGeneralExceptionDirectlyUsingPartialMock(): void
    {
        $request = Request::create($this->router->generate('admin_space_type_create'), Request::METHOD_POST, [
            'name' => 'New Space Type',
        ]);

        $serviceStub = $this->createMock(SpaceTypeServiceInterface::class);
        $serviceStub->expects($this->once())
            ->method('create')
            ->willThrowException(new Exception('Generic error'));

        $translator = self::getContainer()->get('translator');

        $controller = $this->getMockBuilder(SpaceTypeAdminController::class)
            ->setConstructorArgs([$serviceStub, $translator])
            ->onlyMethods(['render'])
            ->getMock();

        $controller->expects($this->once())
            ->method('render')
            ->willReturnCallback(function (string $view, array $parameters) {
                return new Response(json_encode($parameters));
            });

        $response = $controller->create($request);

        $this->assertInstanceOf(Response::class, $response);

        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('errors', $data);
        $this->assertContains('Generic error', $data['errors']);
    }

    public function testEditPageRenderHTML(): void
    {
        $editUrl = $this->router->generate('admin_space_type_edit', [
            'id' => SpaceTypeFixtures::SPACE_TYPE_ID_3,
        ]);
        $this->client->request(Request::METHOD_GET, $editUrl);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', $this->translator->trans('view.space_type.edit'));
    }

    public function testEditWithValidData(): void
    {
        $editUrl = $this->router->generate('admin_space_type_edit', [
            'id' => SpaceTypeFixtures::SPACE_TYPE_ID_3,
        ]);
        $this->client->request(Request::METHOD_POST, $editUrl, [
            'name' => 'Updated Space Type',
        ]);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseRedirects($this->router->generate('admin_space_type_list'));
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.toast.success .toast-body', $this->translator->trans('view.space_type.message.updated'));
    }

    public function testEditWithInvalidData(): void
    {
        $editUrl = $this->router->generate('admin_space_type_edit', [
            'id' => SpaceTypeFixtures::SPACE_TYPE_ID_3,
        ]);
        $this->client->request(Request::METHOD_POST, $editUrl, [
            'name' => 'T',
        ]);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('.toast.danger .toast-body', 'Nome: O valor é muito curto. Deveria de ter 2 caracteres ou mais.');
    }

    public function testEditWithoutSpaceType(): void
    {
        $editUrl = $this->router->generate('admin_space_type_edit', [
            'id' => Uuid::v4()->toRfc4122(),
        ]);
        $this->client->request(Request::METHOD_GET, $editUrl);

        $this->client->followRedirect();

        $this->assertSelectorTextContains('.toast-body', 'The requested SpaceType was not found.');
    }

    public function testEditWithGeneralExceptionDirectlyUsingPartialMock(): void
    {
        $request = Request::create($this->router->generate('admin_space_type_edit', [
            'id' => SpaceTypeFixtures::SPACE_TYPE_ID_3,
        ]), Request::METHOD_POST, [
            'name' => 'Updated Space Type',
        ]);

        $serviceStub = $this->createMock(SpaceTypeServiceInterface::class);
        $serviceStub->expects($this->once())
            ->method('update')
            ->willThrowException(new Exception('Generic error'));

        $translator = self::getContainer()->get('translator');

        $controller = $this->getMockBuilder(SpaceTypeAdminController::class)
            ->setConstructorArgs([$serviceStub, $translator])
            ->onlyMethods(['render'])
            ->getMock();

        $controller->expects($this->once())
            ->method('render')
            ->willReturnCallback(function (string $view, array $parameters) {
                return new Response(json_encode($parameters));
            });

        $response = $controller->edit($request, SpaceTypeFixtures::SPACE_TYPE_ID_3);

        $this->assertInstanceOf(Response::class, $response);

        $data = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('errors', $data);
        $this->assertContains('Generic error', $data['errors']);
    }
}
