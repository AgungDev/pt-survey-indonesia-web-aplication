@extends('layouts.app')

@section('title', 'Dashboard Super Admin')
@section('page-title', 'Dashboard Super Admin')

@section('content')
    <div class="row">
        <x-dashboard.stat-card title="Total Equipment" value="{{ $summary['total_equipment'] }}" icon="bi bi-box-seam" color="indigo" />
        <x-dashboard.stat-card title="Inspections" value="{{ $summary['total_inspections'] }}" icon="bi bi-search" color="primary" />
        <x-dashboard.stat-card title="Findings" value="{{ $summary['total_findings'] }}" icon="bi bi-exclamation-triangle" color="warning" />
        <x-dashboard.stat-card title="Pending Approvals" value="{{ $summary['pending_approvals'] }}" icon="bi bi-clock" color="success" />
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Imports</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>File</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($summary['recent_imports'] as $import)
                                <tr>
                                    <td>{{ $import->created_at }}</td>
                                    <td>{{ $import->file_name ?? 'N/A' }}</td>
                                    <td>{{ $import->status ?? 'Completed' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3">No recent import history.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
