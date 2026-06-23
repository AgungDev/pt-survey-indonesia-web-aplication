<?php

namespace App\Presentation\Http\Controllers;

use App\Application\Services\DashboardService;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService)
    {
    }

    public function index()
    {
        $user = auth()->user();
        $role = $user->role?->name;

        $summary = $this->dashboardService->summary();

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('dashboard.index')],
            ['label' => 'Dashboard', 'url' => null],
        ];

        // Determine which dashboard view to render based on role
        $view = match($role) {
            'Super Admin' => 'pages.dashboard.super-admin',
            'Admin' => 'pages.dashboard.admin',
            'Supervisor' => 'pages.dashboard.supervisor',
            'Inspector' => 'pages.dashboard.inspector',
            default => 'pages.dashboard.index',
        };

        return View::make($view, compact('summary', 'user', 'role', 'breadcrumbs'));
    }
}
