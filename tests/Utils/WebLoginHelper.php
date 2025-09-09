<?php

declare(strict_types=1);

namespace App\Tests\Utils;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class WebLoginHelper
{
    public static function login(KernelBrowser $client, string $email, string $password): void
    {
        $request = $client->request('GET', '/login');

        $form = $request->selectButton('Entrar')->form([
            'email' => $email,
            'password' => $password,
        ]);

        $client->submit($form);

        $client->followRedirect();
    }
}
