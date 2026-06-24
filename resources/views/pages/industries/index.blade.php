@extends('layouts.app')

@section('page-title', 'Industries')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Industries</h1>
    <a href="{{ route('industries.create') }}" class="btn btn-primary">Create Industry</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Created By</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($industries as $industry)
                    <tr>
                        <td>{{ $industry->name }}</td>
                        <td>{{ $industry->description ?? '-' }}</td>
                        <td>{{ optional($industry->creator)->name ?? 'System' }}</td>
                        <td>{{ optional($industry->created_at)->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No industries found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
