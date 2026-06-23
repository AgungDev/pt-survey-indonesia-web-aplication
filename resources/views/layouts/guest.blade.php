<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Survey Management') }} | @yield('title', 'Login')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @if(file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css'])
        @if(request()->routeIs('login'))
            @vite(['resources/css/login.css'])
        @endif
    @else
        <!-- Vite manifest not found; falling back to CDN/local CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/4.0.0/css/adminlte.min.css" />
        @if(request()->routeIs('login'))
            <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
        @endif
    @endif
    @stack('css')
    @stack('styles')
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="{{ route('login') }}">
            <img src="{{ asset('images/logo.png') }}" alt="PT Surveyor Indonesia logo" class="login-logo-image">
            <div class="login-logo-text">
                PT Surveyor Indonesia
                <span>Survey Management System</span>
            </div>
        </a>
    </div>
    <div class="card card-outline card-primary login-card">
        <div class="card-header text-center">
            <h1 class="h4 fw-bold">Masuk Akun</h1>
            <p class="text-muted mb-0">Kelola aktivitas survei Anda dengan aman</p>
        </div>
        <div class="card-body">
            @yield('content')
        </div>
    </div>
</div>

@if(file_exists(public_path('build/manifest.json')))
    @vite(['resources/js/app.js'])
    @if(request()->routeIs('login'))
        @vite(['resources/js/login.js'])
    @endif
@else
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/4.0.0/js/adminlte.min.js"></script>
@endif
@stack('js')
@stack('scripts')
</body>
</html>
