<?php

namespace App\Infrastructure\Image\Processors;

use App\Application\Image\Exceptions\ImageProcessingException;
use App\Application\Image\Exceptions\InvalidImageException;
use App\Application\Image\Exceptions\UnsupportedFormatException;
use Intervention\Image\ImageManager;

class ImageProcessor
{
    protected ImageManager $manager;

    public function __construct(array $driverOptions = [])
    {
        $this->manager = new ImageManager($driverOptions ?: ['driver' => 'gd']);
    }

    public function validateInputFormat(string $path, array $supportedFormats): void
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if ($extension === '') {
            throw new InvalidImageException(sprintf('Image path "%s" has no extension.', $path));
        }

        if (!in_array($extension, $supportedFormats, true)) {
            throw new UnsupportedFormatException(sprintf('Format "%s" is not supported.', $extension));
        }
    }

    public function getImage(string $sourcePath)
    {
        if (!file_exists($sourcePath)) {
            throw new InvalidImageException(sprintf('Image file "%s" does not exist.', $sourcePath));
        }

        try {
            return $this->manager->make($sourcePath)->orientate();
        } catch (\Exception $exception) {
            throw new ImageProcessingException(sprintf('Unable to open image "%s": %s', $sourcePath, $exception->getMessage()), 0, $exception);
        }
    }

    public function saveEncodedImage($image, string $format, int $quality): string
    {
        try {
            return (string) $image->encode($format, $quality);
        } catch (\Exception $exception) {
            throw new ImageProcessingException(sprintf('Unable to encode image to %s: %s', $format, $exception->getMessage()), 0, $exception);
        }
    }

    public function getMetadata(string $sourcePath): array
    {
        $image = $this->getImage($sourcePath);

        return [
            'width' => $image->width(),
            'height' => $image->height(),
            'size' => filesize($sourcePath) ?: 0,
            'mime_type' => $image->mime(),
            'extension' => strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION)),
        ];
    }

    public function calculateChecksum(string $path): string
    {
        if (!file_exists($path)) {
            throw new InvalidImageException(sprintf('Cannot checksum non-existent image "%s".', $path));
        }

        return hash_file('sha256', $path);
    }

    public function resize(string $sourcePath, int $width, int $height, int $quality, string $format)
    {
        $image = $this->getImage($sourcePath);

        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        return $this->saveEncodedImage($image, $format, $quality);
    }

    public function convert(string $sourcePath, string $format, int $quality)
    {
        $image = $this->getImage($sourcePath);

        return $this->saveEncodedImage($image, $format, $quality);
    }

    public function compress(string $sourcePath, int $quality, string $format)
    {
        $image = $this->getImage($sourcePath);
        return $this->saveEncodedImage($image, $format, $quality);
    }
}
