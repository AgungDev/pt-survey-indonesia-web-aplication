Saya ingin membuat aplikasi Survey & Inspection Management untuk industri menggunakan Laravel.

## Tech Stack

* Laravel 12
* PHP 8.4
* PostgreSQL
* Docker & Docker Compose
* Bootstrap 5
* Clean Architecture
* Repository Pattern
* Service Layer
* UUID sebagai Primary Key
* Laravel Authentication
* Laravel Policy & Authorization
* Queue Ready
* Storage untuk upload foto
* Import Excel menggunakan Laravel Excel (maatwebsite/excel)

---

# Tujuan Sistem

Aplikasi digunakan untuk:

1. Import master data peralatan dari file Excel.
2. Melakukan survey/inspeksi peralatan di lapangan.
3. Menyimpan foto unit dan foto temuan.
4. Menyimpan hasil pemeriksaan.
5. Menghasilkan laporan inspeksi.
6. Mendukung ribuan data peralatan dan inspeksi.

Fokus versi awal (MVP):

* User Management
* Role Management
* Import Excel
* Survey/Inspection
* Upload Foto
* Dashboard

---

# Arsitektur

Gunakan Clean Architecture.

Struktur folder:

app/

```
Domain/
    Entities/
    Repositories/
    ValueObjects/

Application/
    UseCases/
    DTOs/
    Services/

Infrastructure/
    Persistence/
        Eloquent/
        Repositories/
    Storage/
    Excel/

Presentation/
    Http/
        Controllers/
        Requests/
        Resources/
```

Buat dependency inversion yang jelas.

Controller tidak boleh mengakses model langsung.

Controller -> UseCase -> Repository Interface -> Repository Implementation

---

# Docker

Buat docker compose lengkap:

services:

* nginx
* app (php-fpm)
* postgres
* redis
* pgadmin

Tambahkan:

* healthcheck
* volume persistence
* environment configuration

Buat Dockerfile production-ready.

---

# Authentication & Authorization

Gunakan Laravel Authentication.

Buat multi role:

1. Super Admin
2. Admin
3. Supervisor
4. Inspector

Hak akses:

Super Admin:

* kelola semua user
* kelola role
* import data
* lihat seluruh laporan

Admin:

* import data
* kelola master data
* lihat laporan

Supervisor:

* review inspeksi
* approve inspeksi
* lihat laporan

Inspector:

* input inspeksi
* upload foto
* edit inspeksi miliknya

Gunakan:

* Policy
* Middleware
* Gates

---

# Database Design

Gunakan PostgreSQL.

Semua tabel menggunakan UUID.

## users

* id
* name
* email
* password
* role_id
* created_at
* updated_at

## roles

* id
* name
* description

## equipments

Master data hasil import excel.

Field:

* id
* equipment_name
* equipment_category
* location
* unit_number
* serial_number
* model_type
* brand
* capacity
* created_at
* updated_at

Tambahkan index yang diperlukan.

---

## inspections

Field:

* id
* survey_timestamp
* equipment_id
* inspector_id
* inspection_type
* inspection_result
* recommendation
* unit_photo
* status

Status:

* Draft
* Submitted
* Approved
* Rejected

Tambahkan audit fields.

---

## inspection_findings

Field:

* id
* inspection_id
* finding_number
* finding_description

---

## inspection_photos

Field:

* id
* inspection_id
* finding_id nullable
* photo_url
* photo_type

photo_type:

* UNIT
* FINDING

---

# Import Excel

Gunakan Laravel Excel.

Buat fitur:

## Upload File

Format:

xlsx
xls
csv

## Validation

* duplicate serial number
* duplicate unit number
* required fields
* invalid format

## Processing

Gunakan Queue.

Import besar tidak boleh timeout.

Buat:

ImportEquipmentJob

## Import History

Tabel:

import_histories

Field:

* id
* filename
* total_rows
* success_rows
* failed_rows
* status
* started_at
* finished_at
* created_by

---

# Dashboard

Tampilkan:

* total equipment
* total inspections
* total findings
* inspections today
* pending approvals
* recent imports

Gunakan Bootstrap Card.

---

# Survey Module

Flow:

1. User pilih equipment.
2. Isi hasil inspeksi.
3. Tambah banyak temuan.
4. Upload foto unit.
5. Upload banyak foto temuan.
6. Submit inspeksi.

Gunakan dynamic form.

Temuan tidak dibatasi jumlahnya.

---

# Scalability

Rancang sistem agar siap berkembang.

Persiapkan:

* Repository Pattern
* Queue
* Event Driven Design
* Domain Service
* Caching dengan Redis
* API Ready
* Audit Logging
* Soft Delete
* Pagination
* Search & Filter

Namun implementasi awal fokus pada:

* Import Equipment
* Inspection Management
* Authentication
* Dashboard

---

# Code Quality

Wajib:

* SOLID Principle
* Clean Code
* Type Hinting
* DTO
* Form Request Validation
* Service Provider Binding
* Unit Test
* Feature Test

Jangan gunakan business logic di Controller.

Buatkan:

* Migration
* Model
* Repository Interface
* Repository Implementation
* DTO
* Use Case
* Service
* Controller
* Request Validation
* Policy
* Seeder
* Docker Configuration

Berikan source code lengkap dan production-ready.
