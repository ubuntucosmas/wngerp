@extends('layouts.master')

@push('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .survey-card, .survey-card * {
            visibility: visible;
        }
        .survey-card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none !important;
        }
        .no-print, .no-print * {
            display: none !important;
        }
        .card {
            border: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-2">
                        <h5 class="mb-0">Site Survey - {{ $project->name }}</h5>
                        <h5 class="mb-0 text-primary">#{{ $siteSurvey->project->project_id }}</h5>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('projects.site-survey.edit', [$project, $siteSurvey]) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('projects.site-survey.destroy', [$project, $siteSurvey]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this site survey? This action cannot be undone.')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                        <a href="{{ route('projects.site-survey.print', ['project' => $project]) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="fas fa-print"></i> Print
                        </a>
                        <a href="{{ route('projects.site-survey.download', ['project' => $project]) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                        <a href="{{ route('projects.files.index', $project) }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Project Files
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Basic Information Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="section-title">
                                <i class="fas fa-info-circle me-2"></i> Basic Information
                            </h4>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless mb-0">
                                                <tr>
                                                    <th style="width: 40%;">Site Visit Date</th>
                                                    <td>{{ \Carbon\Carbon::parse($siteSurvey->site_visit_date)->format('M d, Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Project Manager</th>
                                                    <td>{{ $siteSurvey->project_manager }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Client Name</th>
                                                    <td>{{ $siteSurvey->client_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Location</th>
                                                    <td>{{ $siteSurvey->location }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless mb-0">
                                                <tr>
                                                    <th style="width: 40%;">Contact Person</th>
                                                    <td>{{ $siteSurvey->client_contact_person }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Phone</th>
                                                    <td>{{ $siteSurvey->client_phone }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Email</th>
                                                    <td>{{ $siteSurvey->client_email ?? 'N/A' }}</td>
                                                </tr>
                                                @if($siteSurvey->project_description)
                                                <tr>
                                                    <th>Project Description</th>
                                                    <td>{{ $siteSurvey->project_description }}</td>
                                                </tr>
                                                @endif
                                                @if($siteSurvey->objectives)
                                                <tr>
                                                    <th>Objectives</th>
                                                    <td>{{ $siteSurvey->objectives }}</td>
                                                </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Site Assessment Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="section-title">
                                <i class="fas fa-clipboard-check me-2"></i> Site Assessment
                            </h4>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            @if($siteSurvey->current_condition)
                                            <div class="mb-3">
                                                <h6>Current Condition</h6>
                                                <p class="mb-0">{{ $siteSurvey->current_condition }}</p>
                                            </div>
                                            @endif
                                            
                                            @if($siteSurvey->existing_branding)
                                            <div class="mb-3">
                                                <h6>Existing Branding</h6>
                                                <p class="mb-0">{{ $siteSurvey->existing_branding }}</p>
                                            </div>
                                            @endif
                                            
                                            @if($siteSurvey->access_logistics)
                                            <div class="mb-3">
                                                <h6>Access & Logistics</h6>
                                                <p class="mb-0">{{ $siteSurvey->access_logistics }}</p>
                                            </div>
                                            @endif
                                            
                                            @if($siteSurvey->parking_availability)
                                            <div class="mb-3">
                                                <h6>Parking Availability</h6>
                                                <p class="mb-0">{{ $siteSurvey->parking_availability }}</p>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            @if($siteSurvey->size_accessibility)
                                            <div class="mb-3">
                                                <h6>Size & Accessibility</h6>
                                                <p class="mb-0">{{ $siteSurvey->size_accessibility }}</p>
                                            </div>
                                            @endif
                                            
                                            @if($siteSurvey->lifts || $siteSurvey->door_sizes || $siteSurvey->loading_areas)
                                            <div class="mb-3">
                                                <h6>Site Details</h6>
                                                <ul class="mb-0">
                                                    @if($siteSurvey->lifts)
                                                    <li><strong>Lifts:</strong> {{ $siteSurvey->lifts }}</li>
                                                    @endif
                                                    @if($siteSurvey->door_sizes)
                                                    <li><strong>Door Sizes:</strong> {{ $siteSurvey->door_sizes }}</li>
                                                    @endif
                                                    @if($siteSurvey->loading_areas)
                                                    <li><strong>Loading Areas:</strong> {{ $siteSurvey->loading_areas }}</li>
                                                    @endif
                                                </ul>
                                            </div>
                                            @endif
                                            
                                            @if($siteSurvey->site_measurements || $siteSurvey->room_size)
                                            <div class="mb-3">
                                                <h6>Measurements</h6>
                                                @if($siteSurvey->site_measurements)
                                                <p class="mb-1"><strong>Site Measurements:</strong> {{ $siteSurvey->site_measurements }}</p>
                                                @endif
                                                @if($siteSurvey->room_size)
                                                <p class="mb-0"><strong>Room Size:</strong> {{ $siteSurvey->room_size }}</p>
                                                @endif
                                            </div>
                                            @endif
                                            
                                            @if($siteSurvey->constraints || $siteSurvey->electrical_outlets || $siteSurvey->food_refreshment)
                                            <div class="mb-3">
                                                <h6>Additional Information</h6>
                                                <ul class="mb-0">
                                                    @if($siteSurvey->constraints)
                                                    <li><strong>Constraints:</strong> {{ $siteSurvey->constraints }}</li>
                                                    @endif
                                                    @if($siteSurvey->electrical_outlets)
                                                    <li><strong>Electrical Outlets:</strong> {{ $siteSurvey->electrical_outlets }}</li>
                                                    @endif
                                                    @if($siteSurvey->food_refreshment)
                                                    <li><strong>Food & Refreshment:</strong> {{ $siteSurvey->food_refreshment }}</li>
                                                    @endif
                                                </ul>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Client Requirements Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="section-title">
                                <i class="fas fa-clipboard-list me-2"></i> Client Requirements
                            </h4>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            @if($siteSurvey->branding_preferences)
                                            <div class="mb-3">
                                                <h6>Branding Preferences</h6>
                                                <p class="mb-0">{{ $siteSurvey->branding_preferences }}</p>
                                            </div>
                                            @endif
                                            
                                            @if($siteSurvey->material_preferred)
                                            <div class="mb-3">
                                                <h6>Preferred Materials</h6>
                                                <p class="mb-0">{{ $siteSurvey->material_preferred }}</p>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            @if($siteSurvey->color_scheme)
                                            <div class="mb-3">
                                                <h6>Color Scheme</h6>
                                                <p class="mb-0">{{ $siteSurvey->color_scheme }}</p>
                                            </div>
                                            @endif
                                            
                                            @if($siteSurvey->brand_guidelines || $siteSurvey->special_instructions)
                                            <div class="mb-3">
                                                @if($siteSurvey->brand_guidelines)
                                                <h6>Brand Guidelines</h6>
                                                <p class="mb-2">{{ $siteSurvey->brand_guidelines }}</p>
                                                @endif
                                                
                                                @if($siteSurvey->special_instructions)
                                                <h6>Special Instructions</h6>
                                                <p class="mb-0">{{ $siteSurvey->special_instructions }}</p>
                                                @endif
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Project Timeline Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="section-title">
                                <i class="far fa-calendar-alt me-2"></i> Project Timeline
                            </h4>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless mb-0">
                                                @if($siteSurvey->project_start_date)
                                                <tr>
                                                    <th style="width: 40%;">Start Date</th>
                                                    <td>{{ \Carbon\Carbon::parse($siteSurvey->project_start_date)->format('M d, Y') }}</td>
                                                </tr>
                                                @endif
                                                @if($siteSurvey->project_deadline)
                                                <tr>
                                                    <th>Deadline</th>
                                                    <td>{{ \Carbon\Carbon::parse($siteSurvey->project_deadline)->format('M d, Y') }}</td>
                                                </tr>
                                                @endif
                                            </table>
                                        </div>
                                        @if($siteSurvey->milestones)
                                        <div class="col-md-6">
                                            <h6>Milestones</h6>
                                            <p class="mb-0">{{ $siteSurvey->milestones }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Health and Safety Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="section-title">
                                <i class="fas fa-shield-alt me-2"></i> Health and Safety
                            </h4>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        @if($siteSurvey->safety_conditions)
                                        <div class="col-md-6">
                                            <h6>Safety Conditions</h6>
                                            <p class="mb-0">{{ $siteSurvey->safety_conditions }}</p>
                                        </div>
                                        @endif
                                        @if($siteSurvey->potential_hazards)
                                        <div class="col-md-6">
                                            <h6>Potential Hazards</h6>
                                            <p class="mb-0">{{ $siteSurvey->potential_hazards }}</p>
                                        </div>
                                        @endif
                                        @if($siteSurvey->safety_required)
                                        <div class="col-12 mt-3">
                                            <h6>Safety Equipment Required</h6>
                                            <p class="mb-0">{{ $siteSurvey->safety_required }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attendees Section -->
                    @if($siteSurvey->attendees && count($siteSurvey->attendees) > 0)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h4 class="section-title">
                                <i class="fas fa-users me-2"></i> Attendees
                            </h4>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($siteSurvey->attendees as $attendee)
                                                
                                                <ul>
                                                <li>    
                                                <i class="fas fa-user me-1"></i>
                                                    {{ $attendee }}
                                                </li>
                                                </ul>
                                                </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($siteSurvey->project_description || $siteSurvey->objectives)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6>Project Overview</h6>
                            <div class="card">
                                <div class="card-body">
                                    @if($siteSurvey->project_description)
                                    <h6>Description</h6>
                                    <p>{!! nl2br(e($siteSurvey->project_description)) !!}</p>
                                    @endif
                                    
                                    @if($siteSurvey->objectives)
                                    <h6>Objectives</h6>
                                    <p>{!! nl2br(e($siteSurvey->objectives)) !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($siteSurvey->current_condition || $siteSurvey->existing_branding || $siteSurvey->access_logistics)
                    <h4 class="section-title">Site Assessment</h4>
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="survey-card">
                                <div class="card-body">
                                    <div class="row g-4">
                                        @if($siteSurvey->current_condition)
                                        <div class="col-md-6">
                                            <div class="h-100 p-3 border rounded">
                                                <h5 class="d-flex align-items-center text-primary mb-3">
                                                    <i class="fas fa-clipboard-check me-2"></i> Current Condition
                                                </h5>
                                                <div class="text-muted">
                                                    {!! nl2br(e($siteSurvey->current_condition)) !!}
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        @if($siteSurvey->existing_branding)
                                        <div class="col-md-6">
                                            <div class="h-100 p-3 border rounded">
                                                <h5 class="d-flex align-items-center text-primary mb-3">
                                                    <i class="fas fa-tag me-2"></i> Existing Branding
                                                </h5>
                                                <div class="text-muted">
                                                    {!! nl2br(e($siteSurvey->existing_branding)) !!}
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        @if($siteSurvey->access_logistics)
                                        <div class="col-12">
                                            <div class="p-3 border rounded">
                                                <h5 class="d-flex align-items-center text-primary mb-3">
                                                    <i class="fas fa-truck me-2"></i> Access & Logistics
                                                </h5>
                                                <div class="text-muted">
                                                    {!! nl2br(e($siteSurvey->access_logistics)) !!}
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($siteSurvey->branding_preferences || $siteSurvey->material_preferred || $siteSurvey->color_scheme)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6>Client Requirements</h6>
                            <div class="card">
                                <div class="card-body">
                                    @if($siteSurvey->branding_preferences)
                                    <h6>Branding Preferences</h6>
                                    <p>{!! nl2br(e($siteSurvey->branding_preferences)) !!}</p>
                                    @endif

                                    @if($siteSurvey->material_preferred)
                                    <h6>Preferred Materials</h6>
                                    <p>{!! nl2br(e($siteSurvey->material_preferred)) !!}</p>
                                    @endif

                                    @if($siteSurvey->color_scheme)
                                    <h6>Color Scheme</h6>
                                    <p>{!! nl2br(e($siteSurvey->color_scheme)) !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($siteSurvey->project_start_date || $siteSurvey->project_deadline || $siteSurvey->milestones)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6>Project Timeline</h6>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        @if($siteSurvey->project_start_date)
                                        <div class="col-md-6">
                                            <h6>Start Date</h6>
                                            <p>{{ \Carbon\Carbon::parse($siteSurvey->project_start_date)->format('M d, Y') }}</p>
                                        </div>
                                        @endif
                                        
                                        @if($siteSurvey->project_deadline)
                                        <div class="col-md-6">
                                            <h6>Deadline</h6>
                                            <p>{{ \Carbon\Carbon::parse($siteSurvey->project_deadline)->format('M d, Y') }}</p>
                                        </div>
                                        @endif
                                    </div>

                                    @if($siteSurvey->milestones)
                                    <h6>Milestones</h6>
                                    <p>{!! nl2br(e($siteSurvey->milestones)) !!}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($siteSurvey->safety_conditions || $siteSurvey->potential_hazards || $siteSurvey->safety_required)
                    <h4 class="section-title">Health & Safety</h4>
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="survey-card">
                                <div class="card-body">
                                    <div class="row g-4">
                                        @if($siteSurvey->safety_conditions)
                                        <div class="col-md-6">
                                            <div class="h-100 p-3 border rounded">
                                                <h5 class="d-flex align-items-center text-primary mb-3">
                                                    <i class="fas fa-shield-alt me-2"></i> Safety Conditions
                                                </h5>
                                                <div class="text-muted">
                                                    {!! nl2br(e($siteSurvey->safety_conditions)) !!}
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                            @if($siteSurvey->potential_hazards)
                                        <div class="col-md-6">
                                            <div class="h-100 p-3 border rounded">
                                                <h5 class="d-flex align-items-center text-danger mb-3">
                                                    <i class="fas fa-exclamation-triangle me-2"></i> Potential Hazards
                                                </h5>
                                                <div class="text-muted">
                                                    {!! nl2br(e($siteSurvey->potential_hazards)) !!}
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        @if($siteSurvey->safety_required)
                                        <div class="col-12">
                                            <div class="p-3 border rounded">
                                                <h5 class="d-flex align-items-center text-warning mb-3">
                                                    <i class="fas fa-hard-hat me-2"></i> Required Safety Equipment
                                                </h5>
                                                <div class="text-muted">
                                                    {!! nl2br(e($siteSurvey->safety_required)) !!}
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($siteSurvey->additional_notes || $siteSurvey->special_requests || (is_array($siteSurvey->action_items) && count($siteSurvey->action_items) > 0))
                    <h4 class="section-title">Additional Information</h4>
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="survey-card">
                                <div class="card-body">
                                    <div class="row g-4">
                                        @if($siteSurvey->additional_notes)
                                        <div class="col-12">
                                            <div class="p-3 border rounded">
                                                <h5 class="d-flex align-items-center text-primary mb-3">
                                                    <i class="far fa-sticky-note me-2"></i> Additional Notes
                                                </h5>
                                                <div class="text-muted">
                                                    {!! nl2br(e($siteSurvey->additional_notes)) !!}
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        @if($siteSurvey->special_requests)
                                        <div class="col-md-6">
                                            <div class="h-100 p-3 border rounded">
                                                <h5 class="d-flex align-items-center text-primary mb-3">
                                                    <i class="fas fa-star me-2"></i> Special Requests
                                                </h5>
                                                <div class="text-muted">
                                                    {!! nl2br(e($siteSurvey->special_requests)) !!}
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        @if(is_array($siteSurvey->action_items) && count($siteSurvey->action_items) > 0)
                                        <div class="col-md-6">
                                            <div class="h-100 p-3 border rounded">
                                                <h5 class="d-flex align-items-center text-primary mb-3">
                                                    <i class="fas fa-tasks me-2"></i> Action Items
                                                </h5>
                                                <div class="list-group list-group-flush">
                                                    @foreach($siteSurvey->action_items as $item)
                                                    <div class="list-group-item d-flex align-items-center px-0">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                                                                <i class="fas fa-circle-check"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <p class="mb-0">{{ $item }}</p>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <h4 class="section-title">Approval</h4>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="survey-card h-100">
                                <div class="card-header d-flex align-items-center">
                                    <i class="fas fa-user-edit me-2"></i> Prepared By
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3">
                                            <i class="fas fa-user-tie fa-2x"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">{{ $siteSurvey->prepared_by }}</h5>
                                            <p class="text-muted mb-0">
                                                <i class="far fa-calendar-alt me-1"></i> 
                                                {{ \Carbon\Carbon::parse($siteSurvey->prepared_date)->format('F j, Y') }}
                                            </p>
                                        </div>
                                    </div>
                                    @if($siteSurvey->prepared_signature)
                                    <div class="mt-4 text-center">
                                        <div class="border rounded p-2 d-inline-block">
                                            <div class="text-muted small mb-2">Signature</div>
                                            <img src="{{ $siteSurvey->prepared_signature }}" alt="Signature" class="img-fluid" style="max-height: 80px;">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        @if($siteSurvey->client_approval_name)
                        <div class="col-md-6">
                            <div class="survey-card h-100">
                                <div class="card-header d-flex align-items-center">
                                    <i class="fas fa-user-check me-2"></i> Client Approval
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-success bg-opacity-10 text-success rounded-circle p-3 me-3">
                                            <i class="fas fa-user-tie fa-2x"></i>
                                        </div>
                                        <div>
                                            <h5 class="mb-0">{{ $siteSurvey->client_approval_name }}</h5>
                                            @if($siteSurvey->client_approval_date)
                                            <p class="text-muted mb-0">
                                                <i class="far fa-calendar-alt me-1"></i> 
                                                {{ \Carbon\Carbon::parse($siteSurvey->client_approval_date)->format('F j, Y') }}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                    @if($siteSurvey->client_signature)
                                    <div class="mt-4 text-center">
                                        <div class="border rounded p-2 d-inline-block">
                                            <div class="text-muted small mb-2">Client Signature</div>
                                            <img src="{{ $siteSurvey->client_signature }}" alt="Client Signature" class="img-fluid" style="max-height: 80px;">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="card-footer bg-light d-flex flex-column flex-md-row justify-content-between align-items-center py-3">
                    <div class="text-muted small mb-2 mb-md-0">
                        <i class="far fa-clock me-1"></i> 
                        Last updated {{ $siteSurvey->updated_at->diffForHumans() }}
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('projects.site-survey.edit', [$project, $siteSurvey]) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        @can('delete', $siteSurvey)
                        <form action="{{ route('projects.site-survey.destroy', [$project, $siteSurvey]) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('Are you sure you want to delete this site survey? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash me-1"></i> Delete
                            </button>
                        </form>
                        @endcan
                        <button onclick="window.print()" class="btn btn-print btn-sm">
                            <i class="fas fa-print me-1"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
