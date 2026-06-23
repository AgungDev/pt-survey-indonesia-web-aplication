Saya ingin menambahkan modul **Image Service** ke dalam project Laravel yang menggunakan Clean Architecture.

Tujuan utama service ini adalah menjadi satu pintu untuk seluruh proses pengolahan gambar di aplikasi.

Service harus reusable, modular, scalable, dan dapat digunakan oleh module lain seperti:

* Equipment Module
* Inspection Module
* Findings Module
* User Profile Module
* Future Document Module

Jangan menulis logic image processing di Controller.

Semua proses harus melalui Service Layer.

---

# Teknologi

* Laravel
* PHP 8.4
* Intervention Image v3
* PostgreSQL
* Redis
* Queue
* Docker

---

# Clean Architecture

Struktur:

app/

```
Domain/

    Image/

        Entities/
        ValueObjects/
        Contracts/

Application/

    Image/

        DTOs/
        UseCases/
        Services/

Infrastructure/

    Image/

        Processors/
        Drivers/

Presentation/

    Http/
```

Controller

↓

UseCase

↓

ImageServiceInterface

↓

ImageProcessor

↓

Storage

---

# Tujuan Service

Image Service harus mampu:

1. Compress Image
2. Resize Image
3. Convert Format
4. Generate Thumbnail
5. Generate Multiple Sizes
6. Read Metadata
7. Detect Duplicate Image
8. Optimize Storage
9. Generate Temporary URL
10. Delete Image
11. Replace Image

---

# Supported Input

* jpg
* jpeg
* png
* webp
* bmp
* gif

Future Ready:

* avif
* heic

---

# Supported Output

* jpg
* png
* webp

Default output:

webp

---

# Service Contract

Buat interface:

```php
ImageServiceInterface
```

Method:

```php
compress()
```

```php
resize()
```

```php
convert()
```

```php
thumbnail()
```

```php
optimize()
```

```php
delete()
```

```php
replace()
```

```php
getMetadata()
```

```php
calculateChecksum()
```

```php
generateUrl()
```

---

# Compress Feature

Input:

```text
IMG_001.jpg
8MB
```

Output:

```text
IMG_001.webp
250KB
```

Target:

70%-95% size reduction.

Method:

```php
compress(
    string $path,
    int $quality = 80
)
```

---

# Convert Feature

Support:

jpg → webp

png → webp

webp → jpg

webp → png

jpg → png

png → jpg

Method:

```php
convert(
    string $path,
    string $targetFormat
)
```

---

# Resize Feature

Method:

```php
resize(
    string $path,
    int $width,
    int $height
)
```

Preserve aspect ratio.

Jangan merusak kualitas gambar.

---

# Thumbnail Feature

Method:

```php
generateThumbnail(
    string $path
)
```

Default:

400 x 300

Format:

webp

Quality:

70

---

# Multi Size Generation

Buat method:

```php
generateResponsiveImages()
```

Output:

```text
thumbnail
small
medium
large
original
```

Contoh:

```text
400x300
800x600
1200x900
1600x1200
original
```

---

# Metadata Feature

Method:

```php
getMetadata()
```

Output:

```php
[
    'width' => 1600,
    'height' => 1200,
    'size' => 245760,
    'mime_type' => 'image/webp',
    'extension' => 'webp'
]
```

---

# Duplicate Detection

Method:

```php
calculateChecksum()
```

Gunakan:

SHA256

Jika checksum sama:

Jangan upload ulang file.

Kembalikan file existing.

---

# Optimize Upload Workflow

Flow:

Upload

↓

Temporary Storage

↓

Validate

↓

Queue

↓

Compress

↓

Convert To WebP

↓

Generate Thumbnail

↓

Generate Responsive Images

↓

Store Final File

↓

Save Metadata

---

# Queue Integration

Buat Job:

```php
ProcessImageJob
```

Tugas:

* Compress
* Convert
* Resize
* Generate Thumbnail
* Save Metadata

Gunakan Redis Queue.

---

# Storage Abstraction

Buat interface:

```php
ImageStorageInterface
```

Implementasi:

```php
LocalImageStorage
```

```php
MinioImageStorage
```

```php
S3ImageStorage
```

Gunakan Dependency Injection.

Jangan hardcode provider.

---

# Folder Structure

uploads/

```
originals/

optimized/

thumbnails/

responsive/

    small/

    medium/

    large/
```

Gunakan UUID filename.

Contoh:

```text
9f3a7b0d.webp
```

Jangan gunakan nama file asli.

---

# DTO

Buat DTO:

```php
ImageUploadDTO
```

```php
ImageMetadataDTO
```

```php
ImageProcessDTO
```

---

# Exception Handling

Buat custom exception:

```php
InvalidImageException
```

```php
UnsupportedFormatException
```

```php
ImageProcessingException
```

---

# Config

Buat:

config/image.php

Contoh:

```php
return [

    'default_format' => 'webp',

    'quality' => 80,

    'thumbnail_quality' => 70,

    'thumbnail_width' => 400,

    'thumbnail_height' => 300,

];
```

---

# Reusable API

Contoh penggunaan:

```php
$image = $this->imageService->optimize(
    $uploadedFile
);
```

atau

```php
$image = $this->imageService->convert(
    $path,
    'webp'
);
```

atau

```php
$image = $this->imageService->generateThumbnail(
    $path
);
```

Semua module harus cukup memanggil service tanpa mengetahui implementasi internal.

---

# Yang Harus Dibuat

1. ImageServiceInterface
2. ImageService
3. ImageStorageInterface
4. LocalImageStorage
5. MinioImageStorage
6. S3ImageStorage
7. ImageUploadDTO
8. ImageMetadataDTO
9. ImageProcessDTO
10. ProcessImageJob
11. Custom Exceptions
12. Config Image
13. Unit Test
14. Feature Test
15. Service Provider Binding

Gunakan SOLID Principle.

Gunakan Dependency Injection.

Gunakan Clean Architecture.

Pastikan service siap digunakan untuk jutaan file dan dapat dipakai ulang oleh seluruh module dalam project.
