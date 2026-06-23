<?php

namespace App\Application\Image\Services;

use App\Application\Image\DTOs\ImageMetadataDTO;
use App\Application\Image\DTOs\ImageUploadDTO;
use App\Application\Image\Exceptions\ImageProcessingException;
use App\Application\Image\Exceptions\InvalidImageException;
use App\Domain\Image\Contracts\ImageServiceInterface;
use App\Domain\Image\Contracts\ImageStorageInterface;
use App\Infrastructure\Image\Processors\ImageProcessor;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile as SymfonyUploadedFile;

class ImageService implements ImageServiceInterface
{
    public function __construct(
        protected readonly ImageProcessor $processor,
        protected readonly ImageStorageInterface $storage,
        protected readonly array $config,
    ) {
    }

    public function compress(string $path, int $quality = 80): ImageMetadataDTO
    {
        $sourcePath = $this->resolvePath($path);
        $this->processor->validateInputFormat($sourcePath, $this->config['supported_input_formats']);

        $format = $this->config['default_format'];
        $contents = $this->processor->compress($sourcePath, $quality, $format);
        $target = $this->buildPath('optimized', $format);

        return $this->storeProcessedImage($target, $contents, $sourcePath);
    }

    public function resize(string $path, int $width, int $height): ImageMetadataDTO
    {
        $sourcePath = $this->resolvePath($path);
        $this->processor->validateInputFormat($sourcePath, $this->config['supported_input_formats']);
        $format = $this->config['default_format'];
        $contents = $this->processor->resize($sourcePath, $width, $height, $this->config['quality'], $format);
        $target = $this->buildPath('responsive/' . $width . 'x' . $height, $format);

        return $this->storeProcessedImage($target, $contents, $sourcePath);
    }

    public function convert(string $path, string $targetFormat): ImageMetadataDTO
    {
        $sourcePath = $this->resolvePath($path);
        $targetFormat = strtolower($targetFormat);

        if (!in_array($targetFormat, $this->config['supported_output_formats'], true)) {
            throw new InvalidImageException(sprintf('Target format "%s" is not supported.', $targetFormat));
        }

        $contents = $this->processor->convert($sourcePath, $targetFormat, $this->config['quality']);
        $target = $this->buildPath('optimized', $targetFormat);

        return $this->storeProcessedImage($target, $contents, $sourcePath);
    }

    public function generateThumbnail(string $path): ImageMetadataDTO
    {
        $sourcePath = $this->resolvePath($path);
        $format = $this->config['default_format'];
        $contents = $this->processor->resize($sourcePath, $this->config['thumbnail_width'], $this->config['thumbnail_height'], $this->config['thumbnail_quality'], $format);
        $target = $this->buildPath('thumbnails', $format);

        return $this->storeProcessedImage($target, $contents, $sourcePath);
    }

    public function generateResponsiveImages(string $path): array
    {
        $sourcePath = $this->resolvePath($path);
        $output = [];

        foreach ($this->config['responsive_sizes'] as $label => [$width, $height]) {
            $format = $this->config['default_format'];
            $contents = $this->processor->resize($sourcePath, $width, $height, $this->config['quality'], $format);
            $target = $this->buildPath('responsive/' . $label, $format);
            $output[$label] = $this->storeProcessedImage($target, $contents, $sourcePath)->toArray();
        }

        return $output;
    }

    public function optimize(UploadedFile|string $source, ?string $targetDirectory = null): ImageMetadataDTO
    {
        $uploadDTO = $this->buildUploadDTO($source);
        $checksum = $uploadDTO->checksum;
        $cacheKey = $this->checksumCacheKey($checksum);

        if (Cache::has($cacheKey)) {
            $existing = Cache::get($cacheKey);
            if ($existing && $this->storage->exists($existing)) {
                return $this->getMetadata($existing);
            }
        }

        $originalPath = $this->storeOriginal($uploadDTO, $targetDirectory);
        $optimized = $this->compress($originalPath, $this->config['quality']);
        $this->generateThumbnail($optimized->path);
        $this->generateResponsiveImages($optimized->path);

        Cache::forever($cacheKey, $optimized->path);

        return $optimized;
    }

