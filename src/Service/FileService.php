<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\File\InvalidFileExtensionException;
use App\Exception\File\InvalidFileMimeTypeException;
use App\Service\Interface\FileServiceInterface;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use League\MimeTypeDetection\FinfoMimeTypeDetector;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly class FileService implements FileServiceInterface
{
    private const IMAGE_ALLOWED_TYPES = ['image/png', 'image/jpeg'];
    private const IMAGE_ALLOWED_EXTENSIONS = ['png', 'jpg', 'jpeg'];
    private const ASSETS_PATTERN = '/^\/var\/www(?:\/var)?\/assets(.*)/';
    private string $storageDir;
    private string $storageUrl;
    private string $storageImagesDir;
    private string $storageImagesUrl;

    public function __construct(
        private FilesystemOperator $filesystem,
        private ParameterBagInterface $parameterBag,
    ) {
        $this->storageDir = $this->parameterBag->get('app.dir.storage');
        $this->storageUrl = $this->parameterBag->get('app.url.storage');
        $this->storageImagesDir = $this->parameterBag->get('app.dir.storage.images');
        $this->storageImagesUrl = $this->parameterBag->get('app.url.storage.images');
    }

    public function uploadFile(string $filename, string $content): void
    {
        $this->filesystem->write($filename, $content);
    }

    public function readFile(string $filename): string
    {
        return $this->filesystem->read($filename);
    }

    public function deleteFile(string $filename): void
    {
        if (1 === preg_match(self::ASSETS_PATTERN, $filename, $matches)) {
            $filename = $matches[1];
        }

        $this->filesystem->delete($filename);
    }

    public function deleteFileByUrl(string $url): void
    {
        $filename = str_replace($this->storageImagesUrl, '', $url);

        $path = dirname(__DIR__, 2)."/assets{$filename}";

        unlink($path);
    }

    public function getFileUrl(string $path): string
    {
        if (1 === preg_match(self::ASSETS_PATTERN, $path, $matches)) {
            $path = $matches[1];
        }

        return $path;
    }

    /**
     * @throws FilesystemException
     */
    public function uploadPDF(UploadedFile $uploadedFile, ?string $fileName = null, string $extraPath = ''): File
    {
        $fileName ??= uniqid('', true);
        $fileName = $fileName.'.'.$uploadedFile->guessExtension();
        $filePath = rtrim($this->storageDir, '/').$extraPath;

        $newFile = $uploadedFile->move($filePath, $fileName);

        $stream = fopen($newFile->getRealPath(), 'r');

        $this->validateMimeType($newFile->getRealPath(), $stream, ['application/pdf']);
        $this->validateExtension($uploadedFile, ['pdf']);

        fclose($stream);

        return $newFile;
    }

    /**
     * @throws FilesystemException
     */
    public function uploadMixedFile(UploadedFile $uploadedFile, string $extraPath = '', ?string $optionalName = null): File
    {
        if (null === $optionalName) {
            $fileName = uniqid('', true).'.'.$uploadedFile->getClientOriginalExtension();
        } else {
            $fileName = $optionalName.'.'.$uploadedFile->getClientOriginalExtension();
        }

        $filePath = rtrim($this->storageDir, '/').$extraPath;

        $newFile = $uploadedFile->move($filePath, $fileName);

        $stream = fopen($newFile->getRealPath(), 'r');

        $this->validateMimeType($newFile->getRealPath(), $stream, [
            'application/pdf',
            'application/jpg',
            'image/jpeg',
            'image/png',
            'image/jpg',
            'application/xml',
            'application/vnd.google-earth.kml+xml',
            'application/vnd.google-earth.kmz',
            'application/octet-stream',
        ]);
        $this->validateExtension($uploadedFile, ['pdf', 'jpeg', 'jpg', 'png', 'kml', 'kmz', 'shp']);

        fclose($stream);

        return $newFile;
    }

    /**
     * @throws FilesystemException
     */
    public function uploadImage(string $path, UploadedFile $uploadedFile): File
    {
        $fileName = uniqid('', true).'.'.$uploadedFile->guessExtension();
        $filePath = rtrim($path, '/').'/'.$fileName;

        $stream = fopen($uploadedFile->getRealPath(), 'r');

        $this->validateMimeType($filePath, $stream);
        $this->validateExtension($uploadedFile);

        $this->filesystem->writeStream($filePath, $stream);
        fclose($stream);

        return new File($this->storageImagesDir.$filePath);
    }

    /**
     * @throws InvalidFileMimeTypeException
     */
    private function validateMimeType(string $filePath, mixed $stream, array $allowedTypes = self::IMAGE_ALLOWED_TYPES): void
    {
        $mimeTypeDetector = new FinfoMimeTypeDetector();
        $mimeType = $mimeTypeDetector->detectMimeType($filePath, $stream);

        if (false === in_array($mimeType, $allowedTypes)) {
            throw new InvalidFileMimeTypeException();
        }
    }

    /**
     * @throws InvalidFileExtensionException
     */
    private function validateExtension(UploadedFile $uploadedFile, array $allowedExtensions = self::IMAGE_ALLOWED_EXTENSIONS): void
    {
        $extension = strtolower($uploadedFile->getClientOriginalExtension());

        if (false === in_array($extension, $allowedExtensions)) {
            throw new InvalidFileExtensionException();
        }
    }

    public function urlOfImage(string $path): string
    {
        return $this->storageImagesUrl.'/'.$path;
    }

    public function urlOfPDF(string $filename): string
    {
        return $this->storageUrl.'/'.$filename;
    }
}
