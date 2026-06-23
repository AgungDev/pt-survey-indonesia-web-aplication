<?php

namespace App\Application\Image\DTOs;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ImageUploadDTO
{
    public function __construct(
        public readonly UploadedFile $file,
        public readonly string $originalName,
        public readonly string $mimeType,
        public readonly string $extension,
        public readonly int $size,
        public readonly string $checksum,
    ) {
    }

    public static function fromUploadedFile(UploadedFile $file): self
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');

        return new self(
            file: $file,
            originalName: $file->getClientOriginalName(),
            mimeType: $file->getClientMimeType() ?: 'application/octet-stream',
            extension: $extension,
            size: $file->getSize() ?? 0,
            checksum: hash_file('sha256', $file->getRealPath()),
        );
    }

    public function generateFilename(): string
    {
        return Str::uuid()->toString() . '.' . $this->extension;
    }

    public function toArray(): array
    {
        return [
            'original_name' => $this->originalName,
            'mime_type' => $this->mimeType,
            'extension' => $this->extension,
            'size' => $this->size,
            'checksum' => $this->checksum,
        ];
    }
}
