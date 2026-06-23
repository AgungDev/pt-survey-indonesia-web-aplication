<?php

namespace App\Domain\Image\Contracts;

interface ImageStorageInterface
{
    public function put(string $path, string $contents, string $visibility = 'public'): string;

    public function delete(string $path): bool;

    public function exists(string $path): bool;

    public function url(string $path): string;

    public function path(string $path): string;
}
