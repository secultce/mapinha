<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Event;
use App\Enum\ExportFormatEnum;
use App\Repository\Interface\InscriptionEventRepositoryInterface;
use App\Service\ExporterInscriptions\InscriptionExporterStrategyInterface;
use App\Service\InscriptionEventExportService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\Uid\Uuid;

class InscriptionEventExportServiceTest extends TestCase
{
    private InscriptionEventRepositoryInterface|MockObject $inscriptionEventRepository;

    /** @var InscriptionExporterStrategyInterface[]|MockObject[] */
    private array $strategies;

    private InscriptionEventExportService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->inscriptionEventRepository = $this->createMock(InscriptionEventRepositoryInterface::class);
        $this->strategies = [
            $this->createMock(InscriptionExporterStrategyInterface::class),
            $this->createMock(InscriptionExporterStrategyInterface::class),
        ];

        $this->service = new InscriptionEventExportService(
            $this->inscriptionEventRepository,
            $this->strategies
        );
    }

    public function testExportSuccessfully(): void
    {
        $event = $this->createMock(Event::class);
        $uuid = Uuid::v4();
        $event->method('getId')->willReturn($uuid);

        $format = ExportFormatEnum::XLS;
        $inscriptions = [];
        $response = new Response('exported data');

        $this->inscriptionEventRepository
            ->expects($this->once())
            ->method('findInscriptionsByEvent')
            ->with((string) $uuid, PHP_INT_MAX)
            ->willReturn($inscriptions);

        $this->strategies[0]
            ->expects($this->once())
            ->method('supports')
            ->with($format)
            ->willReturn(true);

        $this->strategies[0]
            ->expects($this->once())
            ->method('export')
            ->with($event, $inscriptions)
            ->willReturn($response);

        $this->strategies[1]
            ->expects($this->never())
            ->method('supports');

        $this->strategies[1]
            ->expects($this->never())
            ->method('export');

        $result = $this->service->export($event, $format);

        $this->assertSame($response, $result);
    }

    public function testExportThrowsExceptionForUnsupportedFormat(): void
    {
        $event = $this->createMock(Event::class);
        $uuid = Uuid::v4();
        $event->method('getId')->willReturn($uuid);

        $format = ExportFormatEnum::PDF;

        $this->inscriptionEventRepository
            ->expects($this->once())
            ->method('findInscriptionsByEvent')
            ->with((string) $uuid, PHP_INT_MAX)
            ->willReturn([]);

        $this->strategies[0]
            ->expects($this->once())
            ->method('supports')
            ->with($format)
            ->willReturn(false);

        $this->strategies[1]
            ->expects($this->once())
            ->method('supports')
            ->with($format)
            ->willReturn(false);

        $this->expectException(UnsupportedMediaTypeHttpException::class);
        $this->expectExceptionMessage('Unsupported format');

        $this->service->export($event, $format);
    }
}
