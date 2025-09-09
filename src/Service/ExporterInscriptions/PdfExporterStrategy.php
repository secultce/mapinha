<?php

declare(strict_types=1);

namespace App\Service\ExporterInscriptions;

use App\Enum\ExportFormatEnum;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class PdfExporterStrategy implements InscriptionExporterStrategyInterface
{
    public function __construct(private readonly Environment $twig)
    {
    }

    public function supports(ExportFormatEnum $format): bool
    {
        return ExportFormatEnum::PDF === $format;
    }

    public function export(ExportableSourceInterface $source, array $inscriptions): Response
    {
        $html = $this->twig->render('_admin/event/pdf/inscriptions.html.twig', [
            'event' => $source,
            'inscriptions' => $inscriptions,
        ]);

        $mpdf = new Mpdf(['tempDir' => sys_get_temp_dir()]);
        $mpdf->WriteHTML($html);
        $filename = sprintf('inscriptions-%s.pdf', $source->getExportSourceName());

        return new Response(
            $mpdf->Output($filename, Destination::STRING_RETURN),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
    }
}
