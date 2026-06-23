@extends('layouts.app')

@section('title', 'Dashboard Supervisor')
@section('page-title', 'Dashboard Supervisor')

@section('content')
    <div class="row">
        <x-dashboard.stat-card title="Total Inspections" value="{{ $summary['total_inspections'] }}" icon="bi bi-search" color="warning" />
        <x-dashboard.stat-card title="Pending Approvals" value="{{ $summary['pending_approvals'] }}" icon="bi bi-clock" color="success" />
        <x-dashboard.stat-card title="Equipments" value="{{ $summary['total_equipment'] }}" icon="bi bi-box-seam" color="primary" />
        <x-dashboard.stat-card title="Findings" value="{{ $summary['total_findings'] }}" icon="bi bi-exclamation-triangle" color="info" />
    </div>
@endsection
