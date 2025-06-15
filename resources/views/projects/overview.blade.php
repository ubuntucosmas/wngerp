@extends('layouts.master')

@pushOnce('scripts')
    {{-- This stack is used for scripts that should only be pushed once --}}
@endpushOnce

@section('title', 'Projects Dashboard')
@section('navbar-title', 'Projects Overview')
@section('content')
<style>
.card.shadow {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    /* box-shadow is included for consistency if we also wanted to change shadow on hover */
}
.card.shadow:hover {
    transform: scale(1.03);
    z-index: 10; /* Ensures the card is lifted above others */
}

/* Icon Bobbing Animation */
@keyframes iconBob {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-4px);
  }
}

.card.shadow:hover .bi.display-4 {
    animation: iconBob 0.7s ease-in-out infinite;
}
</style>
<div class="container">
    <h3 class="mb-4 text-dark">Project Dashboard</h3>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow border-0">
                <div class="card-body py-3">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="fw-semibold text-muted text-center mb-2">TOTAL PROJECTS</h6>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-5 text-center">
                            <i class="bi bi-briefcase-fill display-4 text-primary"></i>
                        </div>
                        <div class="col-7 text-center">
                            <h1 class="display-3 fw-bold text-primary mb-0">{{ $total }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light shadow border-0">
                <div class="card-body py-3">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="fw-semibold text-muted text-center mb-2">COMPLETED</h6>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-5 text-center">
                            <i class="bi bi-check-circle-fill display-4 text-success"></i>
                        </div>
                        <div class="col-7 text-center">
                            <h1 class="display-3 fw-bold text-success mb-0">{{ $completed }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-white shadow border-0">
                <div class="card-body py-3">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="fw-semibold text-muted text-center mb-2">ACTIVE</h6>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-5 text-center">
                            <i class="bi bi-activity display-4 text-info"></i>
                        </div>
                        <div class="col-7 text-center">
                            <h1 class="display-3 fw-bold text-info mb-0">{{ $active }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-gradient shadow border-0" style="background: linear-gradient(135deg, #17a2b8, #b1d4e0); color: #0c2d48;">
                <div class="card-body py-3">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="fw-semibold text-center mb-2" style="color: inherit;">AVG. PROGRESS</h6>
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-5 text-center">
                            <i class="bi bi-speedometer2 display-4" style="color: inherit;"></i>
                        </div>
                        <div class="col-7 text-center">
                            <h1 class="display-3 fw-bold mb-0" style="color: inherit;">{{ round($avgProgress, 1) }}%</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <hr>

    <div class="row mt-5 g-4">
        <div class="col-md-4">
            <h4 class="mb-3 text-primary">Top 4 Fast-Moving Projects</h4>
            @if($topMoving->count() > 0)
                @foreach ($topMoving as $project)
                    <div class="mb-3">
                        <a href="{{ route('projects.index', $project->id) }}" class="card shadow border-0 bg-white text-decoration-none d-block">
                            <div class="card-body p-3">
                                <h6 class="card-title text-info fw-bold mb-1">{{ Str::limit($project->name, 35) }}</h6>
                                <p class="text-muted mb-2 small">P.O: {{ $project->projectOfficer->name ?? 'N/A' }}</p>
                                <div class="progress" style="height: 15px;" role="progressbar" aria-valuenow="{{ $project->progress }}" aria-valuemin="0" aria-valuemax="100">
                                    @php
                                        $barColor = $project->progress < 40 ? 'bg-danger' : ($project->progress < 70 ? 'bg-warning' : 'bg-success');
                                    @endphp
                                    <div class="progress-bar {{ $barColor }}" style="width: {{ $project->progress }}%; font-size: 0.75rem;">
                                        {{ $project->progress }}%
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            @else
                <p class="text-muted">No projects to display.</p>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card shadow border-0 h-100">
                <div class="card-body d-flex flex-column">
                    <h4 class="card-title text-primary mb-3">Project Status Distribution</h4>
                    <div style="position: relative; min-height:250px; flex-grow:1;">
                        <canvas id="projectStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow border-0 h-100">
                <div class="card-body d-flex flex-column">
                    <h4 class="card-title text-primary mb-3">Top Projects Progress</h4>
                    <div style="position: relative; min-height:250px; flex-grow:1;">
                        <canvas id="topProjectsProgressChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('projectStatusChart').getContext('2d');
    const projectStatusChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Completed', 'Active', 'Other'],
            datasets: [{
                label: 'Project Status',
                data: [{{ $completed }}, {{ $active }}, {{ $total - $completed - $active }}],
                backgroundColor: [
                    'rgba(0, 204, 188, 0.75)',  // Vibrant Teal (Completed)
                    'rgba(255, 159, 64, 0.75)', // Energetic Orange (Active)
                    'rgba(153, 102, 255, 0.75)' // Lively Purple (Other)
                ],
                borderColor: [
                    'rgba(0, 204, 188, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed !== null) {
                                label += context.parsed;
                            }
                            return label;
                        }
                    }
                }
            }
        }
    });

    // New: Top Projects Progress Chart
    const topProjectsCtx = document.getElementById('topProjectsProgressChart')?.getContext('2d');
    if (topProjectsCtx) {
        const topProjectNames = {!! json_encode($topMoving->pluck('name')->map(function($name) { return Str::limit($name, 25); })) !!};
        const topProjectProgress = {!! json_encode($topMoving->pluck('progress')) !!};

        const topProjectsProgressChart = new Chart(topProjectsCtx, {
            type: 'bar',
            data: {
                labels: topProjectNames,
                datasets: [{
                    label: 'Progress (%)',
                    data: topProjectProgress,
                    backgroundColor: 'rgba(20, 126, 251, 0.75)', // Strong energetic blue
                    borderColor: 'rgba(20, 126, 251, 1)',
                    borderWidth: 1,
                    indexAxis: 'y', // This makes it a horizontal bar chart
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Progress (%)'
                        }
                    },
                    y: {
                        ticks: {
                            autoSkip: false // Ensure all labels are shown
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Legend might be redundant for a single dataset here
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.x !== null) {
                                    label += context.parsed.x + '%';
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush

@endsection
