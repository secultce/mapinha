<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use App\Enum\CompanyFrameworkEnum;
use App\Enum\OrganizationTypeEnum;

final class RegisterTestFixtures
{
    public static function city(): array
    {
        return [
            'firstname' => 'Bonitão',
            'lastname' => 'das Tapiocas',
            'userEmail' => 'bonitao@example.com',
            'password' => '!@#tdsyfd$%hhjghj',
            'cpf' => '1111111111111',
            'position' => 'Bonzão',
            'city' => '42b2c26f-85f0-44db-a7f9-758788d4e9a8',
            'cnpj' => '111111111111111111',
            'phone' => '9876541230',
            'email' => 'quixelo@quixelo.ce.gov.br',
        ];
    }

    public static function company(): array
    {
        return [
            'type' => OrganizationTypeEnum::EMPRESA->name,
            'cnpj' => '111111111111111111',
            'framework' => CompanyFrameworkEnum::NO_PROFIT->name,
            'name' => 'Movimento de Todos ONG',
            'site' => 'movimento.com',
            'phone' => '9876541230',
            'email' => 'movimento@movimento.com',
            'firstname' => 'José',
            'lastname' => 'das Tapiocas',
            'userEmail' => 'josedastapiocas@example.com',
            'password' => '!@#tdsyfd$%hhjghj',
            'cpf' => '1111111111111',
            'position' => 'Bonzão',
            'userPhone' => '9876541230',
        ];
    }
}
