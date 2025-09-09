<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Web;

use App\Controller\Web\AbstractWebController;
use App\Tests\AbstractWebTestCase;
use App\Tests\Utils\CsrfTokenHelper;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;

class AbstractWebControllerTest extends AbstractWebTestCase
{
    private AbstractWebController $controller;

    protected function setUp(): void
    {
        $this->controller = new class extends AbstractWebController {
            public function validCsrfToken(string $tokenId, Request $request): void
            {
                parent::validCsrfToken($tokenId, $request);
            }
        };
    }

    public function testGetOrderParamDefault(): void
    {
        $filters = ['name' => 'Talyson'];
        $result = $this->controller->getOrderParam($filters);

        $this->assertArrayHasKey('order', $result);
        $this->assertEquals('DESC', $result['order']);

        $this->assertArrayHasKey('filters', $result);
        $this->assertEquals($filters, $result['filters']);
    }

    public function testGetOrderParamWithASCOrder(): void
    {
        $filters = ['order' => 'ASC', 'name' => 'Talyson'];
        $result = $this->controller->getOrderParam($filters);

        $this->assertEquals('ASC', $result['order']);
        $expectedFilters = ['name' => 'Talyson'];
        $this->assertEquals($expectedFilters, $result['filters']);
    }

    public function testGetOrderParamWithDESCOrder(): void
    {
        $filters = ['order' => 'DESC', 'name' => 'Talyson'];
        $result = $this->controller->getOrderParam($filters);

        $this->assertEquals('DESC', $result['order']);
        $expectedFilters = ['name' => 'Talyson'];
        $this->assertEquals($expectedFilters, $result['filters']);
    }

    public function testGetOrderParamWithInvalidOrder(): void
    {
        $filters = ['order' => 'INVALID', 'name' => 'Talyson'];
        $result = $this->controller->getOrderParam($filters);

        $this->assertEquals('DESC', $result['order']);
        $expectedFilters = ['name' => 'Talyson'];
        $this->assertEquals($expectedFilters, $result['filters']);
    }

    public function testShouldNotThrowExceptionForAValidToken(): void
    {
        $tokenId = 'csrf_token_id_test';

        self::ensureKernelShutdown();
        $client = self::createClient();

        $token = CsrfTokenHelper::getValidToken($tokenId, $client);

        $request = new Request(request: ['token' => $token]);
        $request->setMethod(Request::METHOD_POST);

        $container = $client->getContainer();
        $this->controller->setContainer($container);

        try {
            $this->controller->validCsrfToken($tokenId, $request);
        } catch (InvalidCsrfTokenException) {
            $this->fail('Failed to assert that an InvalidCsrfTokenException is not thrown.');
        }

        $this->addToAssertionCount(1);
    }

    public function testMustThrowExceptionWhenATokenIsWrong(): void
    {
        $tokenId = 'csrf_token_id_test';
        $wrongTokenid = 'wrong_token_id';

        self::ensureKernelShutdown();
        $client = self::createClient();

        $token = CsrfTokenHelper::getValidToken($wrongTokenid, $client);

        $request = new Request(request: ['token' => $token]);
        $request->setMethod(Request::METHOD_POST);

        $container = $client->getContainer();
        $this->controller->setContainer($container);

        $this->expectException(InvalidCsrfTokenException::class);

        $container = $client->getContainer();
        $this->controller->setContainer($container);
        $this->controller->validCsrfToken($tokenId, $request);
    }

    public function testMustThrowExceptionWhenATokenIsSentFromADiferentSession(): void
    {
        $tokenId = 'csrf_token_id_test';

        $token = $this->getValidTokenFromAnotherSession($tokenId);

        self::ensureKernelShutdown();
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/');

        $request = $client->getRequest();
        $request->getSession()->start();

        /** @var RequestStack $requestStack */
        $requestStack = $client->getContainer()->get(RequestStack::class);
        $requestStack->push($request);

        new SessionTokenStorage($requestStack);

        $request = new Request(request: ['token' => $token]);
        $request->setMethod(Request::METHOD_POST);

        $this->expectException(InvalidCsrfTokenException::class);

        $container = $client->getContainer();
        $this->controller->setContainer($container);
        $this->controller->validCsrfToken($tokenId, $request);
    }

    public function getValidToken(string $tokenId, KernelBrowser $client): string
    {
        $client->request(Request::METHOD_GET, '/');

        $request = $client->getRequest();
        $request->getSession()->start();

        /** @var RequestStack $requestStack */
        $requestStack = $client->getContainer()->get(RequestStack::class);
        $requestStack->push($request);

        $storage = new SessionTokenStorage($requestStack);

        $tokenManager = new CsrfTokenManager(storage: $storage);

        return $tokenManager->getToken($tokenId)->getValue();
    }

    private function getValidTokenFromAnotherSession(string $tokenId): string
    {
        self::ensureKernelShutdown();
        $client = self::createClient();

        $client->request(Request::METHOD_GET, '/');

        $request = $client->getRequest();
        $request->getSession()->start();

        /** @var RequestStack $requestStack */
        $requestStack = $client->getContainer()->get(RequestStack::class);
        $requestStack->push($request);

        $storage = new SessionTokenStorage($requestStack);

        $tokenManager = new CsrfTokenManager(storage: $storage);

        return $tokenManager->getToken($tokenId)->getValue();
    }
}
