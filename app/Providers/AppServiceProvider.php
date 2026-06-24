<?php

namespace App\Providers;

use App\Domain\Repositories\EquipmentRepositoryInterface;
use App\Domain\Repositories\CompanyRepositoryInterface;
use App\Domain\Repositories\ImportHistoryRepositoryInterface;
use App\Domain\Repositories\IndustryRepositoryInterface;
use App\Domain\Repositories\InspectionApprovalRepositoryInterface;
use App\Domain\Repositories\InspectionFindingRepositoryInterface;
use App\Domain\Repositories\InspectionPhotoRepositoryInterface;
use App\Domain\Repositories\InspectionRepositoryInterface;
use App\Domain\Repositories\RoleRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Repositories\CompanyRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EquipmentRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\ImportHistoryRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\IndustryRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\InspectionApprovalRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\InspectionFindingRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\InspectionPhotoRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\InspectionRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\RoleRepository;
use App\Application\Services\MenuService;
use App\Application\Services\ThemeService;
use App\Infrastructure\Persistence\Eloquent\Repositories\UserRepository;
use App\Models\Inspection;
use App\Providers\ImageServiceProvider;
use App\Policies\InspectionPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(EquipmentRepositoryInterface::class, EquipmentRepository::class);
        $this->app->bind(InspectionRepositoryInterface::class, InspectionRepository::class);
        $this->app->bind(InspectionFindingRepositoryInterface::class, InspectionFindingRepository::class);
        $this->app->bind(InspectionPhotoRepositoryInterface::class, InspectionPhotoRepository::class);
        $this->app->bind(InspectionApprovalRepositoryInterface::class, InspectionApprovalRepository::class);
        $this->app->bind(ImportHistoryRepositoryInterface::class, ImportHistoryRepository::class);
        $this->app->bind(IndustryRepositoryInterface::class, IndustryRepository::class);
        $this->app->bind(CompanyRepositoryInterface::class, CompanyRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        $this->app->singleton(ThemeService::class, fn () => new ThemeService(config('themes', [])));
        $this->app->singleton(MenuService::class, fn () => new MenuService());

        $this->app->register(ImageServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Inspection::class, InspectionPolicy::class);

        Gate::define('manage-users', fn ($user) => in_array($user->role->name, ['Super Admin', 'Admin'], true));
        Gate::define('import-equipments', fn ($user) => in_array($user->role->name, ['Super Admin', 'Admin'], true));
        Gate::define('review-inspections', fn ($user) => in_array($user->role->name, ['Super Admin', 'Supervisor'], true));

        View::composer('*', function ($view) {
            $themeService = app(ThemeService::class);
            $menuService = app(MenuService::class);

            $theme = $themeService->currentTheme();
            // Ensure the user's role relation is loaded so MenuService can detect role name reliably
            $user = auth()->user();
            if ($user) {
                $user->loadMissing('role');
            }
            $menus = $menuService->buildMenu($user);

            $view->with(compact('theme', 'menus'));
        });
    }
}
