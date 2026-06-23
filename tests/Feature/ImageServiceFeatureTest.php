<?php

namespace Tests\Feature;

use App\Application\Image\Services\ImageService;
use App\Infrastructure\Image\Drivers\LocalImageStorage;
use App\Infrastructure\Image\Processors\ImageProcessor;
use App\Domain\Image\Contracts\ImageServiceInterface;
use PHPUnit\Framework\TestCase;

class ImageServiceFeatureTest extends TestCase
{
    public function test_image_service_implements_interface(): void
    {
        $config = require __DIR__ . '/../../config/image.php';
        $service = new ImageService(
            new \App\Infrastructure\Image\Processors\ImageProcessor(['driver' => $config['driver']]),
            new LocalImageStorage(),
            $config,
        );

        $this->assertInstanceOf(ImageServiceInterface::class, $service);
    }
}
