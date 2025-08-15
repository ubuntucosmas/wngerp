@extends('layouts.master')

@push('styles')
<style>
/* Ultra-compact design styles */
.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #198754 0%, #146c43 100%);
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107 0%, #e6ac00 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
}

.bg-gradient-danger {
    background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
}

.metric-card {
    transition: all 0.2s ease;
    border: 1px solid #e9ecef;
    box-shadow: 0 0.05rem 0.1rem rgba(0, 0, 0, 0.05);
    border-radius: 0.25rem;
}

.metric-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 0.2rem 0.4rem rgba(0, 0, 0, 0.1);
}

.section-card {
    transition: all 0.2s ease;
    border: 1px solid #e9ecef;
    box-shadow: 0 0.05rem 0.1rem rgba(0, 0, 0, 0.05);
    border-radius: 0.25rem;
}

.section-card:hover {
    box-shadow: 0 0.2rem 0.4rem rgba(0, 0, 0, 0.1);
}

/* Harmonized Typography System */
:root {
    --font-size-xs: 0.75rem;      /* 12px - Small text, captions */
    --font-size-sm: 0.875rem;     /* 14px - Body text, labels */
    --font-size-base: 1rem;       /* 16px - Base body text */
    --font-size-lg: 1.125rem;     /* 18px - Large text */
    --font-size-xl: 1.25rem;      /* 20px - Headings */
    
    --line-height-tight: 1.25;
    --line-height-normal: 1.5;
    --line-height-relaxed: 1.75;
}

/* Harmonized Card Styles */
.card-header {
    padding: 0.75rem 1rem !important;
    font-size: var(--font-size-sm);
    font-weight: 600;
    border-bottom: 1px solid #e9ecef;
    line-height: var(--line-height-tight);
}

.card-body {
    padding: 1rem !important;
    font-size: var(--font-size-sm);
    line-height: var(--line-height-normal);
}

.card-title {
    font-size: var(--font-size-sm) !important;
    font-weight: 600 !important;
    margin-bottom: 0.5rem !important;
    line-height: var(--line-height-tight);
}

/* Harmonized Text Hierarchy */
h5 { 
    font-size: var(--font-size-lg) !important; 
    font-weight: 600 !important;
    line-height: var(--line-height-tight);
}
h6 { 
    font-size: var(--font-size-base) !important; 
    font-weight: 600 !important;
    line-height: var(--line-height-tight);
}
small { 
    font-size: var(--font-size-xs) !important; 
    line-height: var(--line-height-normal);
}

/* Harmonized Buttons */
.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: var(--font-size-xs);
    font-weight: 500;
    border-radius: 0.375rem;
    line-height: var(--line-height-tight);
}

/* Harmonized Badges */
.badge {
    font-size: var(--font-size-xs);
    font-weight: 500;
    padding: 0.25rem 0.5rem;
    line-height: var(--line-height-tight);
}

.timeline {
    position: relative;
    padding-left: 1rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0;
    bottom: 0;
    width: 1px;
    background: #0d6efd;
}

.timeline-item {
    position: relative;
    margin-bottom: 0.5rem;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -0.75rem;
    top: 0.25rem;
    width: 0.5rem;
    height: 0.5rem;
    background: #0d6efd;
    border-radius: 50%;
    border: 1px solid #fff;
    box-shadow: 0 0 0 1px rgba(13, 110, 253, 0.25);
}

.info-item {
    background: #ffffff;
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
    font-size: var(--font-size-sm);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    line-height: var(--line-height-normal);
}

.info-item:hover {
    background: #f8f9fa;
    border-color: #0d6efd;
    transform: translateY(-1px);
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}

.info-label {
    font-size: var(--font-size-xs);
    font-weight: 700;
    color: #0dcaf0;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
    line-height: var(--line-height-tight);
    display: block;
    border-bottom: 2px solid #0dcaf0;
    padding-bottom: 0.25rem;
}

.info-value {
    font-size: var(--font-size-sm);
    font-weight: 500;
    color: #495057;
    line-height: var(--line-height-normal);
    display: block;
    margin-top: 0.5rem;
    background: #f8f9fa;
    padding: 0.5rem 0.75rem;
    border-radius: 0.375rem;
    border-left: 3px solid #0dcaf0;
}

.info-value.empty {
    color: #adb5bd;
    font-style: italic;
    background: #f1f3f4;
    border-left-color: #adb5bd;
}

/* Enhanced key-value layout with maximum distinction */
.kv-pair {
    display: flex;
    align-items: stretch;
    margin-bottom: 0.6rem;
    border-radius: 0.5rem;
    background: #ffffff;
    border: 2px solid #e9ecef;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: all 0.3s ease;
}

.kv-key {
    flex: 0 0 40%;
    font-size: var(--font-size-sm);
    font-weight: 600;
    color: #ffffff;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 1rem;
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    display: flex;
    align-items: center;
    justify-content: flex-start;
    text-align: start;
    border-right: 4px solid #ffffff;
    position: relative;
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2);
    line-height: var(--line-height-tight);
}

.kv-key::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
}

.kv-key::after {
    content: '';
    position: absolute;
    right: -12px;
    top: 50%;
    transform: translateY(-50%);
    width: 0;
    height: 0;
    border-left: 12px solid #0a58ca;
    border-top: 20px solid transparent;
    border-bottom: 20px solid transparent;
    z-index: 2;
    filter: drop-shadow(2px 0 2px rgba(0, 0, 0, 0.1));
}

.kv-value {
    flex: 1;
    font-size: var(--font-size-sm);
    font-weight: 500;
    color: #495057;
    padding: 1rem 1rem 1rem 1.5rem;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    display: flex;
    align-items: center;
    justify-content: flex-start;
    line-height: var(--line-height-normal);
    border-left: 2px solid #e9ecef;
    position: relative;
    text-align: start;
}

