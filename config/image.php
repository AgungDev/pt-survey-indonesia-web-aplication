<?php

return [
    'driver' => env('IMAGE_DRIVER', 'gd'),
    'default_format' => env('IMAGE_DEFAULT_FORMAT', 'webp'),
    'quality' => env('IMAGE_QUALITY', 80),
    'thumbnail_quality' => env('IMAGE_THUMBNAIL_QUALITY', 70),
    'thumbnail_width' => env('IMAGE_THUMBNAIL_WIDTH', 400),
    'thumbnail_height' => env('IMAGE_THUMBNAIL_HEIGHT', 300),
    'responsive_sizes' => [
        'thumbnail' => [400, 300],
        'small' => [800, 600],
        'medium' => [1200, 900],
        'large' => [1600, 1200],
    ],
    'storage_disk' => env('IMAGE_STORAGE_DISK', 'public'),
    'supported_input_formats' => ['jpg', 'jpeg', 'png', 'webp', 'bmp', 'gif', 'avif', 'heic'],
    'supported_output_formats' => ['jpg', 'png', 'webp'],
    'base_path' => env('IMAGE_BASE_PATH', 'uploads'),
];
