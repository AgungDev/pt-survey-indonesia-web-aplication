<?php

namespace App\Providers;

use App\Application\Image\Services\ImageService;
use App\Domain\Image\Contracts\ImageServiceInterface;
use App\Domain\Image\Contracts\ImageStorageInterface;
use App\Infrastructure\Image\Drivers\LocalImageStorage;
use App\Infrastructure\Image\Processors\ImageProcessor;
use Illuminate\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ImageProcessor::class, function () {
            return new ImageProcessor(['driver' => config('image.driver', 'gd')]);
        });

        $this->app->bind(ImageStorageInterface::class, LocalImageStorage::class);

        $this->app->bind(ImageServiceInterface::class, function ($app) {
            return new ImageService(
                $app->make(ImageProcessor::class),
                $app->make(ImageStorageInterface::class),
                config('image', [])
            );
        });
    }
}
