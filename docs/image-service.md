# Image Service

Dokumentasi singkat Image Service berdasarkan implementasi yang sudah ada.

## Lokasi penting

- `app/Domain/Image/Contracts/ImageServiceInterface.php`
- `app/Application/Image/Services/ImageService.php`
- `app/Infrastructure/Image/Processors/ImageProcessor.php`
- `app/Infrastructure/Image/Drivers/LocalImageStorage.php`
- `app/Infrastructure/Image/Drivers/MinioImageStorage.php`
- `app/Infrastructure/Image/Drivers/S3ImageStorage.php`
- `app/Application/Image/DTOs/ImageUploadDTO.php`
- `app/Application/Image/DTOs/ImageMetadataDTO.php`
- `app/Application/Image/DTOs/ImageProcessDTO.php`
- `app/Application/Image/Exceptions/InvalidImageException.php`
- `app/Application/Image/Exceptions/UnsupportedFormatException.php`
- `app/Application/Image/Exceptions/ImageProcessingException.php`
- `app/Jobs/ProcessImageJob.php`
- `app/Providers/ImageServiceProvider.php`
- `config/image.php`

## Fitur yang tersedia

- compress image ke format default (`webp`) lewat `compress()`
- resize image dengan proporsi yang terjaga lewat `resize()`
- convert format image lewat `convert()`
- generate thumbnail standar lewat `generateThumbnail()`
- generate responsive images (`thumbnail`, `small`, `medium`, `large`) lewat `generateResponsiveImages()`
- optimize upload workflow lewat `optimize()`
- delete image lewat `delete()`
- replace image lama lewat `replace()`
- baca metadata lewat `getMetadata()`
- deteksi duplikat via checksum SHA256 lewat `calculateChecksum()`
- generate public URL lewat `generateUrl()`

## Konfigurasi

`config/image.php` berisi:

- `driver`: image driver (`gd` default)
- `default_format`: `webp`
- `quality`: `80`
- `thumbnail_quality`: `70`
- `thumbnail_width`: `400`
- `thumbnail_height`: `300`
- `responsive_sizes`: ukuran responsive image
- `storage_disk`: disk Laravel untuk menyimpan file (default `public`)
- `supported_input_formats`: `jpg`, `jpeg`, `png`, `webp`, `bmp`, `gif`, `avif`, `heic`
- `supported_output_formats`: `jpg`, `png`, `webp`
- `base_path`: `uploads`

## Cara pakai

### Injeksi service di controller / use case

```php
use App\Domain\Image\Contracts\ImageServiceInterface;

class ExampleController
{
    public function store(ImageServiceInterface $imageService)
    {
        $uploaded = request()->file('image');
        $metadata = $imageService->optimize($uploaded);

        return response()->json($metadata->toArray());
    }
}
```

### Contoh penggunaan metode

```php
$metadata = $imageService->compress('uploads/originals/1234.jpg', 75);
$metadata = $imageService->resize('uploads/originals/1234.jpg', 800, 600);
$metadata = $imageService->convert('uploads/originals/1234.jpg', 'webp');
$thumbnail = $imageService->generateThumbnail('uploads/originals/1234.jpg');
$responsive = $imageService->generateResponsiveImages('uploads/originals/1234.jpg');
$imageService->delete('uploads/optimized/abcd.webp');
$metadata = $imageService->replace('uploads/optimized/old.webp', 'uploads/originals/new.jpg');
$metadata = $imageService->getMetadata('uploads/optimized/abcd.webp');
$checksum = $imageService->calculateChecksum('uploads/optimized/abcd.webp');
$url = $imageService->generateUrl('uploads/optimized/abcd.webp');
```

## Queue integration

`App\Jobs\ProcessImageJob` sudah tersedia untuk queue Redis:

```php
ProcessImageJob::dispatch('uploads/originals/1234.jpg');
```

Job ini memanggil `ImageService::optimize()` dan menjalankan compress, thumbnail, responsive image, dan penyimpanan final.

## Tempat penggunaan yang disarankan

- Controller upload image (user profile, inspection, findings, equipment)
- Use case / service layer lain yang butuh transformasi gambar
- Job queue untuk pemrosesan async
- Module image handling lintas aplikasi seperti profile, equipment, inspection, atau dokumentasi

## Cara cek fitur

- Pastikan `App\Providers\ImageServiceProvider` terdaftar di `App\Providers\AppServiceProvider`.
- Pastikan `config/image.php` sudah ada dan `intervention/image` terinstal.
- Pastikan disk `public` sudah tersedia dan `php artisan storage:link` dijalankan bila perlu.
- Jalankan tes unit/feature:
  - `php artisan test --filter ImageDTOTest`
  - `php artisan test --filter ImageServiceFeatureTest`

## Error handling

- Input format yang tidak didukung akan melempar `UnsupportedFormatException`
- Path gambar yang tidak bisa dibaca akan melempar `InvalidImageException`
- Gagal membuka atau encode image akan melempar `ImageProcessingException`
- Duplikat image akan dicek menggunakan checksum SHA256 dan service akan mengembalikan file existing bila sudah disimpan sebelumnya

## Catatan tambahan

- `ImageService` saat ini menggunakan `LocalImageStorage` sebagai default.
- Untuk menggunakan MinIO atau S3, ubah binding `ImageStorageInterface` di `app/Providers/ImageServiceProvider.php`.
- Semua logika image processing sudah berada di service layer, bukan di controller.
