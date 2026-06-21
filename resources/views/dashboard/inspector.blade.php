@extends('layouts.app')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12">
        <h2 class="mb-0">Dashboard Inspector</h2>
        <small class="text-muted">Create inspections, upload photos, and track your submissions</small>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-primary">
            <div class="card-body">
                <h5 class="card-title">My Inspections</h5>
                <p class="display-6">{{ $summary['inspections_today'] }}</p>
                <small class="text-muted">Submitted today</small>
                <div class="mt-3">
                    <a href="{{ route('inspections.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-success">
            <div class="card-body">
                <h5 class="card-title">Total Equipment</h5>
                <p class="display-6">{{ $summary['total_equipment'] }}</p>
                <small class="text-muted">Available to inspect</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Create New Inspection</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-3" role="alert">
                    <strong>Quick Start:</strong> Select an equipment and begin the inspection process. You can add multiple findings and upload photos.
                </div>
                <a href="{{ route('inspections.create') }}" class="btn btn-success btn-lg w-100">
                    <i class="bi bi-plus-circle"></i> Start New Inspection
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-3">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Inspection Status Guide</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="text-center">
                            <span class="badge bg-secondary p-2">Draft</span>
                            <p class="small mt-2">In Progress - Not yet submitted</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <span class="badge bg-warning p-2">Submitted</span>
                            <p class="small mt-2">Waiting for supervisor approval</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <span class="badge bg-success p-2">Approved</span>
                            <p class="small mt-2">Successfully approved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
