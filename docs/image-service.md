# Image Service

Layanan ini menyediakan seluruh proses pengolahan gambar melalui service layer dan Clean Architecture.

## Struktur penting

- `App\Domain\Image\Contracts\ImageServiceInterface`
- `App\Application\Image\Services\ImageService`
- `App\Infrastructure\Image\Processors\ImageProcessor`
- `App\Infrastructure\Image\Drivers\LocalImageStorage`
- `App\Providers\ImageServiceProvider`
- `config/image.php`

## Install dependensi

Jalankan:

```bash
composer require intervention/image
```

## Konfigurasi

`config/image.php` sudah menyediakan nilai default:

- `default_format`: `webp`
- `quality`: `80`
- `thumbnail_quality`: `70`
- `thumbnail_width`: `400`
- `thumbnail_height`: `300`
- `base_path`: `uploads`
- `responsive_sizes`: `thumbnail`, `small`, `medium`, `large`

## Cara menggunakan

### Injeksi service di controller

```php
use App\Domain\Image\Contracts\ImageServiceInterface;

class ProfileController
{
    public function updateAvatar(ImageServiceInterface $imageService)
    {
        $image = request()->file('avatar');
        $metadata = $imageService->optimize($image);

        return response()->json($metadata->toArray());
    }
}
```

### Contoh pemanggilan metode service

```php
$imageService->compress('uploads/originals/9f3a7b0d.jpg', 75);
$imageService->resize('uploads/originals/9f3a7b0d.jpg', 800, 600);
$imageService->convert('uploads/originals/9f3a7b0d.jpg', 'webp');
$imageService->generateThumbnail('uploads/optimized/9f3a7b0d.webp');
$imageService->generateResponsiveImages('uploads/optimized/9f3a7b0d.webp');
$imageService->delete('uploads/optimized/9f3a7b0d.webp');
$imageService->replace('uploads/optimized/old.webp', 'uploads/originals/new.jpg');
$imageService->getMetadata('uploads/optimized/9f3a7b0d.webp');
$imageService->calculateChecksum('uploads/optimized/9f3a7b0d.webp');
$imageService->generateUrl('uploads/optimized/9f3a7b0d.webp');
```

## Proses antrean

`App\Jobs\ProcessImageJob` sudah tersedia untuk dipakai dengan queue Redis.

```php
ProcessImageJob::dispatch('uploads/originals/9f3a7b0d.jpg');
```

## Notes

- Semua logika pengolahan gambar berada di service layer, bukan di controller.
- `LocalImageStorage` menyimpan file di disk `public` dan menghasilkan URL via `Storage::disk('public')->url()`.
- Duplikasi gambar dideteksi dengan checksum SHA256 dan disimpan di cache.
