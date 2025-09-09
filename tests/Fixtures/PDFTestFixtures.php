<?php

declare(strict_types=1);

namespace App\Tests\Fixtures;

use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class PDFTestFixtures
{
    public static function getPDFValid(): UploadedFile
    {
        return self::getUploadedFile('test.pdf');
    }

    public static function getPDFValidPath(): string
    {
        $path = realpath(sprintf('%s/pdfs/%s', __DIR__, 'test.pdf'));

        if (!file_exists($path)) {
            throw new RuntimeException("PDF file not found: $path");
        }

        return $path;
    }

    private static function getUploadedFile(string $pdf): UploadedFile
    {
        $path = realpath(sprintf('%s/pdfs/%s', __DIR__, $pdf));

        if (!file_exists($path)) {
            throw new RuntimeException("PDF file not found: $path");
        }

        $type = mime_content_type($path);

        if ('application/pdf' !== $type) {
            throw new RuntimeException('Invalid file type');
        }

        return new UploadedFile($path, $pdf, $type, null, true);
    }
}
