@extends('layouts.app')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12">
        <h2 class="mb-0">Dashboard Supervisor</h2>
        <small class="text-muted">Review and approve inspections, view reports</small>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-danger">
            <div class="card-body">
                <h5 class="card-title">Pending Approvals</h5>
                <p class="display-6 text-danger">{{ $summary['pending_approvals'] }}</p>
                <a href="{{ route('inspections.index', ['status' => 'Submitted']) }}" class="btn btn-sm btn-danger mt-2 w-100">
                    Review Now
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-success">
            <div class="card-body">
                <h5 class="card-title">Total Inspections</h5>
                <p class="display-6">{{ $summary['total_inspections'] }}</p>
                <small class="text-muted">All completed inspections</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <div class="col-md-6">
        <div class="card shadow-sm border-warning">
            <div class="card-body">
                <h5 class="card-title">Inspections Today</h5>
                <p class="display-6">{{ $summary['inspections_today'] }}</p>
                <small class="text-muted">New submissions today</small>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-info">
            <div class="card-body">
                <h5 class="card-title">Total Findings</h5>
                <p class="display-6">{{ $summary['total_findings'] }}</p>
                <small class="text-muted">Across all inspections</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Approval Workflow</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-3" role="alert">
                    <strong>Your Responsibilities:</strong> Review inspection results, verify findings, and approve or reject submissions.
                </div>
                <a href="{{ route('inspections.index', ['status' => 'Submitted']) }}" class="btn btn-primary w-100">
                    <i class="bi bi-check-circle"></i> View Pending Approvals
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
