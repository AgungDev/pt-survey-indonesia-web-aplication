<?php

namespace App\Providers;

use App\Domain\Repositories\EquipmentRepositoryInterface;
use App\Domain\Repositories\ImportHistoryRepositoryInterface;
use App\Domain\Repositories\InspectionFindingRepositoryInterface;
use App\Domain\Repositories\InspectionPhotoRepositoryInterface;
use App\Domain\Repositories\InspectionRepositoryInterface;
use App\Domain\Repositories\RoleRepositoryInterface;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Repositories\EquipmentRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\ImportHistoryRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\InspectionFindingRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\InspectionPhotoRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\InspectionRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\RoleRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\UserRepository;
use App\Models\Inspection;
use App\Policies\InspectionPolicy;
use Illuminate\Support\Facades\Gate;
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
        $this->app->bind(ImportHistoryRepositoryInterface::class, ImportHistoryRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
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
    }
}
