@extends('layouts.app')

@section('page-title', 'Create Industry')

@section('content')
<div class="card">
    <div class="card-header">Create Industry</div>
    <div class="card-body">
        <form method="POST" action="{{ route('industries.store') }}">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Industry Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                @error('description')<div class="text-danger">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Create Industry</button>
            <a href="{{ route('industries.index') }}" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    </div>
</div>
@endsection
