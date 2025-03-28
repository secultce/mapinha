<?php

declare(strict_types=1);

return [
    'app' => [
        'path' => './assets/app.js',
        'entrypoint' => true,
    ],
    'register' => [
        'path' => './assets/js/authentication/user/register.js',
        'entrypoint' => true,
    ],
    'opportunity-create' => [
        'path' => './assets/js/opportunity/create.js',
        'entrypoint' => true,
    ],
    '@symfony/ux-translator' => [
        'path' => './vendor/symfony/ux-translator/assets/dist/translator_controller.js',
    ],
    '@app/translations' => [
        'path' => './var/translations/index.js',
    ],
    '@app/translations/configuration' => [
        'path' => './var/translations/configuration.js',
    ],
    '@popperjs/core' => [
        'version' => '2.11.8',
    ],
    'intl-messageformat' => [
        'version' => '10.7.14',
    ],
    'tslib' => [
        'version' => '2.8.1',
    ],
    '@formatjs/fast-memoize' => [
        'version' => '2.2.6',
    ],
    '@formatjs/icu-messageformat-parser' => [
        'version' => '2.9.8',
    ],
    '@formatjs/icu-skeleton-parser' => [
        'version' => '1.8.12',
    ],
    '@iconify/iconify' => [
        'version' => '3.1.1',
    ],
    'air-datepicker' => [
        'version' => '3.5.3',
    ],
    'air-datepicker/locale/en.js' => [
        'version' => '3.5.3',
    ],
    'air-datepicker/locale/es.js' => [
        'version' => '3.5.3',
    ],
    'air-datepicker/locale/pt-BR.js' => [
        'version' => '3.5.3',
    ],
    'air-datepicker/locale/pt-br.js' => [
        'path' => 'vendor/air-datepicker/locale/pt-BR.js',
    ],
    'air-datepicker/air-datepicker.css' => [
        'version' => '3.5.3',
        'type' => 'css',
    ],
];
