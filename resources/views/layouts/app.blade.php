<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Survey Management') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('dashboard.index') }}">Survey Management</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard.index') }}">Dashboard</a></li>
                    
                    @if(in_array(auth()->user()->role?->name, ['Super Admin', 'Admin']))
                        <li class="nav-item"><a class="nav-link" href="{{ route('imports.index') }}">Imports</a></li>
                    @endif
                    
                    @if(in_array(auth()->user()->role?->name, ['Super Admin', 'Admin', 'Supervisor', 'Inspector']))
                        <li class="nav-item"><a class="nav-link" href="{{ route('inspections.index') }}">Inspections</a></li>
                    @endif
                    
                    @if(auth()->user()->role?->name === 'Inspector')
                        <li class="nav-item"><a class="nav-link" href="{{ route('inspections.create') }}">New Inspection</a></li>
                    @endif
                @endauth
            </ul>
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item">
                        <span class="nav-link">
                            <small class="text-light">{{ auth()->user()->name }} ({{ auth()->user()->role?->name }})</small>
                        </span>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
<div class="container py-4">
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
