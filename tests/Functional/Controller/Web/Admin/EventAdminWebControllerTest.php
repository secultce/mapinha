<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web\Admin;

use App\Controller\Web\Admin\EventAdminController;
use App\DataFixtures\Entity\EventFixtures;
use App\Service\Interface\EventServiceInterface;
use App\Tests\AbstractAdminWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class EventAdminWebControllerTest extends AbstractAdminWebTestCase
{
    private EventAdminController $controller;
    private EventServiceInterface $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = static::getContainer()->get(EventAdminController::class);
        $this->service = static::getContainer()->get(EventServiceInterface::class);
    }

    public function testListPageRenderHTMLWithSuccess(): void
    {
        $listUrl = $this->router->generate('admin_event_list');
        $this->client->request(Request::METHOD_GET, $listUrl);
        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', $this->translator->trans('events'));
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
        $removeUrl = $this->router->generate('admin_event_remove', [
            'id' => Uuid::fromString(EventFixtures::EVENT_ID_1),
        ]);
        $this->client->request(Request::METHOD_GET, $removeUrl);
        $redirectUrl = $this->router->generate('admin_event_list');
        $this->assertResponseRedirects($redirectUrl, Response::HTTP_FOUND);
    }

    public function testRemoveNotFound(): void
    {
        $removeUrl = $this->router->generate('admin_event_remove', [
            'id' => Uuid::v4()->toRfc4122(),
        ]);
        $this->client->request(Request::METHOD_GET, $removeUrl);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testCreatePageRenderHTMLWithSuccess(): void
    {
        $createUrl = $this->router->generate('admin_event_create');
        $this->client->request(Request::METHOD_GET, $createUrl);
        $this->assertResponseIsSuccessful();
    }

    public function testCreateWithFormData(): void
    {
        $createUrl = $this->router->generate('admin_event_create');
        $request = $this->client->request(Request::METHOD_GET, $createUrl);
        $token = $request->filter('input[name="token"]')->attr('value');
        $formData = [
            'token' => $token,
            'name' => 'Evento Teste',
            'description' => 'Descrição do evento',
            'age_rating' => 'L',
            'cultural_language' => 'pt',
            'type' => 'show',
            'end_date' => '2025-12-31',
            'max_capacity' => 100,
        ];
        $this->client->request(Request::METHOD_POST, $createUrl, $formData);
        $listUrl = $this->router->generate('admin_event_list');
        $this->assertResponseRedirects($listUrl, Response::HTTP_FOUND);
    }

    public function testCreateWithInvalidFormData(): void
    {
        $createUrl = $this->router->generate('admin_event_create');
        $request = $this->client->request(Request::METHOD_GET, $createUrl);
        $form = $request->selectButton('Criar')->form([]);
        $this->client->submit($form);
        $this->assertSelectorTextContains('.toast-body', 'The provided data violates one or more constraints.');
    }

    public function testEditPageRenderHTMLWithSuccess(): void
    {
        $editUrl = $this->router->generate('admin_event_edit', [
            'id' => Uuid::fromString(EventFixtures::EVENT_ID_1),
        ]);
        $this->client->request(Request::METHOD_GET, $editUrl);
        $this->assertResponseIsSuccessful();
    }

    public function testEditWithFormData(): void
    {
        $editUrl = $this->router->generate('admin_event_edit', [
            'id' => Uuid::fromString(EventFixtures::EVENT_ID_1),
        ]);
        $request = $this->client->request(Request::METHOD_GET, $editUrl);
        $token = $request->filter('input[name="token"]')->attr('value');
        $formData = [
            'token' => $token,
            'name' => 'Evento Editado',
            'description' => 'Descrição editada',
            'age_rating' => '10',
            'type' => 'workshop',
            'max_capacity' => 200,
            'culturalLanguages' => ['pt'],
            'tags' => [],
        ];
        $this->client->request(Request::METHOD_POST, $editUrl, $formData);
        $listUrl = $this->router->generate('admin_event_list');
        $this->assertResponseRedirects($listUrl, Response::HTTP_FOUND);
    }

    public function testEditWithoutEvent(): void
    {
        $editUrl = $this->router->generate('admin_event_edit', [
            'id' => Uuid::v4()->toRfc4122(),
        ]);
        $this->client->request(Request::METHOD_GET, $editUrl);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.toast-body', 'The requested Event was not found.');
    }

    public function testTimelinePageRenderHTMLWithSuccess(): void
    {
        $timelineUrl = $this->router->generate('admin_event_timeline', [
            'id' => Uuid::fromString(EventFixtures::EVENT_ID_1),
        ]);
        $this->client->request(Request::METHOD_GET, $timelineUrl);
        $this->assertResponseIsSuccessful();
    }

    public function testTimelineNotFound(): void
    {
        $timelineUrl = $this->router->generate('admin_event_timeline', [
            'id' => Uuid::v4()->toRfc4122(),
        ]);
        $this->client->request(Request::METHOD_GET, $timelineUrl);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
