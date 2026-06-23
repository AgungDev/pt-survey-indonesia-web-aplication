Saya ingin mengintegrasikan AdminLTE 4 ke project Laravel menggunakan pendekatan modular dan Clean Architecture.

Gunakan template AdminLTE 4 seperti struktur berikut:

* Navbar di atas
* Sidebar kiri
* Breadcrumb
* Content Area
* Dashboard Widgets
* Footer
* Fullscreen Toggle
* Logout Button

Jangan gunakan package AdminLTE Laravel.

Gunakan AdminLTE 4 original.

---

# Tujuan

Membuat UI Framework internal yang nantinya menjadi fondasi seluruh module.

Semua module harus cukup mengisi content saja tanpa perlu membuat ulang layout.

Contoh:

Dashboard Module
User Module
Role Module
Equipment Module
Inspection Module
Import Module
Report Module

harus menggunakan layout yang sama.

---

# Struktur Folder

resources/views/

```
layouts/
    app.blade.php
    guest.blade.php

components/

    navbar/
        index.blade.php

    sidebar/
        index.blade.php
        menu-item.blade.php
        menu-group.blade.php

    footer/
        index.blade.php

    breadcrumb/
        index.blade.php

    widgets/
        stat-card.blade.php

pages/

    dashboard/

    users/

    roles/

    equipments/

    inspections/

    imports/

    reports/
```

---

# Layout App

Layout utama harus menyerupai template AdminLTE berikut:

<body class="layout-fixed sidebar-expand-lg">

```
Navbar

Sidebar

Main Content

Footer
```

</body>

Gunakan:

@yield('title')

@yield('breadcrumb')

@yield('content')

@stack('css')

@stack('js')

---

# Theme Management

Buat ThemeService.

Theme diambil berdasarkan role login.

Jangan hardcode class AdminLTE di blade.

Contoh:

$theme->sidebarClass

$theme->navbarClass

$theme->bodyClass

$theme->cardClass

---

# Role Theme

Super Admin

sidebar-dark-indigo
navbar-indigo

Admin

sidebar-dark-primary
navbar-primary

Supervisor

sidebar-dark-warning
navbar-warning

Inspector

sidebar-dark-success
navbar-success

Semua warna harus berasal dari config.

config/themes.php

---

# Sidebar Builder

Jangan hardcode menu.

Buat service:

MenuService

atau

SidebarBuilder

yang mengembalikan array:

[
[
'title' => 'Dashboard',
'icon' => 'bi bi-speedometer',
'route' => 'dashboard.index'
]
]

Sidebar cukup melakukan loop.

@foreach($menus as $menu)

@endforeach

---

# Navbar

Navbar harus berisi:

Sidebar Toggle

Fullscreen Button

Current Role Badge

Current User Name

Logout Button

---

# Dashboard Widgets

Buat reusable component.

Contoh:

<x-widgets.stat-card
title="Total Equipment"
value="100"
color="primary"
icon="bi bi-box-seam"
/>

Support:

primary
success
warning
danger
info

Menggunakan komponen Small Box AdminLTE 4.

---

# Breadcrumb

Buat component:

<x-breadcrumb />

Controller mengirim data breadcrumb.

Contoh:

[
'Dashboard',
'Equipment',
'Create'
]

---

# Footer

Footer otomatis menampilkan:

Nama Aplikasi

Versi Aplikasi

Current Year

Copyright

Konfigurasi berasal dari:

config/app.php

---

# Asset Management

Gunakan:

Vite

resources/css/admin.css

resources/js/admin.js

Pisahkan custom css dari AdminLTE.

Jangan menggunakan inline css.

---

# Responsive Design

Pastikan:

Desktop

Tablet

Mobile

Sidebar collapse otomatis pada layar kecil.

---

# Clean Architecture Rules

Controller tidak boleh menentukan:

Theme

Menu

Breadcrumb

Layout

Semua berasal dari Service Layer.

---

# Yang Harus Dibuat

1. ThemeService
2. MenuService
3. AppLayout
4. GuestLayout
5. Navbar Component
6. Sidebar Component
7. Footer Component
8. Breadcrumb Component
9. Dashboard Widget Component
10. Config Theme
11. Service Provider
12. Dashboard Example Page

Gunakan Bootstrap Icons seperti contoh template.

Gunakan AdminLTE 4 HTML structure yang identik dengan template referensi.

Pastikan seluruh layout reusable untuk module Equipment, Inspection, Import Excel, User Management, dan Reporting tanpa perubahan struktur utama.
