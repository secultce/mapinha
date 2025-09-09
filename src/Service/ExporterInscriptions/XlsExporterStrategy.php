<?php

declare(strict_types=1);

namespace App\Service\ExporterInscriptions;

use App\Enum\ExportFormatEnum;
use App\Enum\InscriptionEventStatusEnum;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class XlsExporterStrategy implements InscriptionExporterStrategyInterface
{
    public function supports(ExportFormatEnum $format): bool
    {
        return ExportFormatEnum::XLS === $format;
    }

    public function export(ExportableSourceInterface $source, array $inscriptions): Response
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Inscriptions');

        $sheet->setCellValue('A1', 'Name');
        $sheet->setCellValue('B1', 'Status');
        $sheet->setCellValue('C1', 'Created At');

        $row = 2;
        foreach ($inscriptions as $inscription) {
            $sheet->setCellValue('A'.$row, $inscription->getAgent()->getName());
            $sheet->setCellValue('B'.$row, InscriptionEventStatusEnum::getName($inscription->getStatus()));
            $sheet->setCellValue('C'.$row, $inscription->getCreatedAt()->format('d/m/Y H:i:s'));
            $row++;
        }

        $filename = sprintf('inscriptions-%s.xlsx', $source->getExportSourceName());

        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function () use ($writer): void {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', sprintf('attachment;filename="%s"', $filename));
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
