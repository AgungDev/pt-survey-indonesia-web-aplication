# Inspection Evidence Management

Tambahkan fitur dokumentasi inspeksi.

Setiap inspeksi harus dapat memiliki:

* Foto Unit
* Temuan
* Foto Temuan

---

# Equipment Photo

Equipment dapat memiliki banyak foto.

Contoh:

APAR Unit 01

Foto:

* Tampak Depan
* Tampak Samping
* Label Sertifikasi
* Kondisi Aktual

Table:

equipment_photos

* id
* equipment_id
* file_id
* description
* created_at

Relasi:

Equipment

1:N

Equipment Photos

---

# Findings

Temuan tidak boleh hanya berupa text singkat.

Setiap temuan wajib memiliki:

* Judul Temuan
* Deskripsi Temuan
* Severity
* Status

Contoh:

Judul:

Selang APAR Retak

Deskripsi:

Ditemukan keretakan pada selang APAR bagian ujung sepanjang kurang lebih 5 cm yang berpotensi menyebabkan kebocoran saat digunakan.

Severity:

High

Status:

Open

---

# Findings Table

findings

* id

* inspection_id

* title

* description

* severity

severity:

Low
Medium
High
Critical

* status

status:

Open
Monitoring
Closed

* created_by

* created_at

* updated_at

---

# Finding Photos

Setiap temuan dapat memiliki banyak foto.

Relasi:

Finding

1:N

Finding Photos

Contoh:

Temuan:
Selang APAR Retak

Foto:

* Close Up Retakan
* Posisi Selang
* Foto Keseluruhan Unit

---

# Finding Photos Table

finding_photos

* id

* finding_id

* file_id

* description

* created_at

---

# Multi Image Support

Saat Inspector membuat inspeksi:

Inspection

↓

Equipment

↓

Tambah Temuan

↓

Tambah Foto Temuan (Multiple)

↓

Tambah Temuan Berikutnya

↓

Tambah Foto Temuan Berikutnya

↓

Submit

Tidak ada batas jumlah foto.

Tidak ada batas jumlah temuan.

---

# Photo Description

Setiap foto harus dapat memiliki deskripsi.

Contoh:

description:

"Retakan terlihat pada bagian sambungan selang"

atau

"Label APAR sudah tidak terbaca"

---

# Inspection Submission Validation

Minimal:

* 1 Foto Unit
* 1 Temuan ATAU hasil inspeksi tanpa temuan
* Jika ada Temuan:

  * Judul wajib
  * Deskripsi wajib
  * Minimal 1 Foto Temuan

Inspector tidak dapat submit jika validasi gagal.

---

# Gallery View

Supervisor dan Admin harus dapat melihat:

Inspection

↓

Equipment Photo Gallery

↓

Finding List

↓

Finding Photo Gallery

Gunakan:

Thumbnail

Lightbox

Zoom

Download

Lazy Loading

---

# Dashboard Analytics

Tambahkan statistik:

* Total Temuan
* Open Findings
* Critical Findings
* Findings by Category
* Findings by Area
* Findings by Company

---

# Future Ready

Struktur harus memungkinkan:

* Video Temuan
* PDF Sertifikat
* Dokumen Pendukung

Tanpa mengubah business logic.

Gunakan File Management Module yang sudah ada sebagai media storage abstraction.
