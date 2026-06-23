<?php

namespace App\Application\Image\DTOs;

class ImageProcessDTO
{
    public function __construct(
        public readonly string $sourcePath,
        public readonly string $targetPath,
        public readonly string $format,
        public readonly int $quality,
        public readonly int $width,
        public readonly int $height,
        public readonly array $extra = []
    ) {
    }

    public function toArray(): array
    {
        return [
            'source_path' => $this->sourcePath,
            'target_path' => $this->targetPath,
            'format' => $this->format,
            'quality' => $this->quality,
            'width' => $this->width,
            'height' => $this->height,
            'extra' => $this->extra,
        ];
    }
}