    public function delete(string $path): bool
    {
        return $this->storage->delete($path);
    }

    public function replace(string $currentPath, string $newSource): ImageMetadataDTO
    {
        $metadata = $this->optimize($newSource);
        $this->delete($currentPath);

        return $metadata;
    }

    public function getMetadata(string $path): ImageMetadataDTO
    {
        $sourcePath = $this->resolvePath($path);
        $metadata = $this->processor->getMetadata($sourcePath);
        $checksum = $this->processor->calculateChecksum($sourcePath);
        $url = $this->generateUrl($path);

        return new ImageMetadataDTO(
            path: $path,
            width: $metadata['width'],
            height: $metadata['height'],
            size: $metadata['size'],
            mimeType: $metadata['mime_type'],
            extension: $metadata['extension'],
            checksum: $checksum,
            url: $url,
        );
    }

    public function calculateChecksum(string $path): string
    {
        $sourcePath = $this->resolvePath($path);
        return $this->processor->calculateChecksum($sourcePath);
    }

    public function generateUrl(string $path, int $expiration = 3600): string
    {
        return $this->storage->url($path);
    }

    protected function resolvePath(string $path): string
    {
        if (file_exists($path)) {
            return $path;
        }

        $storagePath = $this->storage->path($path);

        if (!file_exists($storagePath)) {
            throw new InvalidImageException(sprintf('Image path "%s" could not be resolved.', $path));
        }

        return $storagePath;
    }

    protected function storeOriginal(ImageUploadDTO $uploadDTO, ?string $targetDirectory = null): string
    {
        $directory = $targetDirectory ?: trim($this->config['base_path'] . '/originals', '/');
        $path = $directory . '/' . $uploadDTO->generateFilename();
        $contents = file_get_contents($uploadDTO->file->getRealPath());

        return $this->storage->put($path, $contents, 'public');
    }

    protected function storeProcessedImage(string $target, string $contents, string $sourcePath): ImageMetadataDTO
    {
        $relativePath = $this->storage->put($target, $contents, 'public');
        $metadata = $this->processor->getMetadata($this->storage->path($relativePath));
        $checksum = $this->processor->calculateChecksum($this->storage->path($relativePath));
        $url = $this->generateUrl($relativePath);

        return new ImageMetadataDTO(
            path: $relativePath,
            width: $metadata['width'],
            height: $metadata['height'],
            size: $metadata['size'],
            mimeType: $metadata['mime_type'],
            extension: $metadata['extension'],
            checksum: $checksum,
            url: $url,
            extra: [
                'source' => $sourcePath,
            ],
        );
    }

    protected function buildPath(string $subdirectory, string $format): string
    {
        return trim($this->config['base_path'] . '/' . trim($subdirectory, '/'), '/') . '/' . Str::uuid()->toString() . '.' . $format;
    }

    protected function checksumCacheKey(string $checksum): string
    {
        return sprintf('image_checksum:%s', $checksum);
    }

    protected function buildUploadDTO(UploadedFile|string $source): ImageUploadDTO
    {
        if ($source instanceof UploadedFile) {
            return ImageUploadDTO::fromUploadedFile($source);
        }

        if (is_string($source)) {
            if (file_exists($source)) {
                $uploadedFile = new SymfonyUploadedFile(
                    $source,
                    basename($source),
                    mime_content_type($source) ?: 'application/octet-stream',
                    filesize($source),
                    null,
                    true
                );

                return ImageUploadDTO::fromUploadedFile($uploadedFile);
            }

            if ($this->storage->exists($source)) {
                $storagePath = $this->storage->path($source);
                $uploadedFile = new SymfonyUploadedFile(
                    $storagePath,
                    basename($storagePath),
                    mime_content_type($storagePath) ?: 'application/octet-stream',
                    filesize($storagePath),
                    null,
                    true
                );

                return ImageUploadDTO::fromUploadedFile($uploadedFile);
            }
        }

        throw new InvalidImageException('Invalid source provided for image optimization.');
    }
}
