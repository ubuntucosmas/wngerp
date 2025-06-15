<!-- Fancy, Compact Edit Phase Modal -->
<div class="modal fade" id="editPhaseModal{{ $phase->id }}" tabindex="-1" aria-labelledby="editPhaseLabel{{ $phase->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm"> <!-- Smaller, centered -->
        <div class="modal-content shadow-lg rounded-3 border-0">
            <form action="{{ route('phases.update', $phase->id) }}" method="POST" class="p-2">
                @csrf
                @method('PUT')
                
                <div class="modal-header bg-info text-white py-2 px-3 rounded-top">
                    <h6 class="modal-title" id="editPhaseLabel{{ $phase->id }}">Edit: {{ Str::limit($phase->title, 20) }}</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body px-3 py-2 small">
                    <div class="mb-2">
                        <label for="title" class="form-label mb-1">Title</label>
                        <input type="text" name="title" value="{{ $phase->title }}" class="form-control form-control-sm" required>
                    </div>

                    <div class="mb-2">
                        <label for="description" class="form-label mb-1">Description</label>
                        <textarea name="description" class="form-control form-control-sm" rows="2">{{ $phase->description }}</textarea>
                    </div>

                    <div class="mb-2">
                        <label for="start_date" class="form-label mb-1">Start Date</label>
                        <input type="date" name="start_date" value="{{ $phase->start_date }}" class="form-control form-control-sm" required>
                    </div>

                    <div class="mb-2">
                        <label for="end_date" class="form-label mb-1">End Date</label>
                        <input type="date" name="end_date" value="{{ $phase->end_date }}" class="form-control form-control-sm" required>
                    </div>

                    <div class="mb-2">
                        <label for="status" class="form-label mb-1">Status</label>
                        <select name="status" class="form-select form-select-sm" required>
                            <option value="Pending" {{ $phase->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="In Progress" {{ $phase->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Completed" {{ $phase->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer py-2 px-3">
                    <button type="submit" class="btn btn-sm btn-outline-primary px-3">Save</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
