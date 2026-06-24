@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Import Master Equipment</h1>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="POST" action="{{ route('imports.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">Excel File</label>
                <input type="file" class="form-control" name="file" id="file" required>
                @error('file')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">Import History</div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>File</th>
                    <th>Status</th>
                    <th>Success</th>
                    <th>Failed</th>
                    <th>Submitted</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($histories as $history)
                    <tr>
                        <td>{{ $history->filename }}</td>
                        <td>{{ $history->status }}</td>
                        <td>{{ $history->success_rows }}</td>
                        <td>{{ $history->failed_rows }}</td>
                        <td>{{ optional($history->created_at)->format('Y-m-d H:i') }}</td>
                        <td>
                            @if(auth()->user()->hasRole('Super Admin'))
                                @if($history->status === 'Pending Review')
                                    <a href="{{ route('imports.show', $history->id) }}" class="btn btn-sm btn-outline-primary">Review</a>
                                @elseif($history->status === 'Rejected')
                                    <a href="{{ route('imports.show', $history->id) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                @elseif($history->status === 'Completed')
                                    <a href="{{ route('imports.show', $history->id) }}" class="btn btn-sm btn-outline-success">Details</a>
                                @else
                                    <a href="{{ route('imports.show', $history->id) }}" class="btn btn-sm btn-outline-info">Details</a>
                                @endif
                            @else
                                <span class="text-muted">No actions</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
