<?php

namespace App\Application\Image\DTOs;

class ImageMetadataDTO
{
    public function __construct(
        public readonly string $path,
        public readonly int $width,
        public readonly int $height,
        public readonly int $size,
        public readonly string $mimeType,
        public readonly string $extension,
        public readonly string $checksum,
        public readonly string $url,
        public readonly array $extra = []
    ) {
    }

    public function toArray(): array
    {
        return [
            'path' => $this->path,
            'width' => $this->width,
            'height' => $this->height,
            'size' => $this->size,
            'mime_type' => $this->mimeType,
            'extension' => $this->extension,
            'checksum' => $this->checksum,
            'url' => $this->url,
            'extra' => $this->extra,
        ];
    }
}
