<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web\Admin;

use App\Enum\OrganizationTypeEnum;
use App\Tests\AbstractWebTestCase;

class CompanyWebControllerTest extends AbstractWebTestCase
{
    public function testAccessCompanyRegistrationForm(): void
    {
        $this->client->request('GET', '/cadastro/empresa');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('input[name="name"]');
        $this->assertSelectorExists('input[name="cnpj"]');
        $this->assertSelectorExists('input[name="email"]');
        $this->assertSelectorExists('input[name="site"]');
        $this->assertSelectorExists('input[name="phone"]');
        $this->assertSelectorExists('input[name="firstname"]');
        $this->assertSelectorExists('input[name="lastname"]');
        $this->assertSelectorExists('input[name="userPhone"]');
        $this->assertSelectorExists('input[name="position"]');
        $this->assertSelectorExists('input[name="cpf"]');
        $this->assertSelectorExists('input[name="userEmail"]');
        $this->assertSelectorExists('input[name="password"]');
    }

    public function testCompanyRegistrationSubmitWithValidData(): void
    {
        $crawler = $this->client->request('GET', '/cadastro/empresa');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Salvar')->form();

        $form['name'] = 'Empresa Exemplo';
        $form['type'] = OrganizationTypeEnum::EMPRESA->name;
        $form['email'] = 'empresa@example.com';
        $form['site'] = 'https://empresa.com';
        $form['phone'] = '(11) 99999-9999';
        $form['cnpj'] = '12.345.678/0001-99';
        $form['framework'] = 'profit';

        $form['firstname'] = 'Joao';
        $form['lastname'] = 'Silva';
        $form['userPhone'] = '(11) 91111-1111';
        $form['position'] = 'Diretor';
        $form['cpf'] = '123.456.789-00';
        $form['userEmail'] = 'joao.silva@example.com';
        $form['password'] = 'SenhaForte123';
        $form['confirm_password'] = 'SenhaForte123';

        $form['opportunity'] = $form['opportunity']->availableOptions()[0]->getValue();

        $this->client->submit($form);

        $this->assertResponseRedirects();
        $this->client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'empresa criada');
    }

    public function testCompanyRegistrationWithValidationErrors(): void
    {
        $crawler = $this->client->request('GET', '/cadastro/empresa');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Salvar')->form();

        $form['name'] = '';
        $form['email'] = 'email-invalido';
        $form['cnpj'] = '000';
        $form['password'] = '123';
        $form['confirm_password'] = '1234';

        $this->client->submit($form);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.alert-danger');
    }
}
