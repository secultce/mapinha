<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web\Admin;

use App\Controller\Web\Admin\UserAdminController;
use App\DataFixtures\Entity\AgentFixtures;
use App\DataFixtures\Entity\UserFixtures;
use App\Tests\AbstractAdminWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

class UserAdminWebControllerTest extends AbstractAdminWebTestCase
{
    private UserAdminController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = static::getContainer()->get(UserAdminController::class);
    }

    public function testListPageRenderHTMLWithSuccess(): void
    {
        $listUrl = $this->router->generate('admin_user_list');

        $this->client->request(Request::METHOD_GET, $listUrl);

        $response = $this->client->getResponse();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', $this->translator->trans('Usuários'));
        $this->assertSelectorTextContains('tr  th:nth-of-type(1)', $this->translator->trans('name'));
        $this->assertSelectorTextContains('tr  th:nth-of-type(2)', $this->translator->trans('email'));
        $this->assertSelectorTextContains('tr  th:nth-of-type(3)', $this->translator->trans('image'));
        $this->assertSelectorTextContains('tr  th:nth-of-type(4)', $this->translator->trans('created_at'));
        $this->assertSelectorTextContains('tr  th:nth-of-type(5)', $this->translator->trans('actions'));
    }

    public function testControllerListMethodDirectly(): void
    {
        $this->assertInstanceOf(Response::class, $this->controller->list());
    }

    public function testTimelinePageRenderHTMLWithSuccess(): void
    {
        $timelineUrl = $this->router->generate('admin_user_timeline', [
            'id' => Uuid::fromString(UserFixtures::USER_ID_3),
        ]);

        $this->client->request(Request::METHOD_GET, $timelineUrl);

        $this->assertResponseIsSuccessful();
    }

    public function testTimelineNotFound(): void
    {
        $timelineUrl = $this->router->generate('admin_user_timeline', [
            'id' => Uuid::v4()->toRfc4122(),
        ]);

        $this->client->request(Request::METHOD_GET, $timelineUrl);

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testAccountPrivacyPageRenderHTMLWithSuccess(): void
    {
        $accountPrivacyUrl = $this->router->generate('admin_user_account_privacy', [
            'id' => Uuid::fromString(UserFixtures::USER_ID_3),
        ]);

        $this->client->request(Request::METHOD_GET, $accountPrivacyUrl);

        $this->assertResponseIsSuccessful();
    }

    public function testAccountPrivacyRedirectsToLoginWhenUserNotFound(): void
    {
        $this->client->request(Request::METHOD_GET, '/logout');

        $accountPrivacyUrl = $this->router->generate('admin_user_account_privacy', [
            'id' => Uuid::v4()->toRfc4122(),
        ]);

        $this->client->request(Request::METHOD_GET, $accountPrivacyUrl);

        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);
    }

    public function testEditUserProfilePageRenderHTMLWithSuccess(): void
    {
        $editUrl = $this->router->generate('admin_user_edit_profile', [
            'id' => Uuid::fromString(UserFixtures::USER_ID_3),
        ]);

        $this->client->request(Request::METHOD_GET, $editUrl);

        $this->assertResponseIsSuccessful();
    }

    public function testEditUserProfileRedirectsToLoginWhenUserNotFound(): void
    {
        $this->client->request(Request::METHOD_GET, '/logout');

        $editUrl = $this->router->generate('admin_user_edit_profile', [
            'id' => Uuid::v4()->toRfc4122(),
        ]);

        $this->client->request(Request::METHOD_GET, $editUrl);

        $this->assertResponseRedirects($this->router->generate('web_auth_login'), Response::HTTP_FOUND);
    }

    public function testEditUserProfileWithFormData(): void
    {
        $editUrl = $this->router->generate('admin_user_edit_profile', [
            'id' => UserFixtures::USER_ID_1,
        ]);
        $request = $this->client->request(Request::METHOD_GET, $editUrl);

        $token = $request->filter('input[name="token"]')->attr('value');

        $formData = [
            'token' => $token,
            'firstname' => 'Francisco',
            'lastname' => 'Alessandro Feitoza',
            'socialName' => 'Alessandro Feitoza',
            'email' => 'alessandrofeitoza@example.com',
            'password' => 'Aurora@2024',
            'agent' => Uuid::fromString(AgentFixtures::AGENT_ID_1),
            'name' => 'Alessandro',
            'short_description' => 'Desenvolvedor e evangelista de Software',
            'long_description' => 'Fomentador da comunidade de desenvolvimento, um dos fundadores da maior comunidade de PHP do Ceará (PHP com Rapadura)',
            'cargo' => 'Desenvolvedor Backend',
            'cpf' => '795.319.940-80',
        ];

        $this->client->request(Request::METHOD_POST, $editUrl, $formData);

        $this->assertSelectorTextContains('.toast-body', $this->translator->trans('view.user.message.updated'));
    }

    public function testEditUserProfileWithInvalidFormData(): void
    {
        $editUrl = $this->router->generate('admin_user_edit_profile', [
            'id' => UserFixtures::USER_ID_3,
        ]);
        $request = $this->client->request(Request::METHOD_GET, $editUrl);

        $token = $request->filter('input[name="token"]')->attr('value');

        $formData = [
            'token' => $token,
            'firstname' => '',
            'lastname' => '',
            'socialName' => '',
            'email' => 'invalid-email',
            'password' => '',
        ];

        $this->client->request(Request::METHOD_POST, $editUrl, $formData);

        $this->assertSelectorTextContains('.toast-body', 'The provided data violates one or more constraints.');
    }
}
