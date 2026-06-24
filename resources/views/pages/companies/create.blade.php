@extends('layouts.app')

@section('page-title', 'Create Company')

@section('content')
<div class="card">
    <div class="card-header">Create Company</div>
    <div class="card-body">
        <form method="POST" action="{{ route('companies.store') }}">
            @csrf
            <div class="mb-3">
                <label for="industry_id" class="form-label">Industry</label>
                <select class="form-select" id="industry_id" name="industry_id" required>
                    <option value="">Select industry</option>
                    @foreach($industries as $industry)
                        <option value="{{ $industry->id }}" {{ old('industry_id') === $industry->id ? 'selected' : '' }}>{{ $industry->name }}</option>
                    @endforeach
                </select>
                @error('industry_id')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Company Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                @error('description')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Create Company</button>
            <a href="{{ route('companies.index') }}" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>
@endsection
