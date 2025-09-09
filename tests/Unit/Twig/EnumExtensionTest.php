<?php

declare(strict_types=1);

namespace App\Tests\Unit\Twig;

use App\Enum\InscriptionEventStatusEnum;
use App\Twig\EnumExtension;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class EnumExtensionTest extends TestCase
{
    private EnumExtension $extension;

    protected function setUp(): void
    {
        parent::setUp();
        $this->extension = new EnumExtension();
    }

    public function testGetFunctions(): void
    {
        $functions = $this->extension->getFunctions();
        $this->assertCount(1, $functions);
        $this->assertSame('get_enum_case_name', $functions[0]->getName());
    }

    #[DataProvider('getEnumCaseNameData')]
    public function testGetEnumCaseName(?string $expected, ?int $value, string $enumClass): void
    {
        $this->assertSame($expected, $this->extension->getEnumCaseName($value, $enumClass));
    }

    public static function getEnumCaseNameData(): array
    {
        return [
            'valid active case' => ['active', 1, InscriptionEventStatusEnum::class],
            'valid cancelled case' => ['cancelled', 2, InscriptionEventStatusEnum::class],
            'valid confirmed case' => ['confirmed', 3, InscriptionEventStatusEnum::class],
            'valid suspended case' => ['suspended', 4, InscriptionEventStatusEnum::class],
            'invalid value' => [null, 99, InscriptionEventStatusEnum::class],
            'null value' => [null, null, InscriptionEventStatusEnum::class],
            'invalid enum class' => [null, 1, 'App\\Enum\\NonExistentEnum'],
        ];
    }
}
