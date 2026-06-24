Saya ingin melakukan refactor terhadap aplikasi Industrial Survey & Inspection yang sudah ada.

Saat ini sistem berfokus pada Equipment dan Inspection.

Saya ingin mengubah business flow menjadi berbasis organisasi industri.

Gunakan Clean Architecture.

Jangan membuat business logic di Controller.

Gunakan:

* Repository Pattern
* DTO
* Use Case
* Service Layer
* Policy
* Laravel
* PostgreSQL
* UUID
* Soft Delete

---

# Business Structure

Struktur organisasi baru:

Industry
↓
Company
↓
Area
↓
Equipment Category
↓
Inspection Assignment
↓
Equipment
↓
Inspection
↓
Finding
↓
File

---

# Role Responsibility

## Super Admin

Bertanggung jawab terhadap:

* User Management
* Role Management
* Permission Management
* Equipment Category Management
* System Configuration

Tidak melakukan inspeksi.

---

## Admin

Bertanggung jawab terhadap:

* Industry Management
* Company Management
* Area Management
* Equipment Monitoring
* Assignment Monitoring
* Reporting

---

## Supervisor

Bertanggung jawab terhadap:

* Membuat Assignment Inspeksi
* Menentukan Category yang harus diperiksa
* Menentukan Area yang harus diperiksa
* Menentukan Inspector
* Monitoring Progress
* Review Inspection
* Approve Inspection
* Reject Inspection

---

## Inspector

Bertanggung jawab terhadap:

* Menjalankan Assignment
* Menginput Equipment
* Menentukan Lokasi Aktual
* Mengisi Nomor Unit
* Mengisi Serial Number
* Mengisi Model / Type
* Mengisi Brand
* Mengisi Capacity
* Menginput Temuan
* Mengupload Foto
* Submit Inspection

Inspector tidak boleh:

* Membuat Industry
* Membuat Company
* Membuat Area
* Membuat Category

---

# Database Refactor

Tambahkan tabel:

industries

* id
* code
* name

companies

* id
* industry_id
* code
* name

areas

* id
* company_id
* code
* name

equipment_categories

* id
* code
* name

inspection_assignments

* id

* industry_id

* company_id

* area_id

* category_id

* inspector_id

* supervisor_id

* due_date

* status

status:

Draft
Assigned
In Progress
Completed
Approved
Rejected

---

# Equipment Refactor

Equipment sekarang harus terkait dengan:

* industry
* company
* area
* category

Jangan berdiri sendiri.

---

# Inspection Refactor

Inspection harus berasal dari assignment.

inspection

* assignment_id

* equipment_id

* inspection_date

* inspection_result

* recommendation

* status

---

# Dashboard Refactor

Super Admin

* Total User
* Total Category
* Total Inspection

Admin

* Total Industry
* Total Company
* Total Area
* Total Inspection

Supervisor

* Assignment Pending
* Assignment Progress
* Inspection Waiting Approval

Inspector

* My Assignment
* Completed Assignment
* Pending Assignment

---

# Sidebar Refactor

Super Admin

* Dashboard
* Users
* Roles
* Categories
* Settings

Admin

* Dashboard
* Industries
* Companies
* Areas
* Assignments
* Reports

Supervisor

* Dashboard
* Assignments
* Review Inspections
* Reports

Inspector

* Dashboard
* My Assignments
* My Inspections

---

# Required Output

Refactor seluruh Entity, Repository, DTO, UseCase, Service, Policy, Migration, Seeder, Dashboard, Menu Builder, dan Permission System agar mengikuti business flow baru tanpa melanggar Clean Architecture.

Pastikan perubahan kompatibel dengan modul File Management dan Image Service yang sudah ada.
