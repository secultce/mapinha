<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web\Admin;

use App\Controller\Web\Admin\SpaceAdminController;
use App\DataFixtures\Entity\ActivityAreaFixtures;
use App\DataFixtures\Entity\SpaceFixtures;
use App\DataFixtures\Entity\TagFixtures;
use App\Service\Interface\SpaceServiceInterface;
use App\Tests\AbstractAdminWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class SpaceAdminWebControllerTest extends AbstractAdminWebTestCase
{
    private SpaceAdminController $controller;

    private SpaceServiceInterface $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = static::getContainer()->get(SpaceAdminController::class);
        $this->service = static::getContainer()->get(SpaceServiceInterface::class);
    }

    public function testListPageRenderHTMLWithSuccess(): void
    {
        $listUrl = $this->router->generate('admin_space_list');

        $this->client->request(Request::METHOD_GET, $listUrl);

        $response = $this->client->getResponse();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', $this->translator->trans('my_spaces'));
        $this->assertSelectorTextContains('tr  th:nth-of-type(1)', $this->translator->trans('name'));
        $this->assertSelectorTextContains('tr  th:nth-of-type(2)', $this->translator->trans('status'));
        $this->assertSelectorTextContains('tr  th:nth-of-type(3)', $this->translator->trans('created_at'));
        $this->assertSelectorTextContains('tr  th:nth-of-type(6)', $this->translator->trans('actions'));
    }

    public function testControllerListMethodDirectly(): void
    {
        $this->assertInstanceOf(Response::class, $this->controller->list());
    }

    public function testRemove(): void
    {
        $removeUrl = $this->router->generate('admin_space_remove', [
            'id' => Uuid::fromString(SpaceFixtures::SPACE_ID_3),
        ]);

        $this->client->request(Request::METHOD_GET, $removeUrl);

        $redirectUrl = $this->router->generate('admin_space_list');

        $this->assertResponseRedirects($redirectUrl, Response::HTTP_FOUND);
    }

    public function testRemoveNotFound(): void
    {
        $removeUrl = $this->router->generate('admin_space_remove', [
            'id' => Uuid::v4()->toRfc4122(),
        ]);

        $this->client->request(Request::METHOD_GET, $removeUrl);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testCreatePageRenderHTMLWithSuccess(): void
    {
        $createUrl = $this->router->generate('admin_space_create');

        $this->client->request(Request::METHOD_GET, $createUrl);

        $this->assertResponseIsSuccessful();
    }

    public function testCreateWithFormData(): void
    {
        $createUrl = $this->router->generate('admin_space_create');
        $request = $this->client->request(Request::METHOD_GET, $createUrl);

        $token = $request->filter('input[name="token"]')->attr('value');

        $formData = [
            'token' => $token,
            'name' => 'Space World',
            'maxCapacity' => 33,
            'isAccessible' => true,
        ];

        $this->client->request(Request::METHOD_POST, $createUrl, $formData);

        $listUrl = $this->router->generate('admin_space_list');

        $this->assertResponseRedirects($listUrl, Response::HTTP_FOUND);
    }

    public function testCreateWithInvalidFormData(): void
    {
        $createUrl = $this->router->generate('admin_space_create');

        $request = $this->client->request(Request::METHOD_GET, $createUrl);

        $form = $request->selectButton('Criar em Rascunho')->form([]);

        $this->client->submit($form);

        $this->assertSelectorTextContains('.toast-body', 'The provided data violates one or more constraints.');
    }

    public function testEditPageRenderHTMLWithSuccess(): void
    {
        $editUrl = $this->router->generate('admin_space_edit', [
            'id' => Uuid::fromString(SpaceFixtures::SPACE_ID_3),
        ]);

        $this->client->request(Request::METHOD_GET, $editUrl);

        $this->assertResponseIsSuccessful();
    }

    public function testEditWithFormData(): void
    {
        $editUrl = $this->router->generate('admin_space_edit', [
            'id' => Uuid::fromString(SpaceFixtures::SPACE_ID_3),
        ]);

        $request = $this->client->request(Request::METHOD_GET, $editUrl);

        $token = $request->filter('input[name="token"]')->attr('value');

        $formData = [
            'token' => $token,
            'name' => 'Space World',
            'maxCapacity' => 33,
            'isAccessible' => true,
            'activityAreas' => [
                ActivityAreaFixtures::ACTIVITY_AREA_ID_3,
            ],
            'tags' => [
                TagFixtures::TAG_ID_2,
            ],
        ];

        $this->client->request(Request::METHOD_POST, $editUrl, $formData);

        $listUrl = $this->router->generate('admin_space_list');

        $this->assertResponseRedirects($listUrl, Response::HTTP_FOUND);
    }

    public function testEditWithoutSpace(): void
    {
        $editUrl = $this->router->generate('admin_space_edit', [
            'id' => Uuid::v4()->toRfc4122(),
        ]);

        $this->client->request(Request::METHOD_GET, $editUrl);

        $this->client->followRedirect();

        $this->assertSelectorTextContains('.toast-body', 'The requested Space was not found.');
    }

    public function testTimelinePageRenderHTMLWithSuccess(): void
    {
        $timelineUrl = $this->router->generate('admin_space_timeline', [
            'id' => Uuid::fromString(SpaceFixtures::SPACE_ID_3),
        ]);

        $this->client->request(Request::METHOD_GET, $timelineUrl);

        $this->assertResponseIsSuccessful();
    }

    public function testTimelineNotFound(): void
    {
        $removeUrl = $this->router->generate('admin_space_timeline', [
            'id' => Uuid::v4()->toRfc4122(),
        ]);

        $this->client->request(Request::METHOD_GET, $removeUrl);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
