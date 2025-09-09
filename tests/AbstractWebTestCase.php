<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractWebTestCase extends WebTestCase
{
    protected KernelBrowser $client;

    protected RouterInterface $router;

    protected TranslatorInterface $translator;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->router = static::getContainer()->get(RouterInterface::class);
        $this->translator = static::getContainer()->get(TranslatorInterface::class);
    }
}
