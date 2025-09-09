<?php

declare(strict_types=1);

namespace App\Tests;

use App\Tests\Utils\WebLoginHelper;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class AbstractAdminWebTestCase extends AbstractWebTestCase
{
    protected UrlGeneratorInterface $urlGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $session = self::getContainer()->get('session.factory')->createSession();

        $session->start();
        $this->client->getContainer()->set(SessionInterface::class, $session);

        $this->urlGenerator = static::getContainer()->get('router');

        WebLoginHelper::login($this->client, 'henriquelopeslima@example.com', 'Aurora@2024');
    }
}
