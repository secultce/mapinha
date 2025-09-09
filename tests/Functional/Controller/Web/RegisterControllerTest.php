<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web;

use App\Entity\Agent;
use App\Entity\Organization;
use App\Entity\User;
use App\Enum\OrganizationTypeEnum;
use App\Enum\UserRolesEnum;
use App\Regmel\Controller\Web\RegisterController;
use App\Regmel\Service\RegisterService;
use App\Service\Interface\CityServiceInterface;
use App\Service\Interface\StateServiceInterface;
use App\Tests\AbstractWebTestCase;
use App\Tests\Fixtures\RegisterTestFixtures;
use App\Tests\Utils\CsrfTokenHelper;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class RegisterControllerTest extends AbstractWebTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = static::getContainer()->get(EntityManagerInterface::class);
    }

    public function testCreateOrganizationWithFormData(): void
    {
        $createUrl = $this->router->generate('regmel_register_city');
        $request = $this->client->request(Request::METHOD_GET, $createUrl);

        $token = $request->filter('input[name="token"]')->attr('value');

        $formData = array_merge(RegisterTestFixtures::city(), [
            'token' => $token,
        ]);

        $this->client->request(Request::METHOD_POST, $createUrl, $formData);

        $homepageUrl = $this->router->generate('web_home_homepage');

        $this->assertResponseRedirects($homepageUrl, Response::HTTP_FOUND);

        $organizations = $this->entityManager->getRepository(Organization::class)->findBy([
            'name' => 'Quixelô',
            'type' => OrganizationTypeEnum::MUNICIPIO->value,
        ]);

        self::assertCount(1, $organizations);

        $agents = $this->entityManager->getRepository(Agent::class)->findBy([
            'name' => 'Bonitão das Tapiocas',
        ]);

        self::assertCount(1, $agents);

        $users = $this->entityManager->getRepository(User::class)->findBy([
            'firstname' => 'Bonitão',
            'lastname' => 'das Tapiocas',
            'role' => UserRolesEnum::ROLE_MUNICIPALITY->value,
        ]);

        self::assertCount(1, $users);
    }

    public function testCreateOrganizationWithInvalidFormData(): void
    {
        $createUrl = $this->router->generate('regmel_register_city');

        $request = $this->client->request(Request::METHOD_GET, $createUrl);

        $form = $request->selectButton('Salvar')->form([]);

        $this->client->submit($form);

        $this->assertSelectorTextContains('.toast-body', 'Nome: This value should not be blank.');
    }

    public function testCreateOrganizationThrowGeneralException(): void
    {
        $serviceStub = $this->createMock(RegisterService::class);
        $serviceStub->expects($this->once())
            ->method('saveOrganization')
            ->willThrowException(new Exception('Generic error'));

        $formData = array_merge(RegisterTestFixtures::city(), [
            'token' => CsrfTokenHelper::getValidToken(RegisterController::FORM_CITY, $this->client),
        ]);

        $translator = self::getContainer()->get('translator');
        $stateService = self::getContainer()->get(StateServiceInterface::class);
        $cityService = self::getContainer()->get(CityServiceInterface::class);
        $controller = new RegisterController($serviceStub, $stateService, $cityService, $translator);
        $controller->setContainer($this->client->getContainer());

        $request = new Request(request: $formData);
        $request->setMethod(Request::METHOD_POST);

        $response = $controller->registerCity($request);

        self::assertStringContainsString('Generic error', $response->getContent());
    }

    public function testCreateCompanyWithFormData(): void
    {
        $createUrl = $this->router->generate('regmel_register_company');
        $request = $this->client->request(Request::METHOD_GET, $createUrl);

        $token = $request->filter('input[name="token"]')->attr('value');

        $formData = array_merge(RegisterTestFixtures::company(), [
            'token' => $token,
        ]);

        $this->client->request(Request::METHOD_POST, $createUrl, $formData);

        $listUrl = $this->router->generate('web_organization_list');

        $this->assertResponseRedirects($listUrl, Response::HTTP_FOUND);

        $organizations = $this->entityManager->getRepository(Organization::class)->findBy([
            'name' => 'Movimento de Todos ONG',
            'type' => OrganizationTypeEnum::EMPRESA->value,
        ]);

        self::assertCount(1, $organizations);

        $agents = $this->entityManager->getRepository(Agent::class)->findBy([
            'name' => 'José das Tapiocas',
        ]);

        self::assertCount(1, $agents);

        $users = $this->entityManager->getRepository(User::class)->findBy([
            'firstname' => 'José',
            'lastname' => 'das Tapiocas',
            'role' => UserRolesEnum::ROLE_COMPANY->value,
        ]);

        self::assertCount(1, $users);
    }

    public function testCreateCompanyWithInvalidFormData(): void
    {
        $createUrl = $this->router->generate('regmel_register_company');

        $request = $this->client->request(Request::METHOD_GET, $createUrl);

        $form = $request->selectButton('Salvar')->form([]);

        $this->client->submit($form);

        $this->assertSelectorTextContains('.toast-body', 'Nome: This value should not be blank.');
    }

    public function testCreateCompanyThrowGeneralException(): void
    {
        $serviceStub = $this->createMock(RegisterService::class);
        $serviceStub->expects($this->once())
            ->method('saveOrganization')
            ->willThrowException(new Exception('Generic error'));

        $formData = array_merge(RegisterTestFixtures::company(), [
            'token' => CsrfTokenHelper::getValidToken(RegisterController::FORM_COMPANY, $this->client),
        ]);

        $translator = self::getContainer()->get('translator');
        $stateService = self::getContainer()->get(StateServiceInterface::class);
        $cityService = self::getContainer()->get(CityServiceInterface::class);
        $controller = new RegisterController($serviceStub, $stateService, $cityService, $translator);
        $controller->setContainer($this->client->getContainer());

        $request = new Request(request: $formData);
        $request->setMethod(Request::METHOD_POST);

        $response = $controller->registerCompany($request);

        self::assertStringContainsString('Generic error', $response->getContent());
    }
}
