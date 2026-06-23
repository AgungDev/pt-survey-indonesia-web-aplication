@extends('layouts.app')

@section('title', 'Dashboard Inspector')
@section('page-title', 'Dashboard Inspector')

@section('content')
    <div class="row">
        <x-dashboard.stat-card title="My Inspections" value="{{ $summary['total_inspections'] }}" icon="bi bi-search" color="success" />
        <x-dashboard.stat-card title="Total Equipment" value="{{ $summary['total_equipment'] }}" icon="bi bi-box-seam" color="primary" />
        <x-dashboard.stat-card title="Findings" value="{{ $summary['total_findings'] }}" icon="bi bi-exclamation-triangle" color="warning" />
        <x-dashboard.stat-card title="Today Inspections" value="{{ $summary['inspections_today'] }}" icon="bi bi-calendar-day" color="info" />
    </div>
@endsection
