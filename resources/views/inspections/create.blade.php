@extends('layouts.app')

@section('title', 'Create New Inspection')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <div>
                <h1 class="mb-1">Create New Inspection</h1>
                <p class="text-muted mb-0">Complete the inspection form with equipment details, findings, and photos.</p>
            </div>
            <a href="{{ route('inspections.index') }}" class="btn btn-outline-secondary">Back to Inspections</a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <h5 class="mb-2">Please fix the following errors:</h5>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('inspections.store') }}" enctype="multipart/form-data" class="card shadow-sm border-0">
            @csrf
            <div class="card-body">
                <div class="row gy-4">
                    <div class="col-12 col-lg-6">
                        <div class="card border-0 shadow-sm p-3 h-100">
                            <div class="card-body">
                                <h5 class="card-title">Inspection details</h5>
                                <div class="mb-3">
                                    <label class="form-label">Equipment</label>
                                    <select name="equipment_id" class="form-select" required>
                                        <option value="">Choose equipment</option>
                                        @foreach($equipments as $equipment)
                                            <option value="{{ $equipment->id }}" {{ old('equipment_id') === $equipment->id ? 'selected' : '' }}>{{ $equipment->equipment_name }} — {{ $equipment->unit_number }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                @if(! Auth::user()->hasRole('Inspector'))
                                    <div class="mb-3">
                                        <label class="form-label">Assign inspector</label>
                                        <select name="inspector_id" class="form-select" required>
                                            <option value="">Choose inspector</option>
                                            @foreach($inspectors as $inspector)
                                                <option value="{{ $inspector->id }}" {{ old('inspector_id') === $inspector->id ? 'selected' : '' }}>{{ $inspector->name }} ({{ $inspector->email }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <label class="form-label">Inspection type</label>
                                    <input name="inspection_type" type="text" class="form-control" value="{{ old('inspection_type') }}" placeholder="Example: Routine Safety Check" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Survey timestamp</label>
                                    <input name="survey_timestamp" type="datetime-local" class="form-control" value="{{ old('survey_timestamp') }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Unit photo</label>
                                    <input name="unit_photo" type="file" class="form-control" accept="image/*">
                                    <div class="form-text">Optional but recommended for documentation.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="card border-0 shadow-sm p-3 h-100">
                            <div class="card-body">
                                <h5 class="card-title">Inspection summary</h5>
                                <div class="mb-3">
                                    <label class="form-label">Inspection result</label>
                                    <textarea name="inspection_result" class="form-control" rows="4" placeholder="Add a short overview of condition">{{ old('inspection_result') }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Recommendation</label>
                                    <textarea name="recommendation" class="form-control" rows="4" placeholder="Add next steps or follow-up actions">{{ old('recommendation') }}</textarea>
                                </div>
                                <div class="alert alert-info mt-3 mb-0">
                                    <div class="fw-semibold">Pro tip:</div>
                                    Tambahkan minimal satu temuan untuk setiap inspeksi. Unggah foto temuan agar tim review lebih cepat memahami kondisi lapangan.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card border-0 shadow-sm p-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h5 class="card-title mb-1">Findings</h5>
                                        <p class="text-muted mb-0">Describe each issue clearly and attach evidence photos when possible.</p>
                                    </div>
                                    <button type="button" id="add-finding" class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-plus-lg"></i> Add finding
                                    </button>
                                </div>

                                <div id="findings-container">
                                    <div class="finding-item mb-4 border rounded-3 p-4 bg-light">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="mb-1">Finding #1</h6>
                                                <p class="text-muted mb-0">Describe the first inspection finding.</p>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Finding description</label>
                                            <textarea name="findings[0][finding_description]" class="form-control" rows="3" required>{{ old('findings.0.finding_description') }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Finding photos</label>
                                            <input name="finding_photos[0][]" type="file" class="form-control finding-photo-input" accept="image/*" multiple>
                                            <div class="form-text file-names text-muted">No files selected.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary btn-lg">Submit Inspection</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let findingIndex = 1;
    const container = document.getElementById('findings-container');

    function createFindingItem(index) {
        const item = document.createElement('div');
        item.className = 'finding-item mb-4 border rounded-3 p-4 bg-light';
        item.innerHTML = `
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="mb-1">Finding #${index + 1}</h6>
                    <p class="text-muted mb-0">Describe the inspection finding clearly.</p>
                </div>
                <button type="button" class="btn btn-sm btn-danger remove-finding">Remove</button>
            </div>
            <div class="mb-3">
                <label class="form-label">Finding description</label>
                <textarea name="findings[${index}][finding_description]" class="form-control" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Finding photos</label>
                <input name="finding_photos[${index}][]" type="file" class="form-control finding-photo-input" accept="image/*" multiple>
                <div class="form-text file-names text-muted">No files selected.</div>
            </div>
        `;

        container.appendChild(item);
        attachFileListener(item.querySelector('.finding-photo-input'));
    }

    function attachFileListener(input) {
        if (!input) {
            return;
        }

        input.addEventListener('change', function () {
            const names = Array.from(this.files).map(file => file.name).join(', ');
            const label = this.closest('.mb-3').querySelector('.file-names');
            label.textContent = names.length ? names : 'No files selected.';
        });
    }

    document.getElementById('add-finding').addEventListener('click', function () {
        createFindingItem(findingIndex);
        findingIndex++;
    });

    document.addEventListener('click', function (event) {
        if (event.target.matches('.remove-finding')) {
            const item = event.target.closest('.finding-item');
            item.remove();
        }
    });

    document.querySelectorAll('.finding-photo-input').forEach(attachFileListener);
</script>
@endpush
