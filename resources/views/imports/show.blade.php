@extends('layouts.app')

@section('page-title', 'Review Import')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1>Import Review</h1>
        @if($history)
            <p class="mb-0 text-muted">File: <strong>{{ $history->filename }}</strong> &middot; Status: <strong>{{ $history->status }}</strong></p>
            <p class="text-muted">Uploaded by <strong>{{ optional($history->creator)->name ?? 'Unknown' }}</strong> on <strong>{{ optional($history->created_at)->format('Y-m-d H:i') }}</strong></p>
        @endif
    </div>
    <a href="{{ route('imports.index') }}" class="btn btn-secondary">Back to History</a>
</div>

@if(isset($preview) && count($preview['rows']) > 0)
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">Preview Summary</div>
                <div class="card-body">
                    <p><strong>Rows:</strong> {{ $preview['summary']['total_rows'] ?? count($preview['rows']) }}</p>
                    <p><strong>Valid rows:</strong> {{ $preview['summary']['valid_rows'] ?? 0 }}</p>
                    <p><strong>Invalid rows:</strong> {{ $preview['summary']['invalid_rows'] ?? 0 }}</p>
                    <p><strong>Total columns:</strong> {{ count($preview['columns'] ?? []) }}</p>

                    @if(!empty($preview['errors']))
                        <div class="alert alert-danger mt-3">
                            <h6 class="mb-2">Preview Errors</h6>
                            <ul class="mb-0">
                                @foreach($preview['errors'] as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            @if($history?->status === 'Pending Review')
                <div class="card mb-4">
                    <div class="card-header">Approval Actions</div>
                    <div class="card-body">
                        <form method="POST" id="approvalForm" action="{{ route('imports.approve', $history->id) }}">
                            @csrf
                            <div class="mb-3">
                                <label for="industry_id" class="form-label">Industry <span class="text-danger">*</span></label>
                                <select class="form-select" id="industry_id" name="industry_id" required>
                                    <option value="">-- Pilih Industri --</option>
                                    @foreach($industries as $industry)
                                        <option value="{{ $industry->id }}" {{ old('industry_id') == $industry->id ? 'selected' : '' }}>{{ $industry->name }}</option>
                                    @endforeach
                                </select>
                                @error('industry_id')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="company_id" class="form-label">Company <span class="text-danger">*</span></label>
                                <select class="form-select" id="company_id" name="company_id" required>
                                    <option value="">-- Pilih Perusahaan --</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" data-industry="{{ $company->industry_id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }} ({{ optional($company->industry)->name ?? 'No Industry' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_id')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="comment" class="form-label">Approval comment</label>
                                <textarea class="form-control" id="comment" name="comment" rows="3">{{ old('comment') }}</textarea>
                                @error('comment')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            <button type="submit" class="btn btn-success me-2">Approve & Execute</button>
                        </form>
                        <form method="POST" action="{{ route('imports.reject', $history->id) }}" class="mt-3">
                            @csrf
                            <div class="mb-3">
                                <label for="reject_comment" class="form-label">Rejection reason</label>
                                <textarea class="form-control" id="reject_comment" name="comment" rows="3">{{ old('comment') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-danger">Reject Import</button>
                        </form>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const industrySelect = document.getElementById('industry_id');
                        const companySelect = document.getElementById('company_id');

                        function filterCompanies() {
                            const selectedIndustry = industrySelect.value;
                            const options = companySelect.querySelectorAll('option[data-industry]');
                            options.forEach(option => {
                                if (!selectedIndustry || option.dataset.industry === selectedIndustry) {
                                    option.hidden = false;
                                } else {
                                    option.hidden = true;
                                }
                            });

                            const selectedOption = companySelect.querySelector('option:checked');
                            if (selectedOption && selectedOption.hidden) {
                                companySelect.value = '';
                            }
                        }

                        industrySelect.addEventListener('change', filterCompanies);
                        filterCompanies();
                    });
                </script>
            @endif
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">Imported Columns</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered mb-0">
                            <thead>
                                <tr>
                                    @foreach($preview['columns'] ?? [] as $column)
                                        <th>{{ $column }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Preview Rows</div>
                <div class="card-body table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>Row data</th>
                                <th>Status</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($preview['rows'] as $index => $row)
                                <tr>
                                    <td class="align-middle text-center">
                                        <input
                                            type="checkbox"
                                            name="approve_rows[]"
                                            value="{{ $row['row_number'] }}"
                                            form="approvalForm"
                                            {{ $row['valid'] ? 'checked' : '' }}
                                            {{ $row['valid'] ? '' : 'disabled' }}
                                        >
                                    </td>
                                    <td class="align-middle">{{ $index + 1 }}</td>
                                    <td>
                                        <pre class="mb-0" style="white-space: pre-wrap;">{{ json_encode($row['data'] ?? $row, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</pre>
                                    </td>
                                    <td>{{ $row['valid'] ? 'Valid' : 'Invalid' }}</td>
                                    <td>{{ implode('; ', $row['errors'] ?? []) ?: '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="alert alert-warning">
        <strong>No preview data available for this import.</strong>
        Please make sure the import file exists and the import was uploaded correctly.
    </div>

    @if(!empty($preview['errors']))
        <div class="alert alert-danger mt-3">
            <h5>Preview errors</h5>
            <ul class="mb-0">
                @foreach($preview['errors'] as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endif
@endsection
