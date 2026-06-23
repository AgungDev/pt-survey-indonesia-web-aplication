<?php

namespace App\Infrastructure\Image\Drivers;

use App\Domain\Image\Contracts\ImageStorageInterface;
use Illuminate\Support\Facades\Storage;

class MinioImageStorage implements ImageStorageInterface
{
    protected string $disk = 'minio';

    public function put(string $path, string $contents, string $visibility = 'public'): string
    {
        Storage::disk($this->disk)->put($path, $contents, $visibility);

        return $path;
    }

    public function delete(string $path): bool
    {
        return Storage::disk($this->disk)->delete($path);
    }

    public function exists(string $path): bool
    {
        return Storage::disk($this->disk)->exists($path);
    }

    public function url(string $path): string
    {
        return Storage::disk($this->disk)->url($path);
    }

    public function path(string $path): string
    {
        $disk = Storage::disk($this->disk);

        if (method_exists($disk, 'path')) {
            return $disk->path($path);
        }

        return $path;
    }
}
