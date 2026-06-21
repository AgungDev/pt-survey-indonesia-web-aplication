@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-primary">
            <div class="card-body">
                <h5 class="card-title">Total Equipment</h5>
                <p class="display-6">{{ $summary['total_equipment'] }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-success">
            <div class="card-body">
                <h5 class="card-title">Total Inspections</h5>
                <p class="display-6">{{ $summary['total_inspections'] }}</p>
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
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-secondary">
            <div class="card-body">
                <h5 class="card-title">Recent Imports</h5>
                <ul class="list-group list-group-flush">
                    @foreach($summary['recent_imports'] as $history)
                        <li class="list-group-item">
                            <strong>{{ $history->filename }}</strong><br>
                            {{ $history->status }} • {{ $history->success_rows }}/{{ $history->total_rows }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
