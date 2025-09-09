<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\ExporterInscriptions;

use App\Enum\ExportFormatEnum;
use App\Service\ExporterInscriptions\ExportableSourceInterface;
use App\Service\ExporterInscriptions\PdfExporterStrategy;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class PdfExporterStrategyTest extends TestCase
{
    private Environment $twig;
    private PdfExporterStrategy $strategy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->twig = $this->createMock(Environment::class);
        $this->strategy = new PdfExporterStrategy($this->twig);
    }

    public function testSupportsPdfFormat(): void
    {
        $this->assertTrue($this->strategy->supports(ExportFormatEnum::PDF));
    }

    public function testDoesNotSupportOtherFormats(): void
    {
        $this->assertFalse($this->strategy->supports(ExportFormatEnum::XLS));
    }

    public function testExport(): void
    {
        $source = $this->createMock(ExportableSourceInterface::class);
        $source->method('getExportSourceName')->willReturn('test-event');

        $inscriptions = [['name' => 'John Doe'], ['name' => 'Jane Doe']];
        $html = '<h1>Test</h1>';

        $this->twig->expects($this->once())
            ->method('render')
            ->with('_admin/event/pdf/inscriptions.html.twig', [
                'event' => $source,
                'inscriptions' => $inscriptions,
            ])
            ->willReturn($html);

        $response = $this->strategy->export($source, $inscriptions);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame('application/pdf', $response->headers->get('Content-Type'));
        $this->assertSame('attachment; filename="inscriptions-test-event.pdf"', $response->headers->get('Content-Disposition'));

        $this->assertStringStartsWith('%PDF-', $response->getContent());
    }
}
