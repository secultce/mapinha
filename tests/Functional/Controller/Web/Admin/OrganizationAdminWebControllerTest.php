<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web\Admin;

use App\Controller\Web\Admin\OrganizationAdminController;
use App\DataFixtures\Entity\AgentFixtures;
use App\DataFixtures\Entity\OrganizationFixtures;
use App\DocumentService\OrganizationTimelineDocumentService;
use App\Service\OrganizationService;
use App\Tests\AbstractAdminWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

class OrganizationAdminWebControllerTest extends AbstractAdminWebTestCase
{
    private OrganizationAdminController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = static::getContainer()->get(OrganizationAdminController::class);
        $service = $this->createMock(OrganizationService::class);
        $documentService = $this->createMock(OrganizationTimelineDocumentService::class);

        $this->controller = new OrganizationAdminController(
            $service,
            $this->translator,
            $documentService,
        );
    }

    public function testListPageRenderHTMLWithSuccess(): void
    {
        $listUrl = $this->router->generate('admin_organization_list');

        $this->client->request(Request::METHOD_GET, $listUrl);

        $response = $this->client->getResponse();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', $this->translator->trans('my_organizations'));
        $this->assertSelectorTextContains('tr  th:nth-of-type(1)', $this->translator->trans('name'));
        $this->assertSelectorTextContains('tr  th:nth-of-type(2)', $this->translator->trans('created_at'));
        $this->assertSelectorTextContains('tr  th:nth-of-type(5)', $this->translator->trans('actions'));
    }

    public function testControllerListMethodDirectly(): void
    {
        $url = $this->urlGenerator->generate('admin_organization_list');

        $request = Request::create(
            $url,
            'GET',
            ['_route' => 'admin_organization_list']
        );

        $request->setSession(new Session(new MockFileSessionStorage()));

        $this->getContainer()->get('request_stack')->push($request);

        $this->assertInstanceOf(Response::class, $this->controller->list());
    }

    public function testControllerGetMethodDirectly(): void
    {
        $url = $this->urlGenerator->generate('admin_organization_get', ['id' => OrganizationFixtures::ORGANIZATION_ID_1]);

        $request = Request::create(
            $url,
            'GET',
            ['_route' => 'admin_organization_get']
        );

        $request->setSession(new Session(new MockFileSessionStorage()));

        $this->getContainer()->get('request_stack')->push($request);

        $this->assertInstanceOf(Response::class, $this->controller->getOne(Uuid::fromString(OrganizationFixtures::ORGANIZATION_ID_1)));
    }

    public function testControllerGetMethodDirectlyNotFound(): void
    {
        $id = Uuid::v4();
        $url = $this->urlGenerator->generate('admin_organization_get', ['id' => $id]);

        $request = Request::create(
            $url,
            'GET',
            ['_route' => 'admin_organization_get']
        );

        $request->setSession(new Session(new MockFileSessionStorage()));

        $this->getContainer()->get('request_stack')->push($request);

        self::expectException(NotFoundHttpException::class);

        $this->controller->getOne($id);
    }

    public function testRemoveAgentMethod(): void
    {
        $this->controller->removeAgent(Uuid::fromString(OrganizationFixtures::ORGANIZATION_ID_2), Uuid::fromString(AgentFixtures::AGENT_ID_2));

        $session = self::getContainer()->get('session');
        $flashBag = $session->getFlashBag();

        self::assertTrue($flashBag->has('success'));
        self::assertEquals($this->translator->trans('view.organization.message.deleted_member'), $flashBag->get('success')[0]);
        self::assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }
}
