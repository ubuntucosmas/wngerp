@extends('layouts.master')

@section('title', 'Projects Overview')
@section('navbar-title', 'Projects Information')

@section('content')
<style>
    h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #145DA0;
        margin-bottom: 1.5rem;
    }

    .table {
        font-size: 0.875rem;
        border-radius: 12px;
        overflow: hidden;
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .table th {
        white-space: nowrap;
        position: relative;
        background-color: #0C2D48 !important;
        color: white;
        font-weight: 500;
        padding: 0.75rem 1rem;
    }

    .table td {
        padding: 0.75rem 1rem;
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .table-striped > tbody > tr:nth-of-type(odd) > * {
        --bs-table-accent-bg: rgba(0, 0, 0, 0.02);
        color: var(--bs-table-striped-color);
    }

    .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        border-radius: 4px;
    }

    .btn-outline-secondary {
        color: #6c757d;
        border-color: #dee2e6;
    }

    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }

    .btn-outline-info {
        color: #0dcaf0;
        border-color: #0dcaf0;
    }

    .btn-outline-info:hover {
        background-color: #0dcaf0;
        color: white;
    }

    .progress {
        height: 15px;
        border-radius: 4px;
        background-color: #f8f9fa;
    }

    .progress-bar {
        border-radius: 4px;
        font-size: 0.7rem;
        line-height: 15px;
    }

    .text-primary {
        color: #0d6efd !important;
    }

    .text-info {
        color: #0dcaf0 !important;
    }

    .pagination {
        margin-bottom: 0;
    }

    .pagination .page-link {
        color: #0C2D48;
        border-color: #dee2e6;
    }

    .pagination .page-item.active .page-link {
        background-color: #0C2D48;
        border-color: #0C2D48;
    }

    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-3px);
    }
</style>

<div class="px-3 mx-10 mt-2 w-100">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
            <h2 class="mb-0 fw-bold me-4" style="letter-spacing:0.01em;">
                @if(isset($viewType) && $viewType === 'all')
                    All Projects
                @else
                    {{ auth()->user()->hasRole('po') ? 'My Projects' : 'Projects' }}
                @endif
            </h2>
        </div>
        @role('pm')
            @if(auth()->user()->level >= 4)
                <button class="btn btn-primary btn-sm shadow-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#newProjectModal">
                    <i class="bi bi-plus-circle"></i> <span>New Project</span>
                </button>
            @endif
        @endrole
    </div>
    <hr class="mb-4">

    <!-- Create Project Modal -->
    @include('partials.projects.create')

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <!-- Search Box Column -->
    <div class="search-box" style="min-width: 300px; max-width: 400px;">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control border-start-0" placeholder="Search projects..." id="projectSearch">
        </div>
    </div>

    <!-- Project Toggle Buttons Column -->
    @role('po')
    <div class="d-flex align-items-center gap-2" role="group">
        <a href="{{ route('projects.index') }}" 
           class="btn {{ (!isset($viewType) || $viewType === 'assigned') ? 'btn-primary' : 'btn-outline-primary' }} d-flex align-items-center gap-1">
            <i class="bi bi-person-check-fill"></i>
            <span>My Projects</span>
        </a>
        <span class="text-muted">|</span>
        <a href="{{ route('projects.all') }}" 
           class="btn {{ (isset($viewType) && $viewType === 'all') ? 'btn-primary' : 'btn-outline-primary' }} d-flex align-items-center gap-1">
            <i class="bi bi-people-fill"></i>
            <span>All Projects</span>
        </a>
    </div>
    @endrole
