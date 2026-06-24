@extends('layouts.app')

@section('page-title', 'Companies')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Companies</h1>
    <a href="{{ route('companies.create') }}" class="btn btn-primary">Create Company</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Industry</th>
                    <th>Description</th>
                    <th>Created By</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($companies as $company)
                    <tr>
                        <td>{{ $company->name }}</td>
                        <td>{{ optional($company->industry)->name ?? '-' }}</td>
                        <td>{{ $company->description ?? '-' }}</td>
                        <td>{{ optional($company->creator)->name ?? 'System' }}</td>
                        <td>{{ optional($company->created_at)->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No companies found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
