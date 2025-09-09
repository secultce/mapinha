<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web\Admin;

use App\Controller\Web\Admin\CulturalLanguageAdminController;
use App\DataFixtures\Entity\CulturalLanguageFixtures;
use App\Tests\AbstractAdminWebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\Uuid;

final class CulturalLanguageAdminWebControllerTest extends AbstractAdminWebTestCase
{
    private CulturalLanguageAdminController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = static::getContainer()->get(CulturalLanguageAdminController::class);
    }

    public function testListPageRenderHTMLWithSuccess(): void
    {
        $url = $this->router->generate('admin_cultural_language_list');
        $this->client->request(Request::METHOD_GET, $url);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', $this->translator->trans('cultural_language'));
        $this->assertSelectorTextContains('tr th:nth-of-type(1)', $this->translator->trans('name'));
        $this->assertSelectorTextContains('tr th:nth-of-type(2)', $this->translator->trans('description'));
        $this->assertSelectorTextContains('tr th:nth-of-type(3)', $this->translator->trans('actions'));
    }

    public function testCreatePageRenderHTML(): void
    {
        $url = $this->router->generate('admin_cultural_language_create');
        $this->client->request(Request::METHOD_GET, $url);

        $response = $this->client->getResponse();
        $this->assertInstanceOf(Response::class, $response);
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', $this->translator->trans('view.cultural_language.create'));
    }

    public function testCreateWithValidData(): void
    {
        $createUrl = $this->router->generate('admin_cultural_language_create');
        $request = $this->client->request(Request::METHOD_GET, $createUrl);

        $token = $request->filter('input[name="token"]')->attr('value');

        $formData = [
            'token' => $token,
            'name' => 'Nova Linguagem',
        ];

        $this->client->request(Request::METHOD_POST, $createUrl, $formData);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains(
            '.toast.success .toast-body',
            $this->translator->trans('view.cultural_language.message.created')
        );
    }

    public function testCreateWithInvalidFormData(): void
    {
        $createUrl = $this->router->generate('admin_cultural_language_create');

        $crawler = $this->client->request(Request::METHOD_GET, $createUrl);
        $token = $crawler->filter('input[name="token"]')->attr('value');

        $formData = [
            'token' => $token,
            'name' => '',
        ];
        $this->client->request(Request::METHOD_POST, $createUrl, $formData);

        $this->assertSelectorTextContains(
            '.toast.danger .toast-body',
            'Nome: Este valor não deveria ser vazio.'
        );
    }

    public function testEditPageRenderHTML(): void
    {
        $editUrl = $this->router->generate('admin_cultural_language_edit', [
            'id' => Uuid::fromString(CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_3),
        ]);

        $this->client->request(Request::METHOD_GET, $editUrl);
        $this->assertResponseIsSuccessful();
    }

    public function testEditWithFormData(): void
    {
        $editUrl = $this->router->generate('admin_cultural_language_edit', [
            'id' => CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_3,
        ]);
        $request = $this->client->request(Request::METHOD_GET, $editUrl);

        $token = $request->filter('input[name="token"]')->attr('value');

        $formData = [
            'token' => $token,
            'name' => 'Cultural Language test edit',
            'description' => 'This is a test description for cultural language edit.',
        ];

        $this->client->request(Request::METHOD_POST, $editUrl, $formData);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains(
            '.toast.success .toast-body',
            $this->translator->trans('view.cultural_language.message.edited')
        );
    }

    public function testEditWithInvalidFormData(): void
    {
        $editUrl = $this->router->generate('admin_cultural_language_edit', [
            'id' => CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_3,
        ]);

        $crawler = $this->client->request(Request::METHOD_GET, $editUrl);
        $token = $crawler->filter('input[name="token"]')->attr('value');

        $this->client->request(Request::METHOD_POST, $editUrl, [
            'token' => $token,
            'name' => 'c',
            'description' => 'c',
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains(
            '.toast.danger .toast-body',
            'Nome: O valor é muito curto. Deveria de ter 2 caracteres ou mais.'
        );
    }

    public function testEditWithoutLanguage(): void
    {
        $editUrl = $this->router->generate(
            'admin_cultural_language_edit',
            ['id' => Uuid::v4()->toRfc4122()]
        );

        $this->client->request(Request::METHOD_GET, $editUrl);

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains(
            '.toast.danger .toast-body',
            'The requested CulturalLanguage was not found.'
        );
    }

    public function testRemove(): void
    {
        $id = Uuid::fromString(CulturalLanguageFixtures::CULTURAL_LANGUAGE_ID_4);
        $url = $this->router->generate('admin_cultural_language_delete', ['id' => $id]);
        $this->client->request(Request::METHOD_GET, $url);

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains(
            '.toast.success .toast-body',
            $this->translator->trans('view.cultural_language.message.deleted')
        );

        $this->assertSelectorTextContains(
            'h2',
            $this->translator->trans('cultural_language')
        );
    }
}
