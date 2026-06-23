<?php

namespace Tests\Unit;

use App\Application\Image\DTOs\ImageMetadataDTO;
use PHPUnit\Framework\TestCase;

class ImageDTOTest extends TestCase
{
    public function test_image_metadata_dto_can_be_created(): void
    {
        $dto = new ImageMetadataDTO(
            path: 'uploads/optimized/example.webp',
            width: 1600,
            height: 1200,
            size: 245760,
            mimeType: 'image/webp',
            extension: 'webp',
            checksum: 'abc123',
            url: 'https://example.com/storage/uploads/optimized/example.webp',
        );

        $this->assertSame('uploads/optimized/example.webp', $dto->path);
        $this->assertSame(1600, $dto->width);
        $this->assertSame('image/webp', $dto->mimeType);
        $this->assertArrayHasKey('url', $dto->toArray());
    }
}
