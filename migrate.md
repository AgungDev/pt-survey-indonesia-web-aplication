Saya ingin membangun aplikasi Industrial Survey & Inspection Management menggunakan Laravel, PostgreSQL, Docker, Redis, Bootstrap dan AdminLTE 4.

Data awal berasal dari file Excel hasil inspeksi K3 yang berisi:

* Equipment
* Category
* Location
* Inspection Result
* Findings
* Recommendation
* Photos

Saya ingin mengubah sistem yang awalnya berbasis Google Form menjadi aplikasi enterprise yang scalable.

Buat arsitektur menggunakan Clean Architecture.

Fokus utama:

1. Equipment Management
2. Inspection Management
3. Findings Management
4. Recommendation Management
5. File Management
6. Dashboard Analytics
7. Excel Import

Buat database yang terdiri dari:

* companies
* sites
* locations
* equipment_categories
* equipments
* inspections
* findings
* recommendations
* files
* users
* roles

Gunakan UUID pada semua tabel.

Gunakan Soft Delete.

Gunakan Audit Fields.

Buat index pada seluruh foreign key dan kolom pencarian.

Implementasikan:

* Repository Pattern
* DTO
* Use Case
* Service Layer
* Policy
* Queue
* Redis Cache

Buat fitur:

* Equipment Auto Complete
* Finding Suggestion
* Recommendation Suggestion
* Duplicate Equipment Detection
* Duplicate Photo Detection menggunakan checksum
* Dashboard Analytics
* Import Excel dengan staging table
* Multi Role User
* Approval Workflow

Pastikan struktur siap menangani:

* 100.000+ Equipment
* 1.000.000+ Inspection
* 10.000.000+ File

Buat source code production-ready dengan SOLID Principle dan Clean Architecture.
