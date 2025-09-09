<?php

declare(strict_types=1);

namespace App\Tests\Functional\Repositories;

use App\DataFixtures\Entity\AgentFixtures;
use App\Entity\InscriptionEvent;
use App\Repository\InscriptionEventRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class InscriptionEventRepositoryTest extends KernelTestCase
{
    private InscriptionEventRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->repository = self::getContainer()->get(InscriptionEventRepository::class);
    }

    public function testFindInscriptions(): void
    {
        $limit = 10;
        $agentId = AgentFixtures::AGENT_ID_1;
        $result = $this->repository->findMyInscriptions($agentId, $limit);

        self::assertIsArray($result, 'O resultado deve ser um array');
        self::assertLessThanOrEqual($limit, count($result), "O resultado deve conter no máximo $limit itens");

        foreach ($result as $inscriptionEvent) {
            self::assertInstanceOf(
                InscriptionEvent::class,
                $inscriptionEvent,
                'Cada item deve ser uma instância de InscriptionEvent'
            );

            self::assertEquals(
                $agentId,
                $inscriptionEvent->getAgent()->getId()->toRfc4122(),
                'O agente associado deve corresponder ao ID das fixtures'
            );
        }
    }
}
