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
                @elseif(isset($viewType) && $viewType === 'trashed')
                    Deleted Projects
                @else
                    {{ auth()->user()->hasRole('po') ? 'My Projects' : 'Projects' }}
                @endif
            </h2>
        </div>
        @hasanyrole('pm|super-admin') {{-- or replace super-admin with your actual top-level role --}}
            @if(auth()->user()->hasRole('super-admin') || auth()->user()->level >= 4)
                <button class="btn btn-primary btn-sm shadow-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#newProjectModal">
                    <i class="bi bi-plus-circle"></i> <span>New Project</span>
                </button>
            @endif
        @endhasanyrole

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
    @hasanyrole('po|super-admin') {{-- or replace super-admin with your actual top-level role --}}
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
        <span class="text-muted">|</span>
        <a href="{{ route('projects.trashed') }}" 
           class="btn {{ (isset($viewType) && $viewType === 'trashed') ? 'btn-danger' : 'btn-outline-danger' }} d-flex align-items-center gap-1">
            <i class="bi bi-trash"></i>
            <span>Deleted</span>
        </a>
    </div>
    @endhasanyrole
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
                        <th>Due Date</th>
                        <th>PO</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="projectTableBody">
                    @foreach($projects as $index => $project)
                    <tr class="table-animate" style="animation-delay: {{ $index * 0.05 }}s">
                        <td class="text-primary"><strong>{{ $project->project_id }}</strong></td>
                        <td class="text-primary"><strong>{{ $project->name }}</strong></td>
                        <td>{{ $project->client_name }}</td>
                        <td>{{ $project->venue }}</td>
                        <td>{{ $project->start_date }}</td>
                        <td>{{ $project->end_date }}</td>
                        <td class="text-info">{{ $project->projectOfficer->name ?? '—' }}</td>
                        <td>{{ $project->status }}</td>
                        @php
                            $progress = $project->progress;
                            if ($progress >= 80) {
                                $progressBarClass = 'bg-success'; // Green
                                $progressTextClass = 'text-white';
                            } elseif ($progress >= 40) {
                                $progressBarClass = 'bg-warning'; // Orange
                                $progressTextClass = 'text-white';
                            } else {
                                $progressBarClass = 'bg-danger'; // Red
                                $progressTextClass = 'text-white';
                            }
                        @endphp
                        <td style="min-width:120px;">
                            <div class="progress" style="height: 18px;">
                                <div class="progress-bar {{ $progressBarClass }}" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                    <span class="fw-bold text-white {{ $progressTextClass }}">{{ $progress }}%</span>
                                </div>
                            </div>
                        </td>
                        <td class="text-end">
                            @if(isset($viewType) && $viewType === 'trashed')
                                <!-- Actions for trashed projects -->
                                @hasanyrole('pm|super-admin')
                                <div class="btn-group" role="group">
                                    <form action="{{ route('projects.restore', $project->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Restore" onclick="return confirm('Are you sure you want to restore this project?')">
                                            <i class="bi bi-arrow-clockwise"></i> Restore
                                        </button>
                                    </form>
                                    @hasrole('super-admin')
                                    <form action="{{ route('projects.force-delete', $project->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Permanently Delete" onclick="return confirm('Are you sure you want to permanently delete this project? This action cannot be undone!')">
                                            <i class="bi bi-trash-fill"></i> Delete Forever
                                        </button>
                                    </form>
                                    @endhasrole
                                </div>
                                @endhasanyrole
                            @else
                                <!-- Actions for active projects -->
                                @if($project->status === 'closed')
                                    <span class="bg-danger mb-1" title="This project is finalized and read-only">Project Closed</span>
                                @else
                                    @hasanyrole('pm|super-admin')
                                        @if(auth()->user()->hasRole('super-admin') || auth()->user()->level >= 4)
                                            <button class="btn btn-sm btn-outline-secondary mb-1" data-bs-toggle="modal" data-bs-target="#assignOfficerModal{{ $project->id }}">
                                                Re-Assign
                                            </button>
                                            <x-delete-button :action="route('projects.destroy', $project->id)">
                                                Delete
                                            </x-delete-button>
                                        @endif
                                    @endhasanyrole
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('projects.files.index', $project->id) }}" class="btn btn-sm btn-info">
                                            Project Files
                                        </a>
                                        <!-- <button class="btn btn-sm btn-outline-info" data-bs-toggle="collapse" data-bs-target="#phasesCollapse{{ $project->id }}">
                                            Phases
                                        </button> -->
                                    </div>
                                @endif
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
                                                    <span>Status: 
                                                        <span class="badge bg-{{ $phase->status === 'Completed' ? 'success' : ($phase->status === 'In Progress' ? 'warning text-dark' : 'secondary') }}">
                                                            {{ $phase->status }}
                                                        </span>
                                                    </span><br>
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
        @push('styles')
        <style>
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            .table-animate {
                animation: fadeInUp 0.6s ease-out forwards;
                opacity: 0;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    @endpush
    </div>

    <script>
        function animateTableRows() {
            const rows = document.querySelectorAll('#projectTableBody tr');
            anime({
                targets: rows,
                translateY: [20, 0],
                opacity: [0, 1],
                duration: 600,
                delay: anime.stagger(50, {start: 100}),
                easing: 'easeOutQuad'
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initial animation
            animateTableRows();
            
            const searchInput = document.getElementById('projectSearch');
            
            // Store original rows for search functionality
            const originalRows = Array.from(document.querySelectorAll('#projectTableBody tr'));
            
            // Add animation class to new rows when they're added (for pagination)
            document.addEventListener('DOMNodeInserted', function(e) {
                if (e.target.matches('#projectTableBody tr')) {
                    e.target.style.opacity = '0';
                    e.target.style.transform = 'translateY(20px)';
                    anime({
                        targets: e.target,
                        translateY: 0,
                        opacity: 1,
                        duration: 400,
                        easing: 'easeOutQuad'
                    });
                }
            });
            const rows = document.querySelectorAll('#projectTableBody tr');

            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });
        });

                // Delete confirmation
                document.querySelectorAll('.delete-project').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
