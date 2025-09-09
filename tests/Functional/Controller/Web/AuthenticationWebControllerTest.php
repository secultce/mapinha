<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web;

use App\Controller\Web\AuthenticationWebController;
use App\Tests\AbstractWebTestCase;
use App\Tests\Utils\WebLoginHelper;

class AuthenticationWebControllerTest extends AbstractWebTestCase
{
    private AuthenticationWebController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = static::getContainer()->get(AuthenticationWebController::class);
    }

    public function testLoginPageRenderHTMLWithSuccess(): void
    {
        $this->client->request('GET', '/login');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('form', 'Entrar');
    }

    public function testLoginRedirectIfUserIsAlreadyAuthenticated(): void
    {
        WebLoginHelper::login($this->client, 'henriquelopeslima@example.com', 'Aurora@2024');

        $this->client->request('GET', '/login');

        $this->assertResponseRedirects('/');

        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(200);
    }

    public function testRegisterPageRenderHTMLWithSuccess(): void
    {
        $this->client->request('GET', '/cadastro');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', 'Crie sua conta');
    }

    public function testRegisterWithValidData(): void
    {
        $request = $this->client->request('GET', '/cadastro');

        $this->assertResponseStatusCodeSame(200);

        $form = $request->selectButton('Continuar')->form([
            'first_name' => 'Henrique',
            'last_name' => 'Lima',
            'birth_date' => '1990-01-01',
            'cpf' => '12345678901',
            'phone' => '12345678901',
            'email' => 'emailtest@example.com',
            'password' => 'Aurora@2024',
            'confirm_password' => 'Aurora@2024',
        ]);

        $this->client->submit($form);

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h3', 'Seu cadastro foi criado com sucesso!');
    }

    public function testRegisterRedirectIfUserIsAlreadyAuthenticated(): void
    {
        WebLoginHelper::login($this->client, 'henriquelopeslima@example.com', 'Aurora@2024');

        $this->client->request('GET', '/cadastro');

        $this->assertResponseRedirects('/');

        $this->client->followRedirect();

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('.toast.danger .toast-body', $this->translator->trans('view.authentication.error.already_logged_in'));
    }

    public function testRegisterWithExistingEmail(): void
    {
        $request = $this->client->request('GET', '/cadastro');

        $this->assertResponseStatusCodeSame(200);

        $form = $request->selectButton('Continuar')->form([
            'first_name' => 'Henrique',
            'last_name' => 'Lima',
            'birth_date' => '1990-01-01',
            'cpf' => '12345678901',
            'phone' => '12345678901',
            'email' => 'alessandrofeitoza@example.com',
            'password' => 'Aurora@2024',
            'confirm_password' => 'Aurora@2024',
        ]);

        $this->client->submit($form);

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('.toast.danger .toast-body', $this->translator->trans('view.authentication.error.email_in_use'));
    }

    public function testRegisterWithInvalidData(): void
    {
        $request = $this->client->request('GET', '/cadastro');

        $this->assertResponseStatusCodeSame(200);

        $form = $request->selectButton('Continuar')->form([
            'first_name' => 'H',
        ]);

        $this->client->submit($form);

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('.toast.danger .toast-body', 'O valor é muito curto. Deveria de ter 2 caracteres ou mais.');
    }
}
