@extends('layouts.app')

@section('title', 'Inspections')

@section('content')
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
    <div>
        <h1 class="mb-1">Inspections</h1>
        <p class="text-muted mb-0">Browse inspection reports, view details, and manage approval status.</p>
    </div>
    <a href="{{ route('inspections.create') }}" class="btn btn-primary btn-lg">
        <i class="bi bi-plus-circle me-2"></i>New Inspection
    </a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row gx-3 gy-3 align-items-end">
            <div class="col-12 col-md-4">
                <label for="status" class="form-label">Filter by status</label>
                <select id="status" name="status" class="form-select">
                    <option value="">All statuses</option>
                    <option value="Submitted" {{ request('status') === 'Submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="Approved" {{ request('status') === 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="Draft" {{ request('status') === 'Draft' ? 'selected' : '' }}>Draft</option>
                    <option value="In Progress" {{ request('status') === 'In Progress' ? 'selected' : '' }}>In Progress</option>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label opacity-0">Apply</label>
                <button type="submit" class="btn btn-outline-primary w-100">Apply filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        @if($inspections->count())
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Equipment</th>
                        <th>Inspector</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inspections as $inspection)
                        @php
                            $statusClass = match($inspection->status) {
                                'Approved' => 'success',
                                'Rejected' => 'danger',
                                'Submitted' => 'warning',
                                'In Progress' => 'info',
                                'Draft' => 'secondary',
                                default => 'secondary',
                            };
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $inspection->equipment->equipment_name ?? 'N/A' }}</strong><br>
                                <small class="text-muted">{{ $inspection->equipment->unit_number ?? '-' }}</small>
                            </td>
                            <td>{{ $inspection->inspector->name ?? 'N/A' }}</td>
                            <td>{{ $inspection->inspection_type }}</td>
                            <td><span class="badge bg-{{ $statusClass }} text-uppercase">{{ $inspection->status ?? 'Unknown' }}</span></td>
                            <td>{{ optional($inspection->survey_timestamp)->format('Y-m-d H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('inspections.show', $inspection->id) }}" class="btn btn-sm btn-outline-primary me-2">View</a>
                                @if($inspection->status === 'Submitted')
                                    <form action="{{ route('inspections.approve', $inspection->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <form action="{{ route('inspections.reject', $inspection->id) }}" method="POST" class="d-inline ms-1">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="p-4 text-center">
                <h5 class="mb-2">No inspections found</h5>
                <p class="text-muted mb-3">Create a new inspection to start tracking equipment condition and findings.</p>
                <a href="{{ route('inspections.create') }}" class="btn btn-primary">Create Inspection</a>
            </div>
        @endif
    </div>
</div>

<div class="mt-3">
    {{ $inspections->withQueryString()->links() }}
</div>
@endsection
