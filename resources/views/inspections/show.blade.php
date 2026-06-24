@extends('layouts.app')

@section('title', 'Inspection Details')

@section('content')
@php
    $userRole = auth()->user()->role?->name;
    $statusClass = match($inspection->status) {
        'Approved' => 'success',
        'Rejected' => 'danger',
        'Submitted' => 'warning',
        'In Progress' => 'info',
        'Draft' => 'secondary',
        default => 'secondary',
    };
@endphp

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="mb-1">Inspection #{{ $inspection->id }}</h1>
        <p class="text-muted mb-0">Review inspection details, findings and evidence photos.</p>
    </div>
    <a href="{{ route('inspections.index') }}" class="btn btn-outline-secondary">Back to list</a>
</div>

<div class="row g-4">
    <div class="col-12 col-xl-8">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-4">
                    <div>
                        <h5 class="card-title">Inspection Overview</h5>
                        <p class="text-muted mb-0">Equipment, inspector and status information.</p>
                    </div>
                    <span class="badge bg-{{ $statusClass }} text-uppercase py-2 px-3">{{ $inspection->status ?? 'Unknown' }}</span>
                </div>

                <div class="row gy-3">
                    <div class="col-12 col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="mb-2">Equipment</h6>
                            <p class="mb-1"><strong>{{ $inspection->equipment->equipment_name ?? 'N/A' }}</strong></p>
                            <p class="text-muted mb-0">Unit: {{ $inspection->equipment->unit_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="mb-2">Inspector</h6>
                            <p class="mb-1">{{ $inspection->inspector->name ?? 'N/A' }}</p>
                            <p class="text-muted mb-0">{{ optional($inspection->survey_timestamp)->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="border rounded-3 p-3">
                            <h6 class="mb-2">Inspection type</h6>
                            <p class="mb-0">{{ $inspection->inspection_type }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3">Result and recommendation</h5>
                <div class="row gy-3">
                    <div class="col-12 col-lg-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="mb-2">Inspection result</h6>
                            <p class="mb-0">{{ $inspection->inspection_result ?: '-' }}</p>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="mb-2">Recommendation</h6>
                            <p class="mb-0">{{ $inspection->recommendation ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($inspection->unit_photo)
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Unit photo</h5>
                    <div class="ratio ratio-16x9 rounded-3 overflow-hidden border">
                        <img src="{{ Storage::url($inspection->unit_photo) }}" class="object-fit-cover" alt="Unit photo">
                    </div>
                </div>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="card-title mb-1">Findings</h5>
                        <p class="text-muted mb-0">Review each finding and its evidence photos.</p>
                    </div>
                    <span class="badge bg-secondary">{{ $inspection->findings->count() }} found</span>
                </div>

                @forelse($inspection->findings as $finding)
                    <div class="card mb-3 shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-1">Finding #{{ $finding->finding_number }}</h6>
                                </div>
                            </div>
                            <p class="mb-3">{{ $finding->finding_description }}</p>
                            @if($finding->photos->count())
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($finding->photos as $photo)
                                        <a href="{{ Storage::url($photo->photo_url) }}" target="_blank" class="d-inline-block rounded overflow-hidden border">
                                            <img src="{{ Storage::url($photo->photo_url) }}" alt="Finding photo" style="width:140px; height:100px; object-fit:cover;">
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No evidence photos uploaded.</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="alert alert-warning">No findings have been recorded for this inspection.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-3">Quick action</h5>
                <p class="text-muted">Use this panel to approve or reject inspections when review is complete.</p>

                @if($inspection->status === 'Submitted' && in_array($userRole, ['Supervisor', 'Super Admin'], true))
                    <div class="d-grid gap-2">
                        <form action="{{ route('inspections.approve', $inspection->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">Approve inspection</button>
                        </form>
                        <form action="{{ route('inspections.reject', $inspection->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">Reject inspection</button>
                        </form>
                    </div>
                @else
                    <div class="alert alert-secondary mb-0">
                        @if($inspection->status === 'Submitted')
                            Waiting for supervisor review.
                        @else
                            This inspection cannot be approved or rejected in its current status.
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