</div>
    <div class="table-responsive">
        <table class="table table-hover table-borderless align-middle">
            <thead>
                <tr>
                    <th>Project ID</th>
                    <th>Name</th>
                    <th>Client</th>
                    <th>Venue</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Officer</th>
                    <th>Progress</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="projectTableBody">
                @foreach($projects as $project)
                <tr>
                    <td class="text-primary"><strong>{{ $project->project_id }}</strong></td>
                    <td class="text-primary"><strong>{{ $project->name }}</strong></td>
                    <td>{{ $project->client_name }}</td>
                    <td>{{ $project->venue }}</td>
                    <td>{{ $project->start_date }}</td>
                    <td>{{ $project->end_date }}</td>
                    <td class="text-info">{{ $project->projectOfficer->name ?? '—' }}</td>
                    @php
                        $progress = $project->progress;
                        $barColor = $progress < 40 ? 'danger' : ($progress < 70 ? 'warning' : 'success');
                        $barColors = [
                            'danger' => '#dc3545',  // Red for danger
                            'warning' => '#ffc107', // Yellow for warning
                            'success' => '#198754'  // Green for success
                        ];
                        $bgColor = $barColors[$barColor];
                    @endphp

                    <td>
                        <div class="progress position-relative" style="height: 15px; border-radius: 5px; background-color: #f8f9fa;">
                            <div class="progress-bar" 
                                role="progressbar" 
                                style="width: {{ $progress }}%; 
                                       border-radius: 4px;
                                       background-color: {{ $bgColor }};"
                                aria-valuenow="{{ $progress }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                            </div>
                            <span class="position-absolute w-100 text-center fw-bold" style="color: #000; font-size: 0.7rem; line-height: 15px;">
                                {{ $progress }}%
                            </span>
                        </div>
                    </td>

                    <td class="text-end">
                        @if($project->status === 'closed')
                            <span class="bg-danger mb-1" title="This project is finalized and read-only">Project Closed</span>
                        @else
                            @role('pm')
                                @if(auth()->user()->level >= 4)
                                    <button class="btn btn-sm btn-outline-secondary mb-1" data-bs-toggle="modal" data-bs-target="#assignOfficerModal{{ $project->id }}">
                                        Assign Officer
                                    </button>

                                    @if(auth()->user()->level > 4)
                                        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger mb-1">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            @endrole

                            <div class="btn-group" role="group">
                                <a href="{{ route('projects.files.index', $project->id) }}" class="btn btn-sm btn-info">
                                    Project Files
                                </a>
                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="collapse" data-bs-target="#phasesCollapse{{ $project->id }}">
                                    Phases
                                </button>
                            </div>
                        @endif
                    </td>
                </tr>

                <tr class="collapse" id="phasesCollapse{{ $project->id }}">
                <td colspan="12" class="rounded-2 px-3 py-2">
                    @if($project->phases->count())
                        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-3 g-2">
                            @foreach($project->phases->take(9) as $phase)
                                <div class="col">
                                    <div class="card h-100 rounded-2 futuristic-card border-info shadow-sm">
                                        <div class="card-body p-2">
                                        <h6 class="card-title text-info-emphasis fw-semibold mb-1"
                                            data-bs-toggle="tooltip" title="{{ $phase->title }}">
                                            {{$phase->title}}
                                        </h6>
                                            <p class="card-text text-light-emphasis small mb-1">
                                                <span>Status: <span class="text-success">{{ $phase->status }}</span></span><br>
                                                <span>{{ $phase->start_date }} → {{ $phase->end_date }}</span>
                                            </p>
                                            <div class="d-flex justify-content-between gap-1">
                                                <a href="{{ route('phases.show', $phase->id) }}" class="btn btn-xs btn-outline-primary rounded-2 px-2 py-0">Tasks</a>
                                                <button class="btn btn-xs btn-outline-success rounded-2 px-2 py-0" data-bs-toggle="modal" data-bs-target="#editPhaseModal{{ $phase->id }}">
                                                    Edit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @include('partials.projects.phaseEdit', ['phase' => $phase])
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted small fst-italic">No phases available.</div>
                    @endif
                </td>
            </tr>

                @include('partials.projects.assign', ['project' => $project, 'users' => $users])
                @include('partials.projects.phase', ['project' => $project])
                @endforeach
            </tbody>
        </table>
            <div class="d-flex justify-content-center mt-3">
                {{ $projects->links('pagination::bootstrap-5') }}
            </div>
    </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('projectSearch');
            const rows = document.querySelectorAll('#projectTableBody tr');

            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        });
    </script>
@endsection
