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

        // Determine which dashboard view to render based on role
        $view = match($role) {
            'Super Admin' => 'dashboard.super-admin',
            'Admin' => 'dashboard.admin',
            'Supervisor' => 'dashboard.supervisor',
            'Inspector' => 'dashboard.inspector',
            default => 'dashboard'
        };

        return View::make($view, compact('summary', 'user', 'role'));
    }
}
