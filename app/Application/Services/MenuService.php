<?php

namespace App\Application\Services;

use App\Models\User;

class MenuService
{
    public function buildMenu(?User $user = null): array
    {
        $role = optional($user)->role?->name;
        $roleKey = $role ? strtolower(trim($role)) : null;

        $menus = [
            'super admin' => [
                ['label' => 'Dashboard', 'route' => 'dashboard.index', 'icon' => 'bi bi-speedometer2'],
                ['label' => 'Users', 'route' => 'users.index', 'icon' => 'bi bi-people'],
                ['label' => 'Roles', 'route' => 'roles.index', 'icon' => 'bi bi-shield-lock'],
                ['label' => 'Industries', 'route' => 'industries.index', 'icon' => 'bi bi-building'],
                ['label' => 'Companies', 'route' => 'companies.index', 'icon' => 'bi bi-building-up'],
                ['label' => 'Equipments', 'route' => 'equipments.index', 'icon' => 'bi bi-box-seam'],
                ['label' => 'Import Data', 'route' => 'imports.index', 'icon' => 'bi bi-file-earmark-arrow-up'],
                ['label' => 'Inspections', 'route' => 'inspections.index', 'icon' => 'bi bi-search'],
                ['label' => 'Reports', 'route' => 'reports.index', 'icon' => 'bi bi-bar-chart-line'],
                ['label' => 'Settings', 'route' => 'settings.index', 'icon' => 'bi bi-gear'],
            ],
            'admin' => [
                ['label' => 'Dashboard', 'route' => 'dashboard.index', 'icon' => 'bi bi-speedometer2'],
                ['label' => 'Industries', 'route' => 'industries.index', 'icon' => 'bi bi-building'],
                ['label' => 'Companies', 'route' => 'companies.index', 'icon' => 'bi bi-building-up'],
                ['label' => 'Equipments', 'route' => 'equipments.index', 'icon' => 'bi bi-box-seam'],
                ['label' => 'Import Data', 'route' => 'imports.index', 'icon' => 'bi bi-file-earmark-arrow-up'],
                ['label' => 'Inspections', 'route' => 'inspections.index', 'icon' => 'bi bi-search'],
                ['label' => 'Reports', 'route' => 'reports.index', 'icon' => 'bi bi-bar-chart-line'],
            ],
            'supervisor' => [
                ['label' => 'Dashboard', 'route' => 'dashboard.index', 'icon' => 'bi bi-speedometer2'],
                ['label' => 'Review Inspections', 'route' => 'inspections.index', 'icon' => 'bi bi-clipboard-check'],
                ['label' => 'Reports', 'route' => 'reports.index', 'icon' => 'bi bi-bar-chart-line'],
            ],
            'inspector' => [
                ['label' => 'Dashboard', 'route' => 'dashboard.index', 'icon' => 'bi bi-speedometer2'],
                ['label' => 'My Inspections', 'route' => 'inspections.index', 'icon' => 'bi bi-search'],
                ['label' => 'Create Inspection', 'route' => 'inspections.create', 'icon' => 'bi bi-plus-circle'],
            ],
        ];

        return $menus[$roleKey] ?? [
            ['label' => 'Dashboard', 'route' => 'dashboard.index', 'icon' => 'bi bi-speedometer2'],
        ];
    }
}
