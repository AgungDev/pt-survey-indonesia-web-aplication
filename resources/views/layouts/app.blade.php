<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Survey Management') }} | @yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css'])
    @else
        <!-- Vite manifest not found; using CDN fallback and custom CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/4.0.0/css/adminlte.min.css" />
        <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    @endif
    @stack('css')
    @stack('styles')
    <style>
        .app-sidebar.sidebar-dark-primary {
            background-color: #0d6efd !important;
        }
        .app-sidebar.sidebar-dark-warning {
            background-color: #fd7e14 !important;
        }
        .app-sidebar.sidebar-dark-success {
            background-color: #198754 !important;
        }
        .app-sidebar.sidebar-dark-info {
            background-color: #0dcaf0 !important;
        }
        .app-sidebar.sidebar-dark-indigo {
            background-color: #343a40 !important;
        }
        .app-sidebar.sidebar-dark-primary .brand-link,
        .app-sidebar.sidebar-dark-warning .brand-link,
        .app-sidebar.sidebar-dark-success .brand-link,
        .app-sidebar.sidebar-dark-info .brand-link,
        .app-sidebar.sidebar-dark-indigo .brand-link,
        .app-sidebar.sidebar-dark-primary .nav-link,
        .app-sidebar.sidebar-dark-warning .nav-link,
        .app-sidebar.sidebar-dark-success .nav-link,
        .app-sidebar.sidebar-dark-info .nav-link,
        .app-sidebar.sidebar-dark-indigo .nav-link,
        .app-sidebar.sidebar-dark-primary .nav-icon,
        .app-sidebar.sidebar-dark-warning .nav-icon,
        .app-sidebar.sidebar-dark-success .nav-icon,
        .app-sidebar.sidebar-dark-info .nav-icon,
        .app-sidebar.sidebar-dark-indigo .nav-icon {
            color: #fff !important;
        }
        .app-sidebar.sidebar-dark-primary .nav-link.active {
            background-color: rgba(13, 110, 253, 0.75) !important;
        }
        .app-sidebar.sidebar-dark-warning .nav-link.active {
            background-color: rgba(253, 126, 20, 0.75) !important;
        }
        .app-sidebar.sidebar-dark-success .nav-link.active {
            background-color: rgba(25, 135, 84, 0.75) !important;
        }
        .app-sidebar.sidebar-dark-info .nav-link.active {
            background-color: rgba(13, 202, 240, 0.75) !important;
        }
        .app-sidebar.sidebar-dark-indigo .nav-link.active {
            background-color: rgba(79, 70, 229, 0.75) !important;
        }

        .app-sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.25) !important;
            color: #fff !important;
            font-weight: 600 !important;
            border-left: 4px solid rgba(255, 255, 255, 0.85) !important;
        }
    </style>
</head>
<body class="hold-transition layout-fixed sidebar-expand-lg bg-body-tertiary {{ $theme['body_class'] ?? '' }}">
<div class="app-wrapper">
    <x-navbar />
    <x-sidebar />

    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h1 class="mb-0">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-6">
                        @hasSection('breadcrumb')
                            @yield('breadcrumb')
                        @else
                            <x-breadcrumb :items="$breadcrumbs ?? []" />
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="app-content">
            <div class="container-fluid">
                <x-flash-message />
                @yield('content')
            </div>
        </div>
    </main>

    <x-footer />
</div>

@if(file_exists(public_path('build/manifest.json')))
    @vite(['resources/js/app.js'])
@else
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/4.0.0/js/adminlte.min.js"></script>
@endif
@stack('js')
@stack('scripts')
</body>
</html>
