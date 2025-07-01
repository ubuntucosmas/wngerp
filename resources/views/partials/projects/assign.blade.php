<div class="modal fade" id="assignOfficerModal{{ $project->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('projects.assign', $project->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Select Officer – Project {{ $project->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <select name="project_officer_id" class="form-select" required>
                        <option value="">-- Select Officer --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $user->id == $project->project_officer_id ? 'disabled' : '' }}>
                                {{ $user->name }} {{ $user->id == $project->project_officer_id ? '(Assigned)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-outline-primary">Assign</button>
                </div>
            </div>
        </form>
    </div>
</div>
