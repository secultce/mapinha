<?php

declare(strict_types=1);

namespace App\Service\Interface;

use App\Entity\Photo;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

interface PhotoServiceInterface
{
    public function create(UploadedFile $uploadedFile, string $directoryParam, ?string $description = null): Photo;

    public function get(Uuid $id): ?Photo;

    public function delete(Photo $photo): void;
}
