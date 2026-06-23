@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
    <div class="row">
        <x-dashboard.stat-card title="Total Equipment" value="{{ $summary['total_equipment'] }}" icon="bi bi-box-seam" color="primary" />
        <x-dashboard.stat-card title="Total Inspections" value="{{ $summary['total_inspections'] }}" icon="bi bi-search" color="info" />
        <x-dashboard.stat-card title="Findings" value="{{ $summary['total_findings'] }}" icon="bi bi-exclamation-triangle" color="warning" />
        <x-dashboard.stat-card title="Recent Imports" value="{{ count($summary['recent_imports']) }}" icon="bi bi-file-arrow-up" color="success" />
    </div>
@endsection
