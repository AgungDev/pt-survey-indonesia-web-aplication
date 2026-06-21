@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Inspections</h1>
    <a href="{{ route('inspections.create') }}" class="btn btn-primary">New Inspection</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Equipment</th>
                    <th>Inspector</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($inspections as $inspection)
                    <tr>
                        <td>{{ $inspection->equipment->equipment_name ?? 'N/A' }}</td>
                        <td>{{ $inspection->inspector->name ?? 'N/A' }}</td>
                        <td>{{ $inspection->status }}</td>
                        <td>{{ optional($inspection->survey_timestamp)->format('Y-m-d') }}</td>
                        <td>
                            @if($inspection->status === 'Submitted')
                                <form action="{{ route('inspections.approve', $inspection->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success">Approve</button>
                                </form>
                                <form action="{{ route('inspections.reject', $inspection->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-danger">Reject</button>
                                </form>
                            @else
                                <span class="text-muted">No actions</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $inspections->links() }}
    </div>
</div>
@endsection
