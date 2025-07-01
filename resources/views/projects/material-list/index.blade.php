@extends('layouts.master')
@section('title', 'Project Material-List')

@section('content')
<div class="container-fluid p-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-primary fw-bold">Material-List for Project: {{ $project->name }}</h1>
        <a href="{{ route('projects.material-list.create', $project) }}" class="btn btn-outline-primary">
            <i class="bi bi-plus-circle me-1"></i> New Material-List
        </a>
    </div>

    <div class="card shadow-sm rounded-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-nowrap">
                    <thead class="table-light">
                        <tr class="align-middle">
                            <th>#</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Approved By</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($materialLists as $materialList)
                            <tr>
                                <td><span class="badge bg-primary">{{ $materialList->id }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($materialList->start_date)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($materialList->end_date)->format('d M Y') }}</td>
                                <td>
                                    <span class="fw-semibold text-dark">{{ $materialList->approved_by }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('projects.material-list.show', [$project, $materialList]) }}" class="btn btn-sm btn-outline-info me-1" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('projects.material-list.edit', [$project, $materialList]) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if(auth()->user()->hasRole('super-admin'))
                                        <form action="{{ route('projects.material-list.destroy', ['project' => $project->id, 'materialList' => $materialList->id]) }}" method="POST" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"
                                                onclick="return confirm('Are you sure you want to delete this material list? This action cannot be undone.')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No material-lists created yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