.kv-value::before {
    content: '';
    position: absolute;
    left: 0;
    top: 20%;
    bottom: 20%;
    width: 3px;
    background: linear-gradient(to bottom, #0d6efd, #0dcaf0);
    border-radius: 0 2px 2px 0;
}

.kv-value.empty {
    color: #6c757d;
    font-style: italic;
    font-weight: 400;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.kv-value.empty::before {
    background: linear-gradient(to bottom, #ffc107, #fd7e14);
}

/* Special styling for important values */
.kv-value.highlight {
    color: #1a252f;
    font-weight: 700;
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-left-color: #2196f3;
}

.kv-value.highlight::before {
    background: linear-gradient(to bottom, #2196f3, #03a9f4);
    width: 4px;
}

/* Enhanced hover effects */
.kv-pair:hover {
    transform: translateY(-3px) scale(1.01);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    border-color: #0d6efd;
    border-width: 3px;
}

.kv-pair:hover .kv-key {
    background: linear-gradient(135deg, #0b5ed7 0%, #0a58ca 100%);
    transform: scale(1.05);
}

.kv-pair:hover .kv-key::after {
    border-left-color: #0a58ca;
    border-left-width: 14px;
}

.kv-pair:hover .kv-value {
    background: linear-gradient(135deg, #f0f8ff 0%, #e3f2fd 100%);
    color: #1a252f;
}

.kv-pair.critical:hover {
    transform: translateY(-3px) scale(1.03);
    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
}

/* Data type specific styling */
.kv-pair[data-type="date"] .kv-key {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.kv-pair[data-type="date"] .kv-key::after {
    border-left-color: #20c997;
}

.kv-pair[data-type="currency"] .kv-key {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    color: #212529;
}

.kv-pair[data-type="currency"] .kv-key::after {
    border-left-color: #fd7e14;
}

.kv-pair[data-type="status"] .kv-key {
    background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
}

.kv-pair[data-type="status"] .kv-key::after {
    border-left-color: #e83e8c;
}

/* Special styling for critical information */
.kv-pair.critical {
    border: 3px solid #dc3545;
    box-shadow: 0 4px 16px rgba(220, 53, 69, 0.25);
    transform: scale(1.02);
}

.kv-pair.critical .kv-key {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: #ffffff;
    font-weight: 950;
    font-size: 0.85rem;
    animation: pulse 2s infinite;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    letter-spacing: 0.25em;
}

.kv-pair.critical .kv-key::after {
    border-left-color: #c82333;
    border-left-width: 14px;
    border-top-width: 22px;
    border-bottom-width: 22px;
}

.kv-pair.critical .kv-value {
    background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
    color: #1a202c;
    font-weight: 800;
    font-size: 0.9rem;
}

.kv-pair.critical .kv-value::before {
    background: linear-gradient(to bottom, #dc3545, #e53e3e);
    width: 5px;
}

@keyframes pulse {
    0% { box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2); }
    50% { box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.4); }
    100% { box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2); }
}

/* Badge enhancements */
.badge {
    font-weight: 600;
    border-radius: 0.375rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

/* Typography improvements */
.kv-value strong {
    color: #212529;
    font-weight: 700;
}

/* Enhanced empty state styling */
.kv-value.empty {
    background: linear-gradient(135deg, #fff8e1 0%, #ffecb3 100%);
    border-left: 4px solid #ff9800;
    position: relative;
    color: #e65100 !important;
    font-weight: 600;
}

.kv-value.empty::before {
    content: '';
    position: absolute;
    left: 0;
    top: 20%;
    bottom: 20%;
    width: 4px;
    background: linear-gradient(to bottom, #ff9800, #f57c00);
    border-radius: 0 2px 2px 0;
}

.kv-value.empty::after {
    content: '⚠';
    position: absolute;
    right: 0.5rem;
    top: 50%;
    transform: translateY(-50%);
    color: #ff9800;
    font-weight: bold;
    font-size: 0.9rem;
    animation: blink 2s infinite;
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0.5; }
}

/* Enhanced Project Information Grid - 2 Column Layout */
.project-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
    align-items: stretch;
    justify-items: stretch;
    grid-auto-rows: minmax(130px, auto);
    align-content: start;
}

.info-row {
    display: flex;
    flex-direction: column;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 2px solid #e9ecef;
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    min-height: 130px;
    height: 100%;
    width: 100%;
    position: relative;
}

.info-row:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    border-color: #0dcaf0;
}

.info-row.critical-row {
    border-color: #dc3545;
    background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
}

.info-row.critical-row:hover {
    border-color: #c82333;
    box-shadow: 0 4px 16px rgba(220, 53, 69, 0.2);
}

.info-label-section {
    background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    text-align: start;
    border-bottom: 2px solid #0aa2c0;
    flex-shrink: 0;
    min-height: 50px;
}

.info-row.critical-row .info-label-section {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border-bottom-color: #c82333;
}

.info-label-wrapper {
    display: flex;
    flex-direction: row;
    align-items: center;
    width: 100%;
    justify-content: flex-start;
}

.info-label {
    font-size: var(--font-size-xs);
    font-weight: 600;
    color: #ffffff;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    line-height: var(--line-height-tight);
    text-align: start;
}

.info-value-section {
    padding: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    background: #ffffff;
    position: relative;
    flex: 1;
    text-align: start;
    min-height: 80px;
}

.info-value-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 20%;
    right: 20%;
    height: 3px;
    background: linear-gradient(to right, #0dcaf0, #17a2b8);
    border-radius: 0 0 2px 2px;
}

.info-row.critical-row .info-value-section::before {
    background: linear-gradient(to right, #dc3545, #e53e3e);
}

.info-value-wrapper {
    font-size: var(--font-size-sm);
    font-weight: 500;
    color: #0dcaf0;
    line-height: var(--line-height-normal);
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    justify-content: flex-start;
    text-align: start;
}

.info-value-wrapper.highlight {
    color: #0aa2c0;
    font-weight: 700;
}

.info-value-wrapper.critical {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #dc3545;
}

.info-value-wrapper.empty {
    color: #6c757d;
    font-style: italic;
}

/* Responsive Grid Adjustments */
@media (max-width: 768px) {
    .project-info-grid {
        grid-template-columns: 1fr;
        gap: 0.5rem;
        grid-auto-rows: minmax(80px, auto);
    }
    
    .info-row {
        min-height: 80px;
    }
    
    .info-label-section {
        padding: 0.5rem;
    }
    
    .info-value-section {
        padding: 0.75rem;
        min-height: 40px;
    }
}

/* Enhanced data presentation */
.data-row {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
}

.data-row:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #0d6efd;
}

.data-label {
    font-size: var(--font-size-xs);
    font-weight: 600;
    color: #495057;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
    display: block;
    line-height: var(--line-height-tight);
}

.data-value {
    font-size: var(--font-size-sm);
    font-weight: 500;
    color: #212529;
    line-height: var(--line-height-normal);
}

.file-item {
    transition: all 0.2s ease;
    padding: 0.25rem 0.5rem;
}

.file-item:hover {
    background: #f8f9fa;
    border-color: #0d6efd;
}

.progress-custom {
    height: 0.4rem;
    border-radius: 0.2rem;
}

.status-badge {
    font-size: var(--font-size-xs);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    line-height: var(--line-height-tight);
}

/* Delete button styling */
.btn-danger:hover {
    transform: translateY(-1px);
    box-shadow: 0 0.2rem 0.4rem rgba(220, 53, 69, 0.3);
}

.modal-content {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-header.bg-danger {
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
}

/* Toast notification styling */
.toast-container {
    z-index: 1055;
}

.toast {
    min-width: 300px;
}

@media print {
    .no-print {
        display: none !important;
    }
    
    .card {
        break-inside: avoid;
        box-shadow: none !important;
        border: 1px solid #dee2e6 !important;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid py-1">
    <!-- Single Comprehensive Report Card -->
    <div class="row">
        <div class="col-12">
            <div class="card section-card">
                <!-- Card Header with Project Info and Actions -->
                <div class="card-header bg-gradient-primary text-white">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="card-title">
                                <i class="fas fa-clipboard-check me-2"></i>
                                Project Close-Out Report
                            </div>
                            <div class="small opacity-75">
                                <strong>{{ Str::limit($project->name, 40) }}</strong> • {{ $project->project_id }}
                            </div>
                        </div>
                        <div class="col-md-3 text-start">
                            @php
                                $statusConfig = match($report->status ?? 'draft') {
                                    'draft' => ['class' => 'bg-secondary', 'icon' => 'fas fa-edit'],
                                    'submitted' => ['class' => 'bg-info text-dark', 'icon' => 'fas fa-clock'],
                                    'approved' => ['class' => 'bg-success', 'icon' => 'fas fa-check-circle'],
                                    'rejected' => ['class' => 'bg-danger', 'icon' => 'fas fa-times-circle'],
                                    default => ['class' => 'bg-secondary', 'icon' => 'fas fa-question']
                                };
                            @endphp
                            <span class="{{ $statusConfig['class'] }}">
                                <i class="{{ $statusConfig['icon'] }} me-1"></i>
                                {{ ucfirst($report->status ?? 'Draft') }}
                            </span>
                        </div>
                        <div class="col-md-3 text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('projects.close-out-report.edit', [$project, $report]) }}" class="btn btn-light btn-sm no-print">
                                    <i class="fas fa-edit me-2"></i>
                                    Edit
                                </a>
                                <a href="{{ route('projects.close-out-report.download', [$project, $report ?? $project]) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-download me-2"></i>
                                    PDF
                                </a>
                                @if(($report->status ?? 'draft') === 'draft' && auth()->user()->can('edit', $project))
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteReportModal">
                                    <i class="fas fa-trash me-2"></i>
                                    Delete
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Single Card Body with All Content -->
                    <!-- Quick Stats Summary (compact) -->
                    <div class="row g-1 p-2">
                        @php
                            $attachmentsCount = $report->attachments?->count() ?? 0;
                            $totalBudgets = $project->budgets?->count() ?? 0;
                            $totalBudgetAmount = 0;
                            if ($project->budgets && $project->budgets->count() > 0) {
                                foreach ($project->budgets as $b) {
                                    $totalBudgetAmount += $b->budget_total ?? ($b->items?->sum('budgeted_cost') ?? 0);
                                }
                            }
                            $durationDays = ($report->set_up_date && $report->set_down_date)
                                ? $report->set_up_date->diffInDays($report->set_down_date)
                                : (($project->start_date && $project->end_date) ? $project->start_date->diffInDays($project->end_date) : null);
                        @endphp
                        <div class="col-6 col-lg-3">
                            <div class="metric-card p-2 text-start bg-white">
                                <div class="small text-muted">Duration</div>
                                <div class="fw-bold">{{ $durationDays !== null ? $durationDays.' days' : 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="metric-card p-2 text-start bg-white">
                                <div class="small text-muted">Budgets</div>
                                <div class="fw-bold">{{ $totalBudgets }} @if($totalBudgets) • KSh {{ number_format($totalBudgetAmount, 0) }} @endif</div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="metric-card p-2 text-start bg-white">
                                <div class="small text-muted">Attachments</div>
                                <div class="fw-bold">{{ $attachmentsCount }}</div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="metric-card p-2 text-start bg-white">
                                <div class="small text-muted">Status</div>
                                <div class="fw-bold text-capitalize">{{ $report->status ?? 'draft' }}</div>
                            </div>
                        </div>
                    </div>

                    @if(($report->status ?? null) === 'rejected' && $report->rejection_reason)
                    <div class="row g-1 px-2">
                        <div class="col-12">
                            <div class="alert alert-danger py-2 mb-0">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Rejection Reason:</strong> {{ $report->rejection_reason }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- All Sections in Organized Grid -->
                    <div class="row g-1">
                        <!-- Section 1: Project Information -->
                        <div class="col-lg-6">
                            <div class="border rounded-3 p-3 h-100 shadow-sm bg-light bg-gradient">
                                
                                <!-- Section Header -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary rounded-circle p-2 me-2">
                                        <i class="fas fa-info-circle text-white fs-6"></i>
                                    </div>
                                    <h6 class="text-primary mb-0 fw-bold" style="font-size: 1.25rem;">
                                        Section 1: Project Information
                                    </h6>
                                    <span class="ms-auto bg-opacity-10 text-primary">
                                        <i class="fas fa-check-circle me-1"></i> Basic Info
                                    </span>
                                </div>

                                <!-- Project Information using Row/Column Layout -->
                                <div class="row g-2">
                                    <!-- Project Title -->
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-header text-black py-2">
                                                <h6 class="mb-0 small d-flex align-items-start">
                                                    <i class="fas fa-project-diagram me-2"></i>
                                                    Project Title
                                                </h6>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-start">
                                                <strong class="text-info">{{ $report->project_title ?? $project->name }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Client Name -->
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-header text-black py-2">
                                                <h6 class="mb-0 small d-flex align-items-start">
                                                    <i class="fas fa-building me-2"></i>
                                                    Client Name
                                                </h6>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-start">
                                                @php
                                                    $clientDisplayName = $report->client_name
                                                        ?? ($project->client_name ?? ($project->client?->FullName ?? null));
                                                @endphp
                                                @if($clientDisplayName)
                                                    <strong class="text-info">{{ $clientDisplayName }}</strong>
                                                @else
                                                    <span class="text-muted fst-italic">Not specified</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Project Code -->
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-header text-black py-2">
                                                <h6 class="mb-0 small d-flex align-items-start">
                                                    <i class="fas fa-barcode me-2"></i>
                                                    Project Code/ID
                                                </h6>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-start">
                                                <span class="bg-info text-white px-3 py-2">{{ $report->project_code ?? $project->project_id }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Project Officer -->
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-header text-black py-2">
                                                <h6 class="mb-0 small d-flex align-items-start">
                                                    <i class="fas fa-user-tie me-2"></i>
                                                    Project Officer
                                                </h6>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-start">
                                                @if($report->project_officer)
                                                    <div class="d-flex align-items-start">
                                                        <i class="fas fa-check-circle text-info me-2 mt-1"></i>
                                                        <strong class="text-info">{{ $report->project_officer }}</strong>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-start">
                                                        <i class="fas fa-exclamation-triangle text-warning me-2 mt-1"></i>
                                                        <span class="text-muted fst-italic">Not assigned</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Setup Date -->
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-header text-black py-2">
                                                <h6 class="mb-0 small d-flex align-items-start">
                                                    <i class="fas fa-calendar-plus me-2"></i>
                                                    Set Up Date
                                                </h6>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-start">
                                                @if($report->set_up_date)
                                                    <div class="d-flex align-items-start">
                                                        <i class="fas fa-calendar-check text-info me-2 mt-1"></i>
                                                        <strong class="text-info">{{ $report->set_up_date->format('M d, Y') }}</strong>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-start">
                                                        <i class="fas fa-calendar-times text-muted me-2 mt-1"></i>
                                                        <span class="text-muted fst-italic">Not specified</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Set Down Date -->
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-header text-black py-2">
                                                <h6 class="mb-0 small d-flex align-items-start">
                                                    <i class="fas fa-calendar-minus me-2"></i>
                                                    Set Down Date
                                                </h6>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-start">
                                                @if($report->set_down_date)
                                                    <div class="d-flex align-items-start">
                                                        <i class="fas fa-calendar-check text-info me-2 mt-1"></i>
                                                        <strong class="text-info">{{ $report->set_down_date->format('M d, Y') }}</strong>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-start">
                                                        <i class="fas fa-calendar-times text-muted me-2 mt-1"></i>
                                                        <span class="text-muted fst-italic">Not specified</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Site Location -->
                                    <div class="col-md-12">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-header text-black py-2">
                                                <h6 class="mb-0 small d-flex align-items-start">
                                                    <i class="fas fa-map-marker-alt me-2"></i>
                                                    Site Location
                                                </h6>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-start">
                                                @if($report->site_location || ($project->siteSurveys && $project->siteSurveys->first()))
                                                    <div class="d-flex align-items-start">
                                                        <i class="fas fa-map-pin text-info me-2 mt-1"></i>
                                                        <strong class="text-info">{{ $report->site_location ?? $project->siteSurveys->first()->location }}</strong>
                                                    </div>
                                                @else
                                                    <div class="d-flex align-items-start">
                                                        <i class="fas fa-map-marked text-muted me-2 mt-1"></i>
                                                        <span class="text-muted fst-italic">Not specified</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Project Scope -->
                        <div class="col-lg-6">
                            <div class="border rounded-3 p-3 h-100 shadow-sm" style="background: linear-gradient(135deg, #e7f6fd 0%, #d1ecf1 100%);">
                                
                                <!-- Section Header -->
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-info rounded-circle p-2 me-2">
                                        <i class="fas fa-list-alt text-white fs-6"></i>
                                    </div>
                                    <h6 class="text-info mb-0 fw-bold">
                                        Section 2: Project Scope
                                    </h6>
                                    <span class="ms-auto badge bg-info bg-opacity-10 text-info">
                                        <i class="fas fa-bullseye me-1"></i>Scope
                                    </span>
                                </div>

                                <!-- Scope Summary Card -->
                                <div class="card mb-3 border-0 shadow-sm">
                                    <div class="card-header bg-info text-white py-2">
                                        <h6 class="mb-0 small">
                                            <i class="fas fa-clipboard-list me-2"></i>
                                            Project Scope Summary
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="p-2 rounded" style="background: #e3f2fd; border-left: 4px solid #2196f3;">
                                            @if($report->scope_summary)
                                                <div class="small text-dark" style="line-height: 1.5;">
                                                    {!! nl2br(e($report->scope_summary)) !!}
                                                </div>
                                            @else
                                                <div class="small text-muted fst-italic">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Brief description of the deliverables, scale, and key components not provided
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Project Deliverables Card -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-primary text-white py-2">
                                        <h6 class="mb-0 small">
                                            <i class="fas fa-tasks me-2"></i>
                                            Project Deliverables
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        @php
                                            // Get deliverables from multiple sources
                                            $deliverables = null;
                                            $source = '';
                                            
                                            // Priority 1: Project deliverables
                                            if ($project->deliverables) {
                                                $deliverables = $project->deliverables;
                                                $source = 'Project';
                                            }
                                            // Priority 2: Enquiry deliverables (if project was converted from enquiry)
                                            elseif ($project->enquiry && $project->enquiry->project_deliverables) {
                                                $deliverables = $project->enquiry->project_deliverables;
                                                $source = 'Original Enquiry';
                                            }
                                        @endphp

                                        <div class="p-2 rounded" style="background: #f3e5f5; border-left: 4px solid #9c27b0;">
                                            @if($deliverables)
                                                @php
                                                    $deliverablesList = array_filter(
                                                        preg_split('/\r\n|\r|\n/', $deliverables),
                                                        fn($item) => trim($item) !== ''
                                                    );
                                                @endphp
                                                
                                                @if(count($deliverablesList) > 0)
                                                    <div class="small fw-semibold text-primary mb-2">
                                                        <i class="fas fa-source me-1"></i>
                                                        Source: {{ $source }}
                                                    </div>
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach($deliverablesList as $deliverable)
                                                            <li class="mb-1 small text-dark">
                                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                                {{ trim($deliverable) }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <div class="small text-muted fst-italic">
                                                        <i class="fas fa-list me-1"></i>
                                                        No specific deliverables listed
                                                    </div>
                                                @endif
                                            @else
                                                <div class="small text-muted fst-italic">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                                    No deliverables specified for this project
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Procurement & Fabrication -->
                    <div class="row g-1 mt-1">
                            <!-- Section 3: Procurement & Inventory -->
                            <div class="border rounded-3 p-3 h-100 shadow-sm" style="background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 60%);">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-warning rounded-circle p-2 me-2">
                                        <i class="fas fa-clipboard-list text-dark" style="font-size: 0.8rem;"></i>
                                    </div>
                                    <h6 class="text-warning mb-0 fw-bold" style="color: #856404 !important; font-size: 1.25rem;">
                                        Section 3: Procurement & Inventory
                                    </h6>
                                    <span class="ms-auto bg-warning bg-opacity-10 text-warning" style="color: #856404 !important;">
                                        <i class="fas fa-shopping-cart me-1"></i>Materials
                                    </span>
                                </div>
                                
                                @if($project->materialLists && $project->materialLists->count() > 0)
                                    @php 
                                        $totalMaterialItems = $project->materialLists->sum(function($list) { 
                                            return $list->items->count() + $list->productionItems->count() + $list->labourItems->count(); 
                                        });
                                        $totalProductionItems = $project->materialLists->sum(function($list) { 
                                            return $list->productionItems->count(); 
                                        });
                                        $totalHireItems = $project->materialLists->sum(function($list) { 
                                            return $list->items->count(); 
                                        });
                                        $totalLabourItems = $project->materialLists->sum(function($list) { 
                                            return $list->labourItems->count(); 
                                        });
                                    @endphp
                    
                                    <!-- Material Summary Cards -->
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-3">
                                            <div class="card text-black">
                                                <div class="card-body p-2 text-center">
                                                    <h6 class="card-title mb-1" style="font-size: 1.2rem;">Production Items</h6>
                                                    <h4 class="mb-0">{{ $totalProductionItems }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card text-dark">
                                                <div class="card-body p-2 text-center">
                                                    <h6 class="card-title mb-1" style="font-size: 1.2rem;">Materials for Hire</h6>
                                                    <h4 class="mb-0">{{ $totalHireItems }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card text-white">
                                                <div class="card-body p-2 text-center">
                                                    <h6 class="card-title mb-1" style="font-size: 1.2rem;">Labour Items</h6>
                                                    <h4 class="mb-0">{{ $totalLabourItems }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card text-white">
                                                <div class="card-body p-2 text-center">
                                                    <h6 class="card-title mb-1" style="font-size: 1.2rem;">Total Items</h6>
                                                    <h4 class="mb-0">{{ $totalMaterialItems }}</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Detailed Material Lists -->
                                    <div class="accordion" id="materialListsAccordion">
                                        @foreach($project->materialLists as $materialList)
                                            @php
                                                $listTotalItems = $materialList->items->count() + $materialList->productionItems->count() + $materialList->labourItems->count();
                                            @endphp
                                            
                                            <div class="accordion-item" style="border: 1px solid #e9ecef; margin-bottom: 0.5rem;">
                                                <h2 class="accordion-header" id="heading{{ $materialList->id }}">
                                                    <button class="accordion-button collapsed small" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $materialList->id }}" aria-expanded="false">
                                                        <div class="w-100">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <strong>Material List #{{ $loop->iteration }}</strong>
                                                                    @if($materialList->date_range !== 'N/A')
                                                                        <small class="text-primary ms-2">(CLICK TO OPEN)</small>
                                                                    @endif
                                                                    <strong>Total Items: {{ $listTotalItems }}</strong>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </h2>
                                                <div id="collapse{{ $materialList->id }}" class="accordion-collapse collapse" data-bs-parent="#materialListsAccordion">
                                                    <div class="accordion-body" style="padding: 0.75rem; background: #f8f9fa;">
                                                        
                                                        <!-- Production Items -->
                                                        @if($materialList->productionItems->count() > 0)
                                                            <div class="mb-3">
                                                                <h6 class="text-primary mb-2 small">Production Items ({{ $materialList->productionItems->count() }})</h6>
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm table-striped" style="font-size: 0.65rem;">
                                                                        <thead class="table-primary">
                                                                            <tr>
                                                                                <th>Item</th>
                                                                                <th>Particular</th>
                                                                                <th>Unit</th>
                                                                                <th>Qty</th>
                                                                                <th>Unit Price</th>
                                                                                <th>Comment</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($materialList->productionItems as $item)
                                                                                @php
                                                                                    // Filter out particulars with empty quantity or no meaningful data
                                                                                    $validParticulars = $item->particulars ? $item->particulars->filter(function($particular) {
                                                                                        return ($particular->quantity ?? 0) > 0 || 
                                                                                               !empty($particular->particular) || 
                                                                                               !empty($particular->unit) || 
                                                                                               !empty($particular->comment);
                                                                                    }) : collect();
                                                                                @endphp
                                                                                @if($validParticulars->count() > 0)
                                                                                    @foreach($validParticulars as $particular)
                                                                                        <tr>
                                                                                            @if($loop->first)
                                                                                                <td rowspan="{{ $validParticulars->count() }}" class="align-middle">
                                                                                                    <strong>{{ $item->item_name }}</strong>
                                                                                                </td>
                                                                                            @endif
                                                                                            <td>{{ $particular->particular ?? '-' }}</td>
                                                                                            <td>{{ $particular->unit ?? '-' }}</td>
                                                                                            <td>{{ number_format($particular->quantity ?? 0, 2) }}</td>
                                                                                            <td>{{ $particular->unit_price ? number_format($particular->unit_price, 2) : '-' }}</td>
                                                                                            <td>{{ $particular->comment ?? '-' }}</td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                @elseif(($item->quantity ?? 0) > 0 || !empty($item->item_name))
                                                                                    <tr>
                                                                                        <td><strong>{{ $item->item_name }}</strong></td>
                                                                                        <td colspan="5" class="text-muted fst-italic">No particulars specified</td>
                                                                                    </tr>
                                                                                @endif
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        
                                                        <!-- Materials for Hire -->
                                                        @if($materialList->items->count() > 0)
                                                            <div class="mb-3">
                                                                <h6 class="text-warning mb-2" style="font-size: 0.7rem;">Materials for Hire ({{ $materialList->items->count() }})</h6>
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm table-striped" style="font-size: 0.65rem;">
                                                                        <thead class="table-warning">
                                                                            <tr>
                                                                                <th>Item</th>
                                                                                <th>Particular</th>
                                                                                <th>Unit</th>
                                                                                <th>Qty</th>
                                                                                <th>Unit Price</th>
                                                                                <th>Comment</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach($materialList->items as $item)
                                                                                @php
                                                                                    // Filter out items with empty quantity or no meaningful data
                                                                                    $hasValidData = ($item->quantity ?? 0) > 0 || 
                                                                                                  !empty($item->particular) || 
                                                                                                  !empty($item->unit) || 
                                                                                                  !empty($item->comment) ||
                                                                                                  !empty($item->item_name);
                                                                                @endphp
                                                                                @if($hasValidData)
                                                                                    <tr>
                                                                                        <td><strong>{{ $item->item_name }}</strong></td>
                                                                                        <td>{{ $item->particular ?? '-' }}</td>
                                                                                        <td>{{ $item->unit ?? '-' }}</td>
                                                                                        <td>{{ number_format($item->quantity ?? 0, 2) }}</td>
                                                                                        <td>{{ $item->unit_price ? number_format($item->unit_price, 2) : '-' }}</td>
                                                                                        <td>{{ $item->comment ?? '-' }}</td>
                                                                                    </tr>
                                                                                @endif
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        

                                                        
                                                        <!-- Material List Total -->
                                                        @php
                                                            // Count only non-empty production items
                                                            $validProductionCount = $materialList->productionItems->filter(function($item) {
                                                                if ($item->particulars && $item->particulars->count() > 0) {
                                                                    return $item->particulars->filter(function($particular) {
                                                                        return ($particular->quantity ?? 0) > 0 || 
                                                                               !empty($particular->particular) || 
                                                                               !empty($particular->unit) || 
                                                                               !empty($particular->comment);
                                                                    })->count() > 0;
                                                                }
                                                                return ($item->quantity ?? 0) > 0 || !empty($item->item_name);
                                                            })->count();
                                                            
                                                            // Count only non-empty hire items
                                                            $validHireCount = $materialList->items->filter(function($item) {
                                                                return ($item->quantity ?? 0) > 0 || 
                                                                       !empty($item->particular) || 
                                                                       !empty($item->unit) || 
                                                                       !empty($item->comment) ||
                                                                       !empty($item->item_name);
                                                            })->count();
                                                            
                                                            $totalMaterialItems = $validProductionCount + $validHireCount;
                                                        @endphp
                                                        <div class="alert alert-info mb-0" style="padding: 0.5rem; font-size: 0.7rem;">
                                                            <div class="row text-start">
                                                                <div class="col-6">
                                                                    <strong>Production:</strong> {{ $validProductionCount }} items
                                                                </div>
                                                                <div class="col-6">
                                                                    <strong>Hire:</strong> {{ $validHireCount }} items
                                                                </div>
                                                            </div>
                                                            <div class="text-start mt-2 pt-2 border-top">
                                                                <strong>Total Material Items: {{ $totalMaterialItems }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="kv-pair">
                                        <div class="kv-key">Materials Requested</div>
                                        <div class="kv-value empty">
                                            {{ $report->materials_requested_notes ?? 'No material lists available' }}
                                        </div>
                                    </div>
                                @endif
                                
                                @if($project->materialLists && $project->materialLists->count() > 0)
                                    <div class="alert alert-info mt-2 mb-2" style="padding: 0.5rem; font-size: 0.75rem;">
                                        <i class="fas fa-info-circle me-1"></i>
                                        <strong>Note:</strong> Detailed material lists with all items and particulars are shown above in the expandable sections.
                                    </div>
                                @endif
                                
                                <!-- Additional Procurement Info -->
                                <div class="row g-1 mt-2">
                                    <div class="col-6">
                                        <div class="kv-pair">
                                            <div class="kv-key">EXTERNAL ITEMS</div>
                                            <div class=" text-primary kv-value {{ !$report->items_sourced_externally ? 'empty' : '' }}">
                                                {{ Str::limit($report->items_sourced_externally ?: 'Not specified', 30) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="kv-pair">
                                            <div class="kv-key">STORE ITEMS</div>
                                            <div class=" text-primary kv-value {{ !$report->store_issued_items ? 'empty' : '' }}">
                                                {{ Str::limit($report->store_issued_items ?: 'Not specified', 30) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="kv-pair">
                                            <div class="kv-key">INVENTORY RETURNS</div>
                                            <div class=" text-primary kv-value {{ !$report->inventory_returns_balance ? 'empty' : '' }}">
                                                {{ $report->inventory_returns_balance ?: 'Not specified' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="kv-pair">
                                            <div class="kv-key">POCUREMENT CHALLENGES</div>
                                            <div class=" text-primary kv-value {{ !$report->procurement_challenges ? 'empty' : '' }}">
                                                {{ Str::limit($report->procurement_challenges ?: 'None reported', 40) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 4: Fabrication & Quality Control -->
                        <div class="col-lg-6">
                            <div class="border rounded p-2 h-100" style="background: #f8f9fa;">
                                <h6 class="text-primary mb-2" style="font-size: 1.25rem; border-bottom: 1px solid #dee2e6; padding-bottom: 0.25rem;">
                                    <i class="fas fa-cogs me-1" style="font-size: 1.6rem;"></i>
                                    Section 4: Fabrication & Quality Control
                                </h6>
                                <div class="row g-1">
                                    <div class="col-6">
                                        <div class="kv-pair">
                                            <div class="kv-key">Production Started</div>
                                            <div class="kv-value {{ !$report->production_start_date ? 'empty' : '' }}">
                                                {{ $report->production_start_date ? $report->production_start_date->format('M d, Y') : 'Not specified' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="kv-pair">
                                            <div class="kv-key">Packaging Status</div>
                                            <div class="kv-value {{ !$report->packaging_labeling_status ? 'empty' : '' }}">
                                                {{ Str::limit($report->packaging_labeling_status ?: 'Not specified', 20) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="kv-pair">
                                            <div class="kv-key">QC Findings</div>
                                            <div class="kv-value {{ !$report->qc_findings_resolutions ? 'empty' : '' }}">
                                                {{ Str::limit($report->qc_findings_resolutions ?: 'Not specified', 80) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="kv-pair">
                                            <div class="kv-key">Production Challenges</div>
                                            <div class="kv-value {{ !$report->production_challenges ? 'empty' : '' }}">
                                                {{ Str::limit($report->production_challenges ?: 'None reported', 80) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 3: Setup & Handover -->
                    <div class="row g-1 mt-1">
                        <!-- Section 5: On-Site Setup -->
                        <div class="col-lg-6">
                            <div class="border rounded p-2 h-100" style="background: #f8f9fa;">
                                <h6 class="text-success mb-2" style="font-size: 1.25rem; border-bottom: 1px solid #dee2e6; padding-bottom: 0.25rem;">
                                    <i class="fas fa-tools me-1" style="font-size: 0.6rem;"></i>
                                    Section 5: On-Site Setup
                                </h6>
                                <div class="row g-1">
                                    <div class="col-4">
                                        <div class="kv-pair">
                                            <div class="kv-key">Setup Date(s)</div>
                                            <div class="kv-value {{ !$report->setup_dates ? 'empty' : '' }}">
                                                {{ Str::limit($report->setup_dates ?: ($report->set_up_date ? $report->set_up_date->format('M d, Y') : 'Not specified'), 15) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="kv-pair">
                                            <div class="kv-key">Est. Time</div>
                                            <div class="kv-value {{ !$report->estimated_setup_time ? 'empty' : '' }}">
                                                {{ Str::limit($report->estimated_setup_time ?: 'Not specified', 15) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="kv-pair">
                                            <div class="kv-key">Actual Time</div>
                                            <div class="kv-value {{ !$report->actual_setup_time ? 'empty' : '' }}">
                                                {{ Str::limit($report->actual_setup_time ?: 'Not specified', 15) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="kv-pair">
                                            <div class="kv-key">Team Composition</div>
                                            <div class="kv-value {{ !$report->team_composition ? 'empty' : '' }}">
                                                {{ Str::limit($report->team_composition ?: 'Not specified', 80) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="kv-pair">
                                            <div class="kv-key">Site Challenges</div>
                                            <div class="kv-value {{ !$report->onsite_challenges ? 'empty' : '' }}">
                                                {{ Str::limit($report->onsite_challenges ?: 'None reported', 40) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="kv-pair">
                                            <div class="kv-key">Safety Issues</div>
                                            <div class="kv-value {{ !$report->safety_issues ? 'empty' : '' }}">
                                                {{ Str::limit($report->safety_issues ?: 'None reported', 40) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 6: Client Handover -->
                        <div class="col-lg-6">
                            <div class="border rounded p-2 h-100" style="background: #f8f9fa;">
                                <h6 class="text-info mb-2" style="font-size: 1.25rem; border-bottom: 1px solid #dee2e6; padding-bottom: 0.25rem;">
                                    <i class="fas fa-handshake me-1" style="font-size: 0.6rem;"></i>
                                    Section 6: Client Handover
                                </h6>
                                <div class="row g-1">
                                    <div class="col-6">
                                        <div class="kv-pair">
                                            <div class="kv-key">Handover Date</div>
                                            <div class="kv-value {{ !$report->handover_date ? 'empty' : '' }}">
                                                {{ $report->handover_date ? $report->handover_date->format('M d, Y') : 'Not specified' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="kv-pair">
                                            <div class="kv-key">Sign-off Status</div>
                                            <div class="kv-value {{ !$report->client_signoff_status ? 'empty' : '' }}">
                                                {{ Str::limit($report->client_signoff_status ?: 'Not specified', 20) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="kv-pair">
                                            <div class="kv-key">Client Feedback/QR Code</div>
                                            <div class="kv-value {{ !$report->client_feedback_qr ? 'empty' : '' }}">
                                                {{ Str::limit($report->client_feedback_qr ?: 'Not specified', 60) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="kv-pair">
                                            <div class="kv-key">Post-handover Adjustments</div>
                                            <div class="kv-value {{ !$report->post_handover_adjustments ? 'empty' : '' }}">
                                                {{ Str::limit($report->post_handover_adjustments ?: 'None reported', 80) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Row 4: Set-Down & Attachments -->
                    <div class="row g-1 mt-1">
                        <!-- Section 7: Set-Down & Debrief -->
                        <div class="col-lg-6">
                            <div class="border rounded p-2 h-100" style="background: #f8f9fa;">
                                <h6 class="text-danger mb-2" style="font-size: 1.25rem; border-bottom: 1px solid #dee2e6; padding-bottom: 0.25rem;">
                                    <i class="fas fa-clipboard-check me-1" style="font-size: 0.6rem;"></i>
                                    Section 7: Set-Down & Debrief
                                </h6>
                                <div class="row g-1">
                                    <div class="col-6">
                                        <div class="kv-pair">
                                            <div class="kv-key">Set-down Date</div>
                                            <div class="kv-value {{ !$report->set_down_date ? 'empty' : '' }}">
                                                {{ $report->set_down_date ? $report->set_down_date->format('M d, Y') : 'Not specified' }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="kv-pair">
                                            <div class="kv-key">Site Clearance</div>
                                            <div class="kv-value {{ !$report->site_clearance_status ? 'empty' : '' }}">
                                                {{ Str::limit($report->site_clearance_status ?: 'Not specified', 20) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="kv-pair">
                                            <div class="kv-key">Items Returned Condition</div>
                                            <div class="kv-value {{ !$report->condition_of_items_returned ? 'empty' : '' }}">
                                                {{ Str::limit($report->condition_of_items_returned ?: 'Not specified', 60) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="kv-pair">
                                            <div class="kv-key">Debrief Notes</div>
                                            <div class="kv-value {{ !$report->debrief_notes ? 'empty' : '' }}">
                                                {{ Str::limit($report->debrief_notes ?: 'Not specified', 80) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 8: Attachments Checklist -->
                        <div class="col-lg-6">
                            <div class="border rounded-3 p-3 h-100 shadow-sm" style="background: linear-gradient(135deg, #d1f2eb 0%, #a7e6d7 100%);">
                                
                                <!-- Section Header -->
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-success rounded-circle p-2 me-2">
                                        <i class="fas fa-check-square text-white fs-6"></i>
                                    </div>
                                    <h6 class="text-success mb-0 fw-bold" style="font-size: 1.25rem;">
                                        Section 8: Attachments Checklist
                                    </h6>
                                    <span class="ms-auto badge bg-success bg-opacity-10 text-success">
                                        <i class="fas fa-paperclip me-1"></i> Documents
                                    </span>
                                </div>

                                @php
                                    $attachmentItems = [
                                        'att_deliverables_ppt' => 'Deliverables PPT(PDF)',
                                        'att_cutlist' => 'Cutlist',
                                        'att_site_survey' => 'Site Survey Form',
                                        'att_project_budget' => 'Project Budget File',
                                        'att_mrf_or_material_list' => 'Material Requisition Form (MRF)/Material List',
                                        'att_qc_checklist' => 'QC Checklist',
                                        'att_setup_setdown_checklists' => 'Setup & Set-Down Checklists',
                                        'att_client_feedback_form' => 'Client Feedback Form(QR Code)',
                                    ];
                                @endphp

                                <!-- Attachments using Row/Column Layout -->
                                <div class="row g-2">
                                    @foreach($attachmentItems as $field => $label)
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-header {{ $report->$field ? 'bg-success' : 'bg-info' }} text-white py-2">
                                                <h6 class="mb-0 small d-flex align-items-start">
                                                    <i class="fas {{ $report->$field ? 'fa-check-square' : 'fa-square' }} me-2"></i>
                                                    {{ Str::limit($label, 25) }}
                                                </h6>
                                            </div>
                                            <div class="card-body p-3 d-flex align-items-start">
                                                <div class="d-flex align-items-start w-100">
                                                    @if($report->$field)
                                                        <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                                        <div>
                                                            <strong class="text-success small">Attached</strong>
                                                            <div class="small text-muted">Document is available</div>
                                                        </div>
                                                    @else
                                                        <i class="fas fa-exclamation-triangle text-warning me-2 mt-1"></i>
                                                        <div>
                                                            <strong class="text-warning small">Missing</strong>
                                                            <div class="small text-muted">Document not attached</div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attachments Files (actual uploads) -->
                    @if($report->attachments && $report->attachments->count() > 0)
                    <div class="row g-1 mt-1">
                        <div class="col-12">
                            <div class="border rounded-3 p-3 h-100 shadow-sm" style="background: linear-gradient(135deg, #f8f9fa 0%, #eef2f7 100%);">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-secondary rounded-circle p-2 me-2">
                                        <i class="fas fa-paperclip text-white" style="font-size: 0.8rem;"></i>
                                    </div>
                                    <h6 class="text-secondary mb-0 fw-bold" style="font-size: 1.1rem;">
                                        Uploaded Attachments ({{ $report->attachments->count() }})
                                    </h6>
                                    <div class="ms-auto d-flex gap-2">
                                        <form class="d-inline" method="POST" action="{{ route('projects.close-out-report.bulk-download', [$project, $report]) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-file-archive me-1"></i> Bulk Download
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-responsive" style="font-size: 0.8rem;">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-start">Filename</th>
                                                <th class="text-start">Uploaded</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($report->attachments as $attachment)
                                            <tr>
                                                <td class="text-start">{{ $attachment->filename }}</td>
                                                <td class="text-start">{{ $attachment->created_at?->format('M d, Y g:i A') }}</td>
                                                <td class="text-end">
                                                    <a href="{{ route('projects.close-out-report.attachments.download', [$project, $report, $attachment]) }}" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    @can('edit', $project)
                                                    <form method="POST" action="{{ route('projects.close-out-report.attachments.destroy', [$project, $report, $attachment]) }}" class="d-inline" onsubmit="return confirm('Delete this attachment?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    @endcan
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Row 5: Budget & Final Approval -->
                    <div class="row g-1 mt-1">
                        <!-- Budget Information -->
                        <div class="col-lg-6">
                            @if($project->budgets && $project->budgets->count() > 0)
                            <div class="border rounded-3 p-3 h-100 shadow-sm" style="background: linear-gradient(135deg, #d1e7dd 0%, #a3cfbb 100%);">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-success rounded-circle p-2 me-2">
                                        <i class="fas fa-chart-line text-white" style="font-size: 0.8rem;"></i>
                                    </div>
                                    <h6 class="text-success mb-0 fw-bold" style="font-size: 1.25rem;">
                                        Budget Analysis
                                    </h6>
                                    <div class="ms-auto">
                                        <span class="bg-success bg-opacity-10 text-success" style="font-size: 0.6rem;">
                                            <i class="fas fa-money-bill-wave me-1"></i>Financial
                                        </span>
                                    </div>
                                </div>
                                
                                @php
                                    $totalProjectBudget = 0;
                                    $totalSpentAmount = 0;
                                    $totalBudgetItems = 0;
                                    
                                    // Calculate comprehensive budget totals
                                    foreach($project->budgets as $budget) {
                                        $budgetTotal = $budget->budget_total ?? $budget->items->sum('budgeted_cost');
                                        $totalProjectBudget += $budgetTotal;
                                        $totalSpentAmount += $budget->spent_amount ?? 0;
                                        $totalBudgetItems += $budget->items->count();
                                    }
                                    
                                    $totalRemaining = $totalProjectBudget - $totalSpentAmount;
                                    $utilizationPercentage = $totalProjectBudget > 0 ? ($totalSpentAmount / $totalProjectBudget) * 100 : 0;
                                    $averageItemCost = $totalBudgetItems > 0 ? $totalProjectBudget / $totalBudgetItems : 0;
                                @endphp
                                
                                <!-- Budget Summary Cards -->
                                <div class="row g-1 mb-3">
                                    <div class="col-3">
                                        <div class="text-start p-2 rounded" style="background: #d4edda; border: 1px solid #c3e6cb;">
                                            <div class="fw-bold text-success">{{ $project->budgets->count() }}</div>
                                            <div class="small text-success">Budget(s)</div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="text-start p-2 rounded" style="background: #cce7ff; border: 1px solid #b3d9ff;">
                                            <div class="fw-bold text-info">{{ $totalBudgetItems }}</div>
                                            <div class="small text-info">Items</div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="text-start p-2 rounded" style="background: #fff3cd; border: 1px solid #ffeaa7;">
                                            <div class="fw-bold text-warning">KSh {{ number_format($totalProjectBudget, 0) }}</div>
                                            <div class="small text-warning">Total Budget</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detailed Budget Breakdown -->
                                <div class="accordion" id="budgetAccordion" style="font-size: 0.75rem;">
                                    @foreach($project->budgets as $budget)
                                        @php
                                            $budgetTotal = $budget->budget_total ?? $budget->items->sum('budgeted_cost');
                                        @endphp
                                        
                                        <div class="accordion-item" style="border: 1px solid #e9ecef; margin-bottom: 0.5rem;">
                                            <h2 class="accordion-header" id="budgetHeading{{ $budget->id }}">
                                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#budgetCollapse{{ $budget->id }}" aria-expanded="false" style="padding: 0.5rem; font-size: 0.7rem;">
                                                    <div class="w-100">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <strong>Budget #{{ $loop->iteration }}</strong>
                                                                @if($budget->start_date && $budget->end_date)
                                                                    <small class="text-muted ms-2">({{ $budget->start_date->format('M d') }} - {{ $budget->end_date->format('M d, Y') }})</small>
                                                                @endif
                                                            </div>
                                                            <div class="d-flex gap-3 m-3">
                                                                <span style="font-size: 0.6rem;">{{ $budget->items->count() }} items</span>
                                                                <span style="font-size: 0.6rem;">KSh {{ number_format($budgetTotal, 0) }}</span>
                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="budgetCollapse{{ $budget->id }}" class="accordion-collapse collapse" data-bs-parent="#budgetAccordion">
                                                <div class="accordion-body" style="padding: 0.75rem; background: #f8f9fa;">
                                                    
                                                    @if($budget->items->count() > 0)
                                                        <!-- Budget Items by Category -->
                                                        @php
                                                            $itemsByCategory = $budget->items->groupBy('category');
                                                        @endphp
                                                        
                                                        @foreach($itemsByCategory as $category => $items)
                                                            @php
                                                                // Filter out empty items and labour categories
                                                                $filteredItems = $items->filter(function($item) use ($category) {
                                                                    // Skip labour categories
                                                                    if (stripos($category, 'labour') !== false || stripos($category, 'labor') !== false) {
                                                                        return false;
                                                                    }
                                                                    
                                                                    // Filter out items with no meaningful data
                                                                    return ($item->quantity ?? 0) > 0 || 
                                                                           ($item->budgeted_cost ?? 0) > 0 || 
                                                                           !empty($item->item_name) || 
                                                                           !empty($item->particular) || 
                                                                           !empty($item->unit);
                                                                });
                                                            @endphp
                                                            @if($filteredItems->count() > 0 && stripos($category, 'labour') === false && stripos($category, 'labor') === false)
                                                                <div class="mb-3">
                                                                    <h6 class="text-primary mb-2" style="font-size: 0.7rem;">{{ $category ?: 'Uncategorized' }} ({{ $filteredItems->count() }})</h6>
                                                                    <div class="table-responsive">
                                                                        <table class="table table-sm table-striped" style="font-size: 0.65rem;">
                                                                            <thead class="table-primary">
                                                                                <tr>
                                                                                    <th>Item</th>
                                                                                    <th>Particular</th>
                                                                                    <th>Unit</th>
                                                                                    <th>Qty</th>
                                                                                    <th>Unit Price</th>
                                                                                    <th>Budgeted Cost</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach($filteredItems as $item)
                                                                                    <tr>
                                                                                        <td>{{ $item->item_name }}</td>
                                                                                        <td>{{ $item->particular ?? '-' }}</td>
                                                                                        <td>{{ $item->unit ?? '-' }}</td>
                                                                                        <td>{{ number_format($item->quantity ?? 1, 2) }}</td>
                                                                                        <td>KSh {{ number_format($item->unit_price ?? 0, 2) }}</td>
                                                                                        <td><strong>KSh {{ number_format($item->budgeted_cost ?? 0, 2) }}</strong></td>
                                                                                    </tr>
                                                                                @endforeach
                                                                                <tr class="table-secondary">
                                                                                    <td colspan="5"><strong>{{ $category ?: 'Uncategorized' }} Subtotal:</strong></td>
                                                                                    <td><strong>KSh {{ number_format($filteredItems->sum('budgeted_cost'), 2) }}</strong></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    
                                                    <!-- Budget Summary -->
                                                    <div class="alert alert-success mb-0" style="padding: 0.5rem; font-size: 0.7rem;">
                                                        <div class="row text-start">
                                                            <div class="col-3">
                                                                <strong>Items:</strong> {{ $budget->items->count() }}
                                                            </div>
                                                            <div class="col-3">
                                                                <strong>Budget:</strong> KSh {{ number_format($budgetTotal, 0) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <div class="border rounded p-2 h-100" style="background: #f8f9fa;">
                                <h6 class="text-muted mb-2" style="font-size: 1.25rem;">
                                    <i class="fas fa-dollar-sign me-1" style="font-size: 0.6rem;"></i>
                                    Budget Analysis
                                </h6>
                                <div class="info-item">
                                    <div class="info-value empty">No budget information available</div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Section 10: Final Approval -->
                        <div class="col-lg-6">
                            <div class="border rounded-3 p-3 h-100 shadow-sm" style="background: linear-gradient(135deg, #d1e7dd 0%, #badbcc 100%);">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-success rounded-circle p-2 me-2">
                                        <i class="fas fa-signature text-white" style="font-size: 0.8rem;"></i>
                                    </div>
                                    <h6 class="text-success mb-0 fw-bold" style="font-size: 1.25rem;">
                                        Section 10: Final Approval
                                    </h6>
                                    <div class="ms-auto">
                                        @php
                                            $approvalStatus = $report->project_officer ? 'pending' : 'incomplete';
                                            $statusConfig = $approvalStatus === 'pending' ? 
                                                ['class' => 'bg-warning', 'text' => 'Pending', 'icon' => 'clock'] :
                                                ['class' => 'bg-secondary', 'text' => 'Incomplete', 'icon' => 'exclamation'];
                                        @endphp
                                        <span class="badge {{ $statusConfig['class'] }} bg-opacity-10" style="color: #856404;">
                                            <i class="fas fa-{{ $statusConfig['icon'] }} me-1"></i>{{ $statusConfig['text'] }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row g-1">
                                    <div class="col-12">
                                        <div class="kv-pair">
                                            <div class="kv-key">Report Prepared by:</div>
                                            <div class="kv-value">
                                                <div class="fw-bold">{{ $report->project_officer ?? 'Not assigned' }}</div>
                                                @if($report->project_officer)
                                                    <div class="mt-2 pt-2 border-top small text-muted">
                                                        <strong>Date:</strong> {{ $report->updated_at ? $report->updated_at->format('d/m/Y') : '______' }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Submission Instructions -->
                    <div class="row g-1 mt-3">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #e7f3ff 0%, #cce7ff 100%);">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="bg-info rounded-circle p-2 me-2">
                                            <i class="fas fa-paper-plane text-white" style="font-size: 0.8rem;"></i>
                                        </div>
                                        <h6 class="text-info mb-0 fw-bold" style="font-size: 1.25rem;">
                                            Report Submission Instructions
                                        </h6>
                                        <span class="ms-auto badge bg-info bg-opacity-10 text-info">
                                            <i class="fas fa-clock me-1"></i>48 Hours
                                        </span>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-clock text-warning me-2 mt-1"></i>
                                                <div>
                                                    <div class="fw-semibold small">Timeline</div>
                                                    <div class="small text-muted">Complete within 48 hours of set-down</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-envelope text-primary me-2 mt-1"></i>
                                                <div>
                                                    <div class="fw-semibold small">Email Submission</div>
                                                    <div class="small text-muted">
                                                        <strong>projectsreports@woodnorkgreen.co.ke</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-cloud text-success me-2 mt-1"></i>
                                                <div>
                                                    <div class="fw-semibold small">File Storage</div>
                                                    <div class="small text-muted">Store in appropriate Google Drive folder</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-database text-info me-2 mt-1"></i>
                                                <div>
                                                    <div class="fw-semibold small">System Upload</div>
                                                    <div class="small text-muted">Attach report in New system</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row g-1 mt-2 no-print">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group" role="group">
                                    @if(($report->status ?? 'draft') === 'draft' && auth()->user()?->can('edit', $project))
                                    <form method="POST" action="{{ route('projects.close-out-report.submit', [$project, $report]) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="fas fa-paper-plane me-1"></i>
                                            Submit Report
                                        </button>
                                    </form>
                                    @endif

                                    @if(($report->status ?? '') === 'submitted' && auth()->user()?->can('edit', $project))
                                    <form method="POST" action="{{ route('projects.close-out-report.approve', [$project, $report]) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-check me-1"></i>
                                            Approve
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectReportModal">
                                        <i class="fas fa-times me-1"></i>
                                        Reject
                                    </button>
                                    @endif

                                    <form method="POST" action="{{ route('projects.close-out-report.email', [$project, $report]) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            <i class="fas fa-envelope me-1"></i>
                                            Email Report
                                        </button>
                                    </form>

                                    <a href="{{ route('projects.close-out-report.print', [$project, $report]) }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-print me-1"></i>
                                        Print
                                    </a>

                                    <form method="POST" action="{{ route('projects.close-out-report.export-word', [$project, $report]) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-dark btn-sm">
                                            <i class="fas fa-file-word me-1"></i>
                                            Word
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('projects.close-out-report.export-all-excel', [$project, $report]) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-file-excel me-1"></i>
                                            Excel
                                        </button>
                                    </form>
                                </div>
                                <a href="{{ route('projects.index') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Back to Projects
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


<!-- Reject Report Modal -->
@if(($report->status ?? '') === 'submitted' && auth()->user()?->can('edit', $project))
<div class="modal fade" id="rejectReportModal" tabindex="-1" aria-labelledby="rejectReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="rejectReportModalLabel">
                    <i class="fas fa-times-circle me-2"></i>
                    Reject Close-Out Report
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('projects.close-out-report.reject', [$project, $report]) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason</label>
                        <textarea name="rejection_reason" class="form-control" rows="3" required placeholder="Provide a clear reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i>
                        Confirm Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Delete Report Confirmation Modal -->
<div class="modal fade" id="deleteReportModal" tabindex="-1" aria-labelledby="deleteReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteReportModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Delete Close-Out Report
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-start mb-3">
                    <i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i>
                </div>
                <h6 class="text-start mb-3">Are you sure you want to delete this close-out report?</h6>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. All report data and attachments will be permanently deleted.
                </div>
                <div class="bg-light p-3 rounded">
                    <h6 class="mb-2">Report Details:</h6>
                    <ul class="list-unstyled mb-0 small">
                        <li><strong>Project:</strong> {{ $project->name }}</li>
                        <li><strong>Project ID:</strong> {{ $project->project_id }}</li>
                        <li><strong>Status:</strong> 
                            <span class="bg-{{ ($report->status ?? 'draft') === 'draft' ? 'secondary' : 'primary' }}">
                                {{ ucfirst($report->status ?? 'Draft') }}
                            </span>
                        </li>
                        <li><strong>Last Updated:</strong> {{ $report->updated_at ? $report->updated_at->format('M d, Y g:i A') : 'Never' }}</li>
                        @if($report->attachments && $report->attachments->count() > 0)
                        <li><strong>Attachments:</strong> {{ $report->attachments->count() }} file(s)</li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Cancel
                </button>
                <form action="{{ route('projects.close-out-report.destroy', [$project, $report]) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">
                        <i class="fas fa-trash me-1"></i>
                        Yes, Delete Report
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Report Modal -->
@include('projects.close-out-report.edit')

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Add smooth animations
    const cards = document.querySelectorAll('.section-card, .metric-card');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });

    // Delete confirmation functionality
    const deleteModal = document.getElementById('deleteReportModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    
    if (deleteModal && confirmDeleteBtn) {
        let clickCount = 0;
        
        confirmDeleteBtn.addEventListener('click', function(e) {
            clickCount++;
            
            if (clickCount === 1) {
                e.preventDefault();
                this.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Click Again to Confirm';
                this.classList.remove('btn-danger');
                this.classList.add('btn-warning');
                
                // Reset after 3 seconds
                setTimeout(() => {
                    clickCount = 0;
                    this.innerHTML = '<i class="fas fa-trash me-1"></i>Yes, Delete Report';
                    this.classList.remove('btn-warning');
                    this.classList.add('btn-danger');
                }, 3000);
            }
            // Second click will submit the form naturally
        });
        
        // Reset click count when modal is closed
        deleteModal.addEventListener('hidden.bs.modal', function() {
            clickCount = 0;
            confirmDeleteBtn.innerHTML = '<i class="fas fa-trash me-1"></i>Yes, Delete Report';
            confirmDeleteBtn.classList.remove('btn-warning');
            confirmDeleteBtn.classList.add('btn-danger');
        });
    }

    // Show success message if report was deleted
    @if(session('success'))
        // Create and show toast notification
        const toastHtml = `
            <div class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        // Add toast container if it doesn't exist
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }
        
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        const toast = new bootstrap.Toast(toastContainer.lastElementChild);
        toast.show();
    @endif
});
</script>
@endpush