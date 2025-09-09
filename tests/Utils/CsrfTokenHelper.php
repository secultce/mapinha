<?php

declare(strict_types=1);

namespace App\Tests\Utils;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;

class CsrfTokenHelper
{
    public static function getValidToken(string $tokenId, KernelBrowser $client): string
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
}
