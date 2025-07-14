@extends('layouts.master')
@section('title', '{{ isset($enquiry) ? "Enquiry" : "Project" }} Material-List')

@section('content')
<div class="container-fluid p-2">
    <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                @if(isset($enquiry))
                    <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}">Enquiries</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('enquiries.files', $enquiry) }}">{{ $enquiry->project_name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Project Material List</li>
                @else
                    <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">{{ $project->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Project Material List</li>
                @endif
            </ol>
        </nav>
        <h2 class="mb-0">Project Material List</h2>
    </div>
    <div class="page-actions">
        <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.files', $enquiry) : route('projects.files.index', $project) }}" class="btn btn-primary me-2">
            <i class="bi bi-arrow-left me-2"></i>Back to Files & Phases
        </a>
        <a href="{{ isset($enquiry) ? route('enquiries.material-list.create', $enquiry) : route('projects.material-list.create', $project) }}" class="btn btn-success">
            <i class="bi bi-plus-circle me-1"></i> New Material-List
        </a>
    </div>
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
                                <td><span class="fw-semibold text-dark">{{ $materialList->approved_by }}</span></td>
                                <td class="text-center">
                                    <a href="{{ isset($enquiry) ? route('enquiries.material-list.show', [$enquiry, $materialList]) : route('projects.material-list.show', [$project, $materialList]) }}" class="btn btn-sm btn-outline-info me-1" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <!-- <a href="{{ isset($enquiry) ? route('enquiries.material-list.edit', [$enquiry, $materialList]) : route('projects.material-list.edit', [$project, $materialList]) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a> -->
                                    @if(auth()->user()->hasRole('super-admin'))
                                        <form action="{{ isset($enquiry) ? route('enquiries.material-list.destroy', ['enquiry' => $enquiry->id, 'materialList' => $materialList->id]) : route('projects.material-list.destroy', ['project' => $project->id, 'materialList' => $materialList->id]) }}" method="POST" style="display: inline-block;">
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
