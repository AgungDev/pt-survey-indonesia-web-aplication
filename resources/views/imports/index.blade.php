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
                    <th>Started</th>
                </tr>
            </thead>
            <tbody>
                @foreach($histories as $history)
                    <tr>
                        <td>{{ $history->filename }}</td>
                        <td>{{ $history->status }}</td>
                        <td>{{ $history->success_rows }}</td>
                        <td>{{ $history->failed_rows }}</td>
                        <td>{{ optional($history->started_at)->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
