<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\City;
use App\Entity\State;
use App\Service\Interface\StateServiceInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class StateEntityTest extends KernelTestCase
{
    private StateServiceInterface $stateService;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->stateService = self::getContainer()->get(StateServiceInterface::class);
    }

    public function testStateEntity(): void
    {
        $state = $this->stateService->findOneBy([]);

        $this->assertInstanceOf(State::class, $state);
        $this->assertNotEmpty($state->getId());
        $this->assertEquals('Acre', $state->getName());
        $this->assertEquals('AC', $state->getAcronym());
        $this->assertEquals('Norte', $state->getRegion());
        $this->assertInstanceOf(City::class, $state->getCapital());

        $arrayData = $state->toArray();
        $expectedArray = [
            'id' => '3d31e092-0316-4204-8627-4470dafa7e40',
            'name' => 'Acre',
            'acronym' => 'AC',
            'capital' => '99ce2c19-78e4-4e25-bc33-4b69cc7603bd',
            'region' => 'Norte',
        ];

        $this->assertEquals($expectedArray, $arrayData);
    }
}
