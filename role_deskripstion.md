Ini salah satu hal paling penting dalam pengembangan aplikasi.

Jawaban singkatnya:

**Jangan pernah mengubah migration lama yang sudah dijalankan di production.**
Buat migration baru yang berisi perubahan (alter table).

---

### Contoh Salah

Misalnya migration awal:

```php
Schema::create('equipments', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('equipment_name');
});
```

Sudah dijalankan di server dan berisi 10.000 data.

Lalu kamu edit migration lama menjadi:

```php
Schema::create('equipments', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('equipment_name');
    $table->string('manufacturer');
});
```

Ini aman di lokal kalau database masih kosong.

Tapi di production tidak akan berpengaruh karena migration tersebut sudah pernah dijalankan.

---

### Cara Yang Benar

Buat migration baru:

```bash
php artisan make:migration add_manufacturer_to_equipments_table
```

Isi:

```php
return new class extends Migration {
    public function up(): void
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->string('manufacturer')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->dropColumn('manufacturer');
        });
    }
};
```

Lalu:

```bash
php artisan migrate
```

Laravel hanya menjalankan migration baru.

Data lama tetap aman.

---

## Menambah Tabel Baru

Misalnya mau menambah checklist inspeksi.

Buat migration:

```bash
php artisan make:migration create_inspection_checklists_table
```

Jangan sentuh migration lama.

---

## Mengubah Nama Kolom

Misalnya:

```text
equipment_name
```

menjadi

```text
unit_name
```

Migration:

```php
Schema::table('equipments', function (Blueprint $table) {
    $table->renameColumn(
        'equipment_name',
        'unit_name'
    );
});
```

Data tetap ada.

---

## Menghapus Kolom

Hati-hati.

```php
Schema::table('equipments', function (Blueprint $table) {
    $table->dropColumn('capacity');
});
```

Semua data pada kolom tersebut hilang permanen.

Biasanya saya lebih suka:

```php
$table->boolean('is_active');
```

atau

```php
$table->softDeletes();
```

daripada menghapus data.

---

## Untuk Sistem Industri

Saya sarankan dari awal tambahkan:

```php
$table->timestamps();
$table->softDeletes();
```

di hampir semua tabel.

Contoh:

```php
Schema::create('equipments', function (Blueprint $table) {
    $table->uuid('id')->primary();

    $table->string('equipment_name');

    $table->timestamps();

    $table->softDeletes();
});
```

Kalau user menghapus alat:

```php
$equipment->delete();
```

Data tidak benar-benar hilang.

Hanya mengisi:

```sql
deleted_at
```

---

## Jika Struktur Sangat Berubah

Misalnya client minta:

```text
Version 1
equipment_name

Version 2
asset_name
```

Jangan langsung hapus kolom lama.

Lakukan bertahap:

### Step 1

Tambah kolom baru

```php
$table->string('asset_name')->nullable();
```

### Step 2

Migrasikan data

```php
DB::table('equipments')
    ->update([
        'asset_name' => DB::raw('equipment_name')
    ]);
```

### Step 3

Update source code

Gunakan:

```php
asset_name
```

### Step 4

Beberapa bulan kemudian baru hapus kolom lama.

---

### Di proyekmu nanti

Karena ada:

* Equipment Import
* Survey
* Findings
* Photos

Saya sarankan aturan tim:

✅ Migration lama = jangan diubah

✅ Selalu buat migration baru

✅ Gunakan Soft Delete

✅ Backup database sebelum deploy

✅ Untuk perubahan besar gunakan migration data (data migration) sebelum menghapus kolom

Dengan pola ini, database production yang sudah berisi ribuan inspeksi dan foto bisa terus berkembang tanpa kehilangan data lama.
