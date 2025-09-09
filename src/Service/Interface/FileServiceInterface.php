<?php

declare(strict_types=1);

namespace App\Service\Interface;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileServiceInterface
{
    public function uploadFile(string $filename, string $content): void;

    public function uploadMixedFile(UploadedFile $uploadedFile, string $extraPath = '', ?string $optionalName = null): File;

    public function uploadImage(string $path, UploadedFile $uploadedFile): File;

    public function uploadPDF(UploadedFile $uploadedFile, ?string $fileName = null, string $extraPath = ''): File;

    public function readFile(string $filename): string;

    public function deleteFile(string $filename): void;

    public function deleteFileByUrl(string $url): void;

    public function getFileUrl(string $path): string;

    public function urlOfImage(string $path): string;

    public function urlOfPDF(string $filename): string;
}
