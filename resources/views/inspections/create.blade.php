@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-10">
        <h1 class="mb-4">New Inspection</h1>
        <form method="POST" action="{{ route('inspections.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Equipment</label>
                <select name="equipment_id" class="form-select" required>
                    <option value="">Choose equipment</option>
                    @foreach($equipments as $equipment)
                        <option value="{{ $equipment->id }}">{{ $equipment->equipment_name }} ({{ $equipment->unit_number }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Inspection Type</label>
                <input name="inspection_type" type="text" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Survey Timestamp</label>
                <input name="survey_timestamp" type="datetime-local" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Unit Photo</label>
                <input name="unit_photo" type="file" class="form-control">
            </div>
            <div id="findings-container">
                <div class="finding-item mb-3 border rounded p-3">
                    <label class="form-label">Finding description</label>
                    <textarea name="findings[0][finding_description]" class="form-control" rows="3" required></textarea>
                    <label class="form-label mt-3">Finding photos</label>
                    <input name="finding_photos[0][]" type="file" class="form-control" multiple>
                </div>
            </div>
            <button type="button" id="add-finding" class="btn btn-outline-secondary mb-4">Add Finding</button>
            <div class="mb-3">
                <label class="form-label">Inspection Result</label>
                <textarea name="inspection_result" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Recommendation</label>
                <textarea name="recommendation" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Inspection</button>
        </form>
    </div>
</div>
<script>
    let findingIndex = 1;
    document.getElementById('add-finding').addEventListener('click', function () {
        const container = document.getElementById('findings-container');
        const item = document.createElement('div');
        item.className = 'finding-item mb-3 border rounded p-3';
        item.innerHTML = `
            <label class="form-label">Finding description</label>
            <textarea name="findings[${findingIndex}][finding_description]" class="form-control" rows="3" required></textarea>
            <label class="form-label mt-3">Finding photos</label>
            <input name="finding_photos[${findingIndex}][]" type="file" class="form-control" multiple>
            <button type="button" class="btn btn-sm btn-danger mt-3 remove-finding">Remove finding</button>
        `;
        container.appendChild(item);
        findingIndex++;
    });

    document.addEventListener('click', function (event) {
        if (event.target.matches('.remove-finding')) {
            event.target.closest('.finding-item').remove();
        }
    });
</script>
@endsection
