@extends('layouts.app')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12">
        <h2 class="mb-0">Dashboard Admin</h2>
        <small class="text-muted">Import data, manage master data, and view reports</small>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-primary">
            <div class="card-body">
                <h5 class="card-title">Total Equipment</h5>
                <p class="display-6">{{ $summary['total_equipment'] }}</p>
                <small class="text-muted">Master data items</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-success">
            <div class="card-body">
                <h5 class="card-title">Total Inspections</h5>
                <p class="display-6">{{ $summary['total_inspections'] }}</p>
                <small class="text-muted">All inspections</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-warning">
            <div class="card-body">
                <h5 class="card-title">Recent Imports</h5>
                <div class="list-group list-group-sm">
                    @foreach($summary['recent_imports'] as $history)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <strong class="mb-1">{{ $history->filename }}</strong>
                                <small>{{ $history->status }}</small>
                            </div>
                            <small>{{ $history->success_rows }}/{{ $history->total_rows }} rows • {{ $history->created_at->format('M d, Y') }}</small>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('imports.index') }}" class="btn btn-sm btn-warning mt-2 w-100">View All</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-info">
            <div class="card-body">
                <h5 class="card-title">Quick Stats</h5>
                <ul class="list-unstyled">
                    <li><strong>Inspections Today:</strong> {{ $summary['inspections_today'] }}</li>
                    <li><strong>Total Findings:</strong> {{ $summary['total_findings'] }}</li>
                    <li><strong>Pending Reviews:</strong> {{ $summary['pending_approvals'] }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Data Management</h5>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <a href="{{ route('imports.index') }}" class="btn btn-primary w-100">
                            <i class="bi bi-upload"></i> Upload Equipment Import
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('inspections.index') }}" class="btn btn-outline-primary w-100">
                            <i class="bi bi-eye"></i> View All Inspections
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
