<?php

declare(strict_types=1);

namespace App\DataFixtures\Entity;

use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class ImageFixtures
{
    public static function getAgentImage(): UploadedFile
    {
        return self::getUploadedFile('agent.png');
    }

    public static function getEventImage(): UploadedFile
    {
        return self::getUploadedFile('event.png');
    }

    public static function getInitiativeImage(): UploadedFile
    {
        return self::getUploadedFile('initiative.jpg');
    }

    public static function getOpportunityImage(): UploadedFile
    {
        return self::getUploadedFile('opportunity.png');
    }

    public static function getOrganizationImage(): UploadedFile
    {
        return self::getUploadedFile('organization.jpg');
    }

    public static function getSpaceImage(): UploadedFile
    {
        return self::getUploadedFile('space.png');
    }

    public static function getSpacePortfolioImage(): UploadedFile
    {
        return self::getUploadedFile('space.png');
    }

    public static function getUserImage(): UploadedFile
    {
        return self::getUploadedFile('user.png');
    }

    public static function getCoverImage(): UploadedFile
    {
        return self::getUploadedFile('entity-cover-image.png');
    }

    private static function getUploadedFile(string $image): UploadedFile
    {
        $path = realpath(sprintf('%s/images/%s', __DIR__, $image));

        if (!file_exists($path)) {
            throw new RuntimeException("Image file not found: $path");
        }

        $type = mime_content_type($path);

        return new UploadedFile($path, $image, $type, null, true);
    }
}
