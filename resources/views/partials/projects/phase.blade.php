<div class="modal fade" id="createPhaseModal{{ $project->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('phases.store') }}" method="POST">
            @csrf
            <input type="hidden" name="project_id" value="{{ $project->id }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Add Phase – {{ $project->name }}</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="title" class="form-control mb-2" placeholder="Phase Title" required>
                    <textarea name="description" class="form-control mb-2" placeholder="Description"></textarea>
                    <input type="date" name="start_date" class="form-control mb-2" required>
                    <input type="date" name="end_date" class="form-control mb-2" required>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-primary" type="submit">Save Phase</button>
                </div>
            </div>
        </form>
    </div>
</div>
