<?php

namespace App\Application\Services;

use Illuminate\Support\Str;

class ThemeService
{
    public function __construct(private array $themes = [])
    {
    }

    public function currentTheme(): array
    {
        $user = auth()->user();
        if ($user) {
            $user->loadMissing('role');
        }
        return $this->getTheme(optional($user)->role?->name);
    }

    public function getTheme(?string $role = null): array
    {
        $key = $this->normalizeRole($role);

        return $this->themes[$key] ?? $this->themes['default'] ?? [
            'body_class' => '',
            'sidebar_class' => 'sidebar-dark-primary',
            'navbar_class' => 'navbar-primary',
        ];
    }

    private function normalizeRole(?string $role): string
    {
        return Str::of($role ?? 'default')
            ->trim()
            ->lower()
            ->replace(' ', '_')
            ->__toString();
    }
}
