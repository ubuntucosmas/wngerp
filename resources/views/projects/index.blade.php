@extends('layouts.master')

@section('title', 'Projects')
@section('navbar-title', 'Projects Dashboard')

@section('content')
<style>
    body {
        background: linear-gradient(120deg, #f8fafc 0%, #e0e7ef 100%);
    }
    .table {
        font-size: 0.95rem;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(44,62,80,0.07);
        background: #fff;
    }
    .table thead th {
        background: linear-gradient(90deg, #0C2D48 60%, #145DA0 100%) !important;
        color: #fff;
        font-weight: 600;
        letter-spacing: 0.02em;
        border: none;
    }
    .table tbody tr {
        transition: background 0.2s;
    }
    .table tbody tr:hover {
        background: #f1f6fb;
    }
    .list-group-item {
        border: none;
        border-top: 1px solid #e6e6e6;
        background: #f9fbfd;
        border-radius: 8px;
        margin-bottom: 4px;
        box-shadow: 0 1px 4px rgba(44,62,80,0.03);
    }
    .btn, .btn-sm, .btn-xs {
        border-radius: 6px;
        transition: all 0.18s cubic-bezier(.4,0,.2,1);
        box-shadow: 0 2px 6px rgba(44,62,80,0.07);
    }
    .btn-primary, .btn-outline-primary {
        background: linear-gradient(90deg, #145DA0 60%, #0C2D48 100%);
        border: none;
        color: #fff;
    }
    .btn-outline-primary:hover, .btn-primary:hover {
        background: linear-gradient(90deg, #0C2D48 60%, #145DA0 100%);
        color: #fff;
        transform: translateY(-2px) scale(1.03);
        box-shadow: 0 4px 12px rgba(20,93,160,0.13);
    }
    .btn-outline-secondary {
        border: 1px solid #bfc9d1;
        color: #145DA0;
        background: #f5f8fb;
    }
    .btn-outline-secondary:hover {
        background: #e3eaf3;
        color: #0C2D48;
        border-color: #145DA0;
    }
    .btn-xs {
        font-size: 0.72rem;
        padding: 0.21rem 0.6rem;
    }
    .form-control, .form-select {
        border-radius: 6px;
        border: 1px solid #bfc9d1;
        background: #f8fafc;
        transition: border 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #145DA0;
        box-shadow: 0 0 0 2px #b5d3f3;
    }
    .progress {
        border-radius: 8px;
        height: 1.1rem;
        background: #e6eaf1;
        box-shadow: 0 1px 2px rgba(44,62,80,0.05);
    }
    .progress-bar {
        transition: width 0.7s cubic-bezier(.4,0,.2,1);
        font-weight: 600;
        font-size: 0.85rem;
        letter-spacing: 0.01em;
        background: linear-gradient(90deg, #21e6c1 0%, #278ea5 100%);
        color: #0C2D48;
    }
    .progress-bar-danger {
        background: linear-gradient(90deg, #ff6b6b 0%, #ffb88c 100%);
    }
    .progress-bar-warning {
        background: linear-gradient(90deg, #ffd166 0%, #f6c453 100%);
        color: #7c4700;
    }
    .progress-bar-success {
        background: linear-gradient(90deg, #21e6c1 0%, #278ea5 100%);
        color: #0C2D48;
    }
    .badge-status {
        font-size: 0.8em;
        border-radius: 4px;
        padding: 0.3em 0.7em;
        font-weight: 500;
        letter-spacing: 0.01em;
    }
    .text-truncate {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        max-width: 150px;
        display: inline-block;
        vertical-align: bottom;
    }
    @media (max-width: 768px) {
        .table-responsive { font-size: 0.92rem; }
        .table th, .table td { padding: 0.5rem 0.2rem; }
        .text-truncate { max-width: 90px; }
    }
</style>

<div class="container mt-4">
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
                    @endphp

                    <td>
                        <div class="progress position-relative" style="height: 15px; border-radius: 5px; background-color:rgb(255, 255, 255);">
                            <div class="progress-bar bg-{{ $barColor }} progress-bar-striped progress-bar-animated" 
                                role="progressbar" 
                                style="width: {{ $progress }}%; border-radius: 4px;"
                                aria-valuenow="{{ $progress }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                            </div>
                            <span class="position-absolute w-100 text-center fw-bold" style="color: #000;">
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








