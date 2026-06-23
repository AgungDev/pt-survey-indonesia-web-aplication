<?php

namespace App\Domain\Image\Contracts;

use App\Application\Image\DTOs\ImageMetadataDTO;
use App\Application\Image\DTOs\ImageUploadDTO;
use Illuminate\Http\UploadedFile;

interface ImageServiceInterface
{
    public function compress(string $path, int $quality = 80): ImageMetadataDTO;

    public function resize(string $path, int $width, int $height): ImageMetadataDTO;

    public function convert(string $path, string $targetFormat): ImageMetadataDTO;

    public function generateThumbnail(string $path): ImageMetadataDTO;

    public function generateResponsiveImages(string $path): array;

    public function optimize(UploadedFile|string $source, ?string $targetDirectory = null): ImageMetadataDTO;

    public function delete(string $path): bool;

    public function replace(string $currentPath, string $newSource): ImageMetadataDTO;

    public function getMetadata(string $path): ImageMetadataDTO;

    public function calculateChecksum(string $path): string;

    public function generateUrl(string $path, int $expiration = 3600): string;
}
