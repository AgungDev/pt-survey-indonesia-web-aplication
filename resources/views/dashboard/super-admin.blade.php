@extends('layouts.app')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12">
        <h2 class="mb-0">Dashboard Super Admin</h2>
        <small class="text-muted">Full access to all system capabilities</small>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-primary">
            <div class="card-body">
                <h5 class="card-title">Total Equipment</h5>
                <p class="display-6">{{ $summary['total_equipment'] }}</p>
                <a href="{{ route('inspections.index') }}" class="btn btn-sm btn-primary mt-2">View</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-success">
            <div class="card-body">
                <h5 class="card-title">Total Inspections</h5>
                <p class="display-6">{{ $summary['total_inspections'] }}</p>
                <a href="{{ route('inspections.index') }}" class="btn btn-sm btn-success mt-2">Manage</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-warning">
            <div class="card-body">
                <h5 class="card-title">Total Findings</h5>
                <p class="display-6">{{ $summary['total_findings'] }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <div class="col-md-4">
        <div class="card shadow-sm border-info">
            <div class="card-body">
                <h5 class="card-title">Inspections Today</h5>
                <p class="display-6">{{ $summary['inspections_today'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-danger">
            <div class="card-body">
                <h5 class="card-title">Pending Approvals</h5>
                <p class="display-6">{{ $summary['pending_approvals'] }}</p>
                <a href="{{ route('inspections.index', ['status' => 'Submitted']) }}" class="btn btn-sm btn-danger mt-2">Review</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-secondary">
            <div class="card-body">
                <h5 class="card-title">Recent Imports</h5>
                <div class="list-group list-group-sm">
                    @foreach($summary['recent_imports'] as $history)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <strong class="mb-1">{{ $history->filename }}</strong>
                                <small>{{ $history->status }}</small>
                            </div>
                            <small>{{ $history->success_rows }}/{{ $history->total_rows }} rows</small>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('imports.index') }}" class="btn btn-sm btn-secondary mt-2 w-100">Manage Imports</a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Admin Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-3">
                        <a href="{{ route('imports.index') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-upload"></i> Import Equipment
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('inspections.index') }}" class="btn btn-outline-success w-100">
                            <i class="bi bi-checklist"></i> Manage Inspections
                        </a>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-info w-100" data-bs-toggle="modal" data-bs-target="#userModal">
                            <i class="bi bi-people"></i> User Management
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-warning w-100" data-bs-toggle="modal" data-bs-target="#roleModal">
                            <i class="bi bi-shield"></i> Role Management
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
