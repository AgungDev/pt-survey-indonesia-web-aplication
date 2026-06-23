<?php

namespace App\Jobs;

use App\Domain\Image\Contracts\ImageServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly string $sourcePath,
        public readonly ?string $targetDirectory = null,
    ) {
    }

    public function handle(ImageServiceInterface $imageService): void
    {
        $imageService->optimize($this->sourcePath, $this->targetDirectory);
    }
}
