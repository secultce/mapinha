<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\ExporterInscriptions;

use App\Entity\Agent;
use App\Entity\InscriptionEvent;
use App\Enum\ExportFormatEnum;
use App\Enum\InscriptionEventStatusEnum;
use App\Service\ExporterInscriptions\ExportableSourceInterface;
use App\Service\ExporterInscriptions\XlsExporterStrategy;
use DateTimeImmutable;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\StreamedResponse;

class XlsExporterStrategyTest extends TestCase
{
    private XlsExporterStrategy $strategy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->strategy = new XlsExporterStrategy();
    }

    public function testSupportsXlsFormat(): void
    {
        $this->assertTrue($this->strategy->supports(ExportFormatEnum::XLS));
    }

    public function testDoesNotSupportOtherFormats(): void
    {
        $this->assertFalse($this->strategy->supports(ExportFormatEnum::PDF));
    }

    public function testExport(): void
    {
        $source = $this->createMock(ExportableSourceInterface::class);
        $source->method('getExportSourceName')->willReturn('test-event');

        $agent1 = $this->createMock(Agent::class);
        $agent1->method('getName')->willReturn('John Doe');

        $inscription1 = $this->createMock(InscriptionEvent::class);
        $inscription1->method('getAgent')->willReturn($agent1);
        $inscription1->method('getStatus')->willReturn(InscriptionEventStatusEnum::CONFIRMED->value);
        $inscription1->method('getCreatedAt')->willReturn(new DateTimeImmutable('2023-01-01 10:00:00'));

        $agent2 = $this->createMock(Agent::class);
        $agent2->method('getName')->willReturn('Jane Doe');

        $inscription2 = $this->createMock(InscriptionEvent::class);
        $inscription2->method('getAgent')->willReturn($agent2);
        $inscription2->method('getStatus')->willReturn(InscriptionEventStatusEnum::ACTIVE->value);
        $inscription2->method('getCreatedAt')->willReturn(new DateTimeImmutable('2023-01-02 12:30:00'));

        $inscriptions = [$inscription1, $inscription2];

        $response = $this->strategy->export($source, $inscriptions);

        $this->assertInstanceOf(StreamedResponse::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $response->headers->get('Content-Type'));
        $this->assertSame('attachment;filename="inscriptions-test-event.xlsx"', $response->headers->get('Content-Disposition'));

        ob_start();
        $response->sendContent();
        $content = ob_get_clean();

        $this->assertNotEmpty($content);

        $tempFile = tmpfile();
        fwrite($tempFile, $content);
        $metaData = stream_get_meta_data($tempFile);
        $filePath = $metaData['uri'];

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        $this->assertSame('Inscriptions', $sheet->getTitle());
        $this->assertSame('Name', $sheet->getCell('A1')->getValue());
        $this->assertSame('Status', $sheet->getCell('B1')->getValue());
        $this->assertSame('Created At', $sheet->getCell('C1')->getValue());

        $this->assertSame('John Doe', $sheet->getCell('A2')->getValue());
        $this->assertSame(InscriptionEventStatusEnum::getName(3), $sheet->getCell('B2')->getValue());
        $this->assertSame('01/01/2023 10:00:00', $sheet->getCell('C2')->getValue());

        $this->assertSame('Jane Doe', $sheet->getCell('A3')->getValue());
        $this->assertSame(InscriptionEventStatusEnum::getName(1), $sheet->getCell('B3')->getValue());
        $this->assertSame('02/01/2023 12:30:00', $sheet->getCell('C3')->getValue());

        fclose($tempFile);
    }
}
