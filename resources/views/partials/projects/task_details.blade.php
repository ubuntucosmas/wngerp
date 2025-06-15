<div class="card shadow-sm p-4 rounded">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h4 class="mb-1 text-dark">{{ $task->name }}</h4>
            <small class="text-muted">{{ $task->created_at->format('M d, Y - H:i A') }}</small>
        </div>
        <span class="badge bg-{{ 
            $task->status === 'Completed' ? 'success' : 
            ($task->status === 'In Progress' ? 'warning' : 'secondary') 
        }} px-3 py-2 rounded-pill">
            {{ $task->status }}
        </span>
    </div>

    <hr class="my-3">

    <div class="mb-4">
        <h6 class="text-secondary mb-2">💬 Comment</h6>
        <p class="text-dark mb-0">{{ $task->comment ?? 'No comment provided' }}</p>
    </div>

    @if ($task->file)
        @php
            $fileUrl = asset('storage/' . $task->file);
            $extension = Str::lower(pathinfo($task->file, PATHINFO_EXTENSION));
        @endphp
        <div class="mb-4">
            <h6 class="text-secondary mb-2">📎 Attachment</h6>
            @if (in_array($extension, ['jpg', 'jpeg', 'png']))
                <img src="{{ $fileUrl }}" class="img-fluid rounded shadow-sm" style="max-height: 250px;">
            @else
                <a href="{{ $fileUrl }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill">
                    🔗 View Attachment
                </a>
            @endif
        </div>
    @endif

    <hr class="my-3">

    <div>
        <h6 class="mb-3 text-secondary">📌 Deliverables Checklist</h6>

        <form action="{{ route('phases.updateDeliverables', $task->id) }}" method="POST">
            @csrf
            @method('PUT')

            <ul class="list-group mb-3 deliverables-list rounded">
                @forelse ($task->deliverables as $deliverable)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="deliverables[{{ $deliverable->id }}][done]" 
                                {{ $deliverable->done ? 'checked' : '' }} id="deliverable_{{ $deliverable->id }}">
                            <label class="form-check-label" for="deliverable_{{ $deliverable->id }}">
                                {{ $deliverable->item }}
                            </label>
                        </div>
                        <input type="hidden" name="deliverables[{{ $deliverable->id }}][id]" value="{{ $deliverable->id }}">
                    </li>
                @empty
                    <li class="list-group-item text-muted">No deliverables added yet.</li>
                @endforelse
            </ul>

            @if ($task->deliverables->isNotEmpty())
                <button type="submit" class="btn btn-success btn-sm rounded-pill">
                    ✅ Save Checklist
                </button>
            @endif
        </form>
    </div>
</div>
