<!-- Modern Form Styling -->
<style>
    :root {
        --primary-color: #0BADD3;
        --secondary-color: #6E6F71;
        --accent-color: #C8DA30;
        --light-bg: #f8fafc;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
        --form-section-spacing: 2.5rem;
    }
    
    html {
        scroll-behavior: smooth;
    }

    body {
        background-color: var(--light-bg);
    }

    .form-container {
        display: flex;
        gap: 2rem;
        margin-top: 2rem;
    }

    .sidebar-nav {
        position: sticky;
        top: 20px; /* Adjust as needed */
        height: fit-content;
        width: 250px;
        flex-shrink: 0;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: var(--card-shadow);
        padding: 1.5rem;
    }

    .sidebar-nav ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-nav li {
        margin-bottom: 0.75rem;
    }

    .sidebar-nav a {
        display: block;
        padding: 0.5rem 1rem;
        color: var(--secondary-color);
        text-decoration: none;
        border-radius: 6px;
        transition: var(--transition);
        font-weight: 500;
    }

    .sidebar-nav a:hover {
        background-color: rgba(var(--primary-color-rgb), 0.1);
        color: var(--primary-color);
    }

    .sidebar-nav a.active {
        background-color: var(--primary-color);
        color: white;
        box-shadow: 0 2px 8px rgba(var(--primary-color-rgb), 0.2);
    }

    .form-content {
        flex-grow: 1;
    }

    .form-section-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        margin-bottom: var(--form-section-spacing);
        overflow: hidden;
    }

    .form-section-card-header {
        background: linear-gradient(135deg, var(--primary-color), #0897c4);
        color: white;
        padding: 1.25rem 1.5rem;
        border-bottom: none;
    }

    .form-section-card-header h5 {
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        font-size: 1.25rem;
    }

    .form-section-card-header h5:before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 20px;
        background: var(--accent-color);
        margin-right: 12px;
        border-radius: 4px;
    }

    .form-section-card-body {
        padding: 2rem;
    }

    .form-label {
        font-weight: 500;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select, .select2-container--default .select2-selection--multiple {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.65rem 1rem;
        font-size: 0.95rem;
        transition: var(--transition);
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        background-color: #fff;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #e2e8f0;
        color: var(--secondary-color);
    }
    
    .required-field::after {
        content: '*';
        color: #dc3545;
        margin-left: 4px;
    }
    
    .form-text {
        color: #6c757d;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }
    
    .btn {
        font-weight: 500;
        padding: 0.6rem 1.5rem;
        border-radius: 6px;
        transition: all 0.2s ease-in-out;
    }
    
    .btn-primary {
        background: var(--primary-color);
        border: none;
        padding: 0.75rem 2rem;
        font-weight: 600;
        border-radius: 8px;
        transition: var(--transition);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-primary:hover {
        background: #0897c4;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(11, 173, 211, 0.3);
    }

    .signature-pad {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        cursor: crosshair;
        margin-bottom: 1rem;
    }

    @media (max-width: 992px) {
        .form-container {
            flex-direction: column;
        }
        .sidebar-nav {
            position: static;
            width: 100%;
            margin-bottom: 2rem;
        }
    }
</style>

@php
    // Ensure $siteSurvey is defined and has required properties
    if (!isset($siteSurvey)) {
        $siteSurvey = new \App\Models\SiteSurvey();
        $siteSurvey->exists = false;
    }
    
    // Initialize attachments collection if not set
    if (!isset($siteSurvey->attachments)) {
        $siteSurvey->attachments = collect();
    }
    
    // Helper function to safely get old input or model attribute
    function getFormValue($name, $model, $default = '') {
        $old = old($name);
        if ($old !== null) {
            return $old;
        }
        
        // Handle special cases
        if ($name === 'prepared_by' && !$model->exists) {
            return auth()->user()->name;
        }
        
        if ($name === 'prepared_date' && !$model->exists) {
            return now()->format('Y-m-d');
        }
        
        return $model->exists ? $model->$name : $default;
    }
@endphp

<div class="form-container">
    <nav class="sidebar-nav">
        <ul>
            <li><a href="#" class="sidebar-link active" data-bs-target="#collapseBasicInfo">Basic Information</a></li>
            <li><a href="#" class="sidebar-link" data-bs-target="#collapseContactInfo">Contact Information</a></li>
            <li><a href="#" class="sidebar-link" data-bs-target="#collapseProjectOverview">Project Overview</a></li>
            <li><a href="#" class="sidebar-link" data-bs-target="#collapseSiteAssessment">Site Assessment</a></li>
            <li><a href="#" class="sidebar-link" data-bs-target="#collapseClientRequirements">Client Requirements</a></li>
            <li><a href="#" class="sidebar-link" data-bs-target="#collapseProjectTimeline">Project Timeline</a></li>
            <li><a href="#" class="sidebar-link" data-bs-target="#collapseHealthSafety">Health and Safety</a></li>
            <li><a href="#" class="sidebar-link" data-bs-target="#collapseAdditionalInfo">Additional Information</a></li>
            <li><a href="#" class="sidebar-link" data-bs-target="#collapseSignatures">Signatures</a></li>
        </ul>
    </nav>

    <!-- Accordion for Form Sections -->
    <div class="form-content">
        <div class="accordion" id="siteSurveyAccordion">
            <!-- Basic Information -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingBasicInfo">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBasicInfo" aria-expanded="true" aria-controls="collapseBasicInfo">
                        <i class="fas fa-info-circle me-2"></i>Basic Information
                    </button>
                </h2>
                <div id="collapseBasicInfo" class="accordion-collapse collapse show" aria-labelledby="headingBasicInfo" data-bs-parent="#siteSurveyAccordion">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="site_visit_date" class="form-label required-field">
                                        <i class="far fa-calendar-alt me-2"></i>Site Visit Date
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                        <input type="text" 
                                               class="form-control datepicker @error('site_visit_date') is-invalid @enderror" 
                                               id="site_visit_date" 
                                               name="site_visit_date" 
                                               placeholder="Select visit date"
                                               data-date-format="Y-m-d"
                                               value="{{ $siteSurvey->exists && $siteSurvey->site_visit_date ? $siteSurvey->site_visit_date->format('Y-m-d') : old('site_visit_date', '') }}" 
                                               readonly
                                               required>
                                    </div>
                                    @error('site_visit_date')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="project_manager" class="form-label required-field">Project Officer</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                        <select class="form-select @error('project_manager') is-invalid @enderror" 
                                                id="project_manager" 
                                                name="project_manager" 
                                                required>
                                            <option value="">Select Project Officer</option>
                                            @if(isset($teamMembers) && $teamMembers->count() > 0)
                                                @foreach($teamMembers as $member)
                                                    <option value="{{ $member->name }}" 
                                                        {{ old('project_manager', $siteSurvey->project_manager) == $member->name ? 'selected' : '' }}>
                                                        {{ $member->name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                            <option value="Other" {{ old('project_manager', $siteSurvey->project_manager) == 'Other' ? 'selected' : '' }}>Other (specify)</option>
                                        </select>
                                    </div>
                                    <div id="otherProjectManagerContainer" class="mt-2" style="display: none;">
                                        <input type="text" 
                                               class="form-control mt-2" 
                                               name="other_project_manager" 
                                               placeholder="Enter project officer's name"
                                               value="{{ old('other_project_manager', $siteSurvey->other_project_manager ?? '') }}">
                                    </div>
                                    @error('project_manager')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="conducted_by" class="form-label required-field">Conducted By</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-check"></i></span>
                                        <input type="text" 
                                               class="form-control @error('conducted_by') is-invalid @enderror" 
                                               id="conducted_by" 
                                               name="conducted_by" 
                                               placeholder="Name of person conducting survey"
                                               value="{{ old('conducted_by', auth()->user()->name) }}" 
                                               required>
                                    </div>
                                    @error('conducted_by')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="client_name" class="form-label required-field">Client Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                        <input type="text" 
                                               class="form-control @error('client_name') is-invalid @enderror" 
                                               id="client_name" 
                                               name="client_name" 
                                               placeholder="Enter client's name"
                                               value="{{ isset($enquiry) ? $enquiry->client_name : ($project->client_name ?? '') }}" 
                                               required>
                                    </div>
                                    @error('client_name')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="location" class="form-label required-field">Location</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input type="text" 
                                               class="form-control @error('location') is-invalid @enderror" 
                                               id="location" 
                                               name="location" 
                                               placeholder="Enter site location"
                                               value="{{ isset($enquiry) ? $enquiry->venue : $project->venue }}" 
                                               required>
                                    </div>
                                    @error('location')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group mb-4">
                                    <label class="form-label fw-semibold d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-users me-1 text-primary"></i> Attendees</span>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="addAttendeeBtn">
                                            <i class="fas fa-plus me-1"></i> Add Attendee
                                        </button>
                                    </label>
                                    
                                    <div id="attendeesContainer" class="mb-3">
                                        @php
                                            $attendees = old('attendees', $siteSurvey->exists ? (is_array($siteSurvey->attendees) ? $siteSurvey->attendees : [$siteSurvey->attendees]) : ['']);
                                        @endphp
                                        
                                        @foreach($attendees as $index => $attendee)
                                            <div class="input-group mb-2 attendee-row">
                                                <span class="input-group-text">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                                <input type="text" 
                                                       class="form-control" 
                                                       name="attendees[]" 
                                                       value="{{ $attendee }}" 
                                                       placeholder="Attendee name">
                                                <button type="button" class="btn btn-outline-danger remove-attendee" {{ $loop->first && count($attendees) === 1 ? 'disabled' : '' }}>
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    @error('attendees')
                                        <div class="invalid-feedback d-flex align-items-center">
                                            <i class="fas fa-exclamation-circle me-2"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingContactInfo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseContactInfo" aria-expanded="false" aria-controls="collapseContactInfo">
                        <i class="fas fa-address-card me-2"></i>Contact Information
                    </button>
                </h2>
                <div id="collapseContactInfo" class="accordion-collapse collapse" aria-labelledby="headingContactInfo" data-bs-parent="#siteSurveyAccordion">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="client_contact_person" class="form-label">Contact Person</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" 
                                               class="form-control @error('client_contact_person') is-invalid @enderror" 
                                               id="client_contact_person" 
                                               name="client_contact_person" 
                                               placeholder="Contact person's name"
                                               value="{{ old('client_contact_person', $siteSurvey->client_contact_person) }}">
                                    </div>
                                    @error('client_contact_person')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="client_phone" class="form-label">Phone</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="text" 
                                               class="form-control @error('client_phone') is-invalid @enderror" 
                                               id="client_phone" 
                                               name="client_phone" 
                                               placeholder="Contact phone number"
                                               data-inputmask="'mask': '+255 999 999 999'"
                                               value="{{ old('client_phone', $siteSurvey->client_phone ?? (isset($enquiry) ? '' : ($project->client->Phone ?? ''))) }}">
                                    </div>
                                    @error('client_phone')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="client_email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" 
                                               class="form-control @error('client_email') is-invalid @enderror" 
                                               id="client_email" 
                                               name="client_email" 
                                               placeholder="Contact email address"
                                               value="{{ old('client_email', $siteSurvey->client_email ?? (isset($enquiry) ? '' : ($project->client->Email ?? ''))) }}">
                                    </div>
                                    @error('client_email')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Overview -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingProjectOverview">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProjectOverview" aria-expanded="false" aria-controls="collapseProjectOverview">
                        <i class="fas fa-project-diagram me-2"></i>Project Overview
                    </button>
                </h2>
                <div id="collapseProjectOverview" class="accordion-collapse collapse" aria-labelledby="headingProjectOverview" data-bs-parent="#siteSurveyAccordion">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="project_description" class="form-label">
                                        <i class="fas fa-align-left me-2"></i>Project Description
                                    </label>
                                    <div class="input-group">
                                        <textarea class="form-control @error('project_description') is-invalid @enderror" 
                                                 id="project_description" 
                                                 name="project_description" 
                                                 rows="4"
                                                 placeholder="Provide a detailed description of the project...">{{ old('project_description', $siteSurvey->project_description ?? '') }}</textarea>
                                    </div>
                                    @error('project_description')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                    <small class="form-text text-muted">Briefly describe the project scope and purpose</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="objectives" class="form-label">
                                        <i class="fas fa-bullseye me-2"></i>Project Objectives
                                    </label>
                                    <div class="input-group">
                                        <textarea class="form-control @error('objectives') is-invalid @enderror" 
                                                 id="objectives" 
                                                 name="objectives" 
                                                 rows="4"
                                                 placeholder="List the main objectives of this project...">{{ old('objectives', $siteSurvey->objectives ?? '') }}</textarea>
                                    </div>
                                    @error('objectives')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                    <small class="form-text text-muted">List the key objectives to be achieved (one per line)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Site Assessment -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSiteAssessment">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSiteAssessment" aria-expanded="false" aria-controls="collapseSiteAssessment">
                        <i class="fas fa-clipboard-check me-2"></i>Site Assessment
                    </button>
                </h2>
                <div id="collapseSiteAssessment" class="accordion-collapse collapse" aria-labelledby="headingSiteAssessment" data-bs-parent="#siteSurveyAccordion">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="current_condition" class="form-label">
                                        <i class="fas fa-clipboard-check me-2"></i>Current Condition
                                    </label>
                                    <div class="input-group">
                                        <textarea class="form-control @error('current_condition') is-invalid @enderror" 
                                                 id="current_condition" 
                                                 name="current_condition" 
                                                 rows="3"
                                                 placeholder="Describe the current condition of the site...">{{ old('current_condition', $siteSurvey->current_condition ?? '') }}</textarea>
                                    </div>
                                    @error('current_condition')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="existing_branding" class="form-label">
                                        <i class="fas fa-tags me-2"></i>Existing Branding
                                    </label>
                                    <div class="input-group">
                                        <textarea class="form-control @error('existing_branding') is-invalid @enderror" 
                                                 id="existing_branding" 
                                                 name="existing_branding" 
                                                 rows="3"
                                                 placeholder="Note any existing branding elements...">{{ old('existing_branding', $siteSurvey->existing_branding ?? '') }}</textarea>
                                    </div>
                                    @error('existing_branding')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="access_logistics" class="form-label">
                                        <i class="fas fa-truck-loading me-2"></i>Access & Logistics
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                        <textarea class="form-control @error('access_logistics') is-invalid @enderror" 
                                                 id="access_logistics" 
                                                 name="access_logistics" 
                                                 rows="2"
                                                 placeholder="Describe site access and logistics considerations...">{{ old('access_logistics', $siteSurvey->access_logistics ?? '') }}</textarea>
                                    </div>
                                    @error('access_logistics')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="parking_availability" class="form-label">
                                        <i class="fas fa-parking me-2"></i>Parking Availability
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-car"></i></span>
                                        <input type="text" 
                                               class="form-control @error('parking_availability') is-invalid @enderror" 
                                               id="parking_availability" 
                                               name="parking_availability" 
                                               placeholder="Available parking spaces"
                                               value="{{ getFormValue('parking_availability', $siteSurvey) }}">
                                    </div>
                                    @error('parking_availability')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="lifts" class="form-label">
                                        <i class="fas fa-elevator me-2"></i>Lifts
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-arrow-up-arrow-down"></i></span>
                                        <input type="text" 
                                               class="form-control @error('lifts') is-invalid @enderror" 
                                               id="lifts" 
                                               name="lifts" 
                                               placeholder="Lift availability and capacity"
                                               value="{{ getFormValue('lifts', $siteSurvey) }}">
                                    </div>
                                    @error('lifts')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="door_sizes" class="form-label">
                                        <i class="fas fa-door-open me-2"></i>Door Sizes
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-arrows-alt-h"></i></span>
                                        <input type="text" 
                                               class="form-control @error('door_sizes') is-invalid @enderror" 
                                               id="door_sizes" 
                                               name="door_sizes" 
                                               placeholder="Door dimensions and clearances"
                                               value="{{ old('door_sizes', $siteSurvey->door_sizes ?? '') }}">
                                    </div>
                                    @error('door_sizes')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="loading_areas" class="form-label">
                                        <i class="fas fa-truck-loading me-2"></i>Loading Areas
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-warehouse"></i></span>
                                        <input type="text" 
                                               class="form-control @error('loading_areas') is-invalid @enderror" 
                                               id="loading_areas" 
                                               name="loading_areas" 
                                               placeholder="Loading dock and access points"
                                               value="{{ old('loading_areas', $siteSurvey->loading_areas ?? '') }}">
                                    </div>
                                    @error('loading_areas')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="site_measurements" class="form-label">
                                        <i class="fas fa-ruler-combined me-2"></i>Site Measurements
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-ruler"></i></span>
                                        <textarea class="form-control @error('site_measurements') is-invalid @enderror" 
                                                 id="site_measurements" 
                                                 name="site_measurements" 
                                                 rows="3"
                                                 placeholder="Detailed site measurements and dimensions">{{ old('site_measurements', $siteSurvey->site_measurements ?? '') }}</textarea>
                                    </div>
                                    @error('site_measurements')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="room_size" class="form-label">
                                        <i class="fas fa-vector-square me-2"></i>Room Size
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-ruler-combined"></i></span>
                                        <input type="text" 
                                               class="form-control @error('room_size') is-invalid @enderror" 
                                               id="room_size" 
                                               name="room_size" 
                                               placeholder="Room dimensions (L x W x H)"
                                               value="{{ old('room_size', $siteSurvey->room_size ?? '') }}">
                                    </div>
                                    @error('room_size')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="electrical_outlets" class="form-label">
                                        <i class="fas fa-plug me-2"></i>Electrical Outlets
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-bolt"></i></span>
                                        <input type="text" 
                                               class="form-control @error('electrical_outlets') is-invalid @enderror" 
                                               id="electrical_outlets" 
                                               name="electrical_outlets" 
                                               placeholder="Number and location of outlets"
                                               value="{{ old('electrical_outlets', $siteSurvey->electrical_outlets ?? '') }}">
                                    </div>
                                    @error('electrical_outlets')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="constraints" class="form-label">
                                        <i class="fas fa-exclamation-triangle me-2"></i>Constraints
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-ban"></i></span>
                                        <textarea class="form-control @error('constraints') is-invalid @enderror" 
                                                 id="constraints" 
                                                 name="constraints" 
                                                 rows="3"
                                                 placeholder="Any site limitations or restrictions...">{{ getFormValue('constraints', $siteSurvey) }}</textarea>
                                    </div>
                                    @error('constraints')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label for="food_refreshment" class="form-label">
                                        <i class="fas fa-utensils me-2"></i>Food & Refreshment
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-coffee"></i></span>
                                        <textarea class="form-control @error('food_refreshment') is-invalid @enderror" 
                                                 id="food_refreshment" 
                                                 name="food_refreshment" 
                                                 rows="3"
                                                 placeholder="Catering and refreshment arrangements...">{{ getFormValue('food_refreshment', $siteSurvey) }}</textarea>
                                    </div>
                                    @error('food_refreshment')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Client Requirements -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingClientRequirements">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseClientRequirements" aria-expanded="false" aria-controls="collapseClientRequirements">
                        <i class="fas fa-clipboard-list me-2"></i>Client Requirements
                    </button>
                </h2>
                <div id="collapseClientRequirements" class="accordion-collapse collapse" aria-labelledby="headingClientRequirements" data-bs-parent="#siteSurveyAccordion">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="branding_preferences" class="form-label">
                                        <i class="fas fa-palette me-2"></i>Branding Preferences
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                        <textarea class="form-control @error('branding_preferences') is-invalid @enderror" 
                                                 id="branding_preferences" 
                                                 name="branding_preferences" 
                                                 rows="3"
                                                 placeholder="Client's branding requirements...">{{ getFormValue('branding_preferences', $siteSurvey) }}</textarea>
                                    </div>
                                    @error('branding_preferences')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="form-group mb-4">
                                    <label for="material_preferred" class="form-label">
                                        <i class="fas fa-cubes me-2"></i>Preferred Materials
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-boxes"></i></span>
                                        <textarea class="form-control @error('material_preferred') is-invalid @enderror" 
                                                 id="material_preferred" 
                                                 name="material_preferred" 
                                                 rows="3"
                                                 placeholder="Preferred materials and finishes...">{{ getFormValue('material_preferred', $siteSurvey) }}</textarea>
                                    </div>
                                    @error('material_preferred')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="color_scheme" class="form-label">
                                        <i class="fas fa-fill-drip me-2"></i>Color Scheme
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-swatchbook"></i></span>
                                        <input type="text" 
                                               class="form-control color-picker @error('color_scheme') is-invalid @enderror" 
                                               id="color_scheme" 
                                               name="color_scheme" 
                                               placeholder="e.g., #0BADD3, #6E6F71, #C8DA30"
                                               value="{{ getFormValue('color_scheme', $siteSurvey) }}">
                                    </div>
                                    @error('color_scheme')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="form-group mb-4">
                                    <label for="brand_guidelines" class="form-label">
                                        <i class="fas fa-book me-2"></i>Brand Guidelines
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                        <textarea class="form-control @error('brand_guidelines') is-invalid @enderror" 
                                                 id="brand_guidelines" 
                                                 name="brand_guidelines" 
                                                 rows="3"
                                                 placeholder="Link to or describe brand guidelines...">{{ getFormValue('brand_guidelines', $siteSurvey) }}</textarea>
                                    </div>
                                    @error('brand_guidelines')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="special_instructions" class="form-label">
                                <i class="fas fa-clipboard-list me-2"></i>Special Instructions
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                <textarea class="form-control @error('special_instructions') is-invalid @enderror" 
                                         id="special_instructions" 
                                         name="special_instructions" 
                                         rows="3"
                                         placeholder="Any additional special instructions...">{{ getFormValue('special_instructions', $siteSurvey) }}</textarea>
                            </div>
                            @error('special_instructions')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Timeline -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingProjectTimeline">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProjectTimeline" aria-expanded="false" aria-controls="collapseProjectTimeline">
                        <i class="far fa-calendar-alt me-2"></i>Project Timeline
                    </button>
                </h2>
                <div id="collapseProjectTimeline" class="accordion-collapse collapse" aria-labelledby="headingProjectTimeline" data-bs-parent="#siteSurveyAccordion">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="proposed_start_date" class="form-label">
                                        <i class="far fa-calendar-alt me-2"></i>Proposed Start Date
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                        <input type="text" 
                                               class="form-control datepicker @error('proposed_start_date') is-invalid @enderror" 
                                               id="proposed_start_date" 
                                               name="proposed_start_date" 
                                               placeholder="Select start date"
                                               data-date-format="Y-m-d"
                                               value="{{ getFormValue('proposed_start_date', $siteSurvey) }}"
                                               readonly>
                                    </div>
                                    @error('proposed_start_date')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="proposed_end_date" class="form-label">
                                        <i class="far fa-calendar-check me-2"></i>Proposed End Date
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                        <input type="text" 
                                               class="form-control datepicker @error('proposed_end_date') is-invalid @enderror" 
                                               id="proposed_end_date" 
                                               name="proposed_end_date" 
                                               placeholder="Select end date"
                                               data-date-format="Y-m-d"
                                               value="{{ getFormValue('proposed_end_date', $siteSurvey) }}"
                                               readonly>
                                    </div>
                                    @error('proposed_end_date')
                                        <div class="invalid-feedback d-block">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="timeline_notes" class="form-label">
                                <i class="fas fa-sticky-note me-2"></i>Timeline Notes
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                                <textarea class="form-control @error('timeline_notes') is-invalid @enderror" 
                                         id="timeline_notes" 
                                         name="timeline_notes" 
                                         rows="3"
                                         placeholder="Important notes about the project timeline...">{{ getFormValue('timeline_notes', $siteSurvey) }}</textarea>
                            </div>
                            @error('timeline_notes')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Health and Safety -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingHealthSafety">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHealthSafety" aria-expanded="false" aria-controls="collapseHealthSafety">
                        <i class="fas fa-shield-alt me-2"></i>Health and Safety
                    </button>
                </h2>
                <div id="collapseHealthSafety" class="accordion-collapse collapse" aria-labelledby="headingHealthSafety" data-bs-parent="#siteSurveyAccordion">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="safety_conditions">Safety Conditions</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-exclamation-triangle"></i></span>
                                        <textarea class="form-control @error('safety_conditions') is-invalid @enderror" 
                                                 id="safety_conditions" 
                                                 name="safety_conditions" 
                                                 rows="3"
                                                 placeholder="Describe safety conditions at the site...">{{ getFormValue('safety_conditions', $siteSurvey) }}</textarea>
                                    </div>
                                    @error('safety_conditions')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="potential_hazards">Potential Hazards</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-radiation"></i></span>
                                        <textarea class="form-control @error('potential_hazards') is-invalid @enderror" 
                                                 id="potential_hazards" 
                                                 name="potential_hazards" 
                                                 rows="3"
                                                 placeholder="List any potential hazards...">{{ getFormValue('potential_hazards', $siteSurvey) }}</textarea>
                                    </div>
                                    @error('potential_hazards')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="safety_required">Safety Equipment Required</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-hard-hat"></i></span>
                                        <textarea class="form-control @error('safety_required') is-invalid @enderror" 
                                                 id="safety_required" 
                                                 name="safety_required" 
                                                 rows="3"
                                                 placeholder="List required safety equipment...">{{ getFormValue('safety_required', $siteSurvey) }}</textarea>
                                    </div>
                                    @error('safety_required')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingAdditionalInfo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdditionalInfo" aria-expanded="false" aria-controls="collapseAdditionalInfo">
                        <i class="fas fa-info-circle me-2"></i>Additional Information
                    </button>
                </h2>
                <div id="collapseAdditionalInfo" class="accordion-collapse collapse" aria-labelledby="headingAdditionalInfo" data-bs-parent="#siteSurveyAccordion">
                    <div class="accordion-body">
                        <div class="form-group mb-4">
                            <label for="additional_notes" class="form-label">
                                <i class="fas fa-notes-medical me-2"></i>Additional Notes
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
                                <textarea class="form-control @error('additional_notes') is-invalid @enderror" 
                                         id="additional_notes" 
                                         name="additional_notes" 
                                         rows="4"
                                         placeholder="Any other relevant information or special instructions...">{{ getFormValue('additional_notes', $siteSurvey) }}</textarea>
                            </div>
                            @error('additional_notes')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="attachments" class="form-label">
                                <i class="fas fa-paperclip me-2"></i>Attachments
                            </label>
                            <div class="file-upload-wrapper">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-cloud-upload-alt"></i></span>
                                    <div class="custom-file">
                                        <input type="file" 
                                               class="form-control @error('attachments') is-invalid @enderror" 
                                               id="attachments" 
                                               name="attachments[]" 
                                               multiple
                                               data-max-size="10240"
                                               data-allowed-extensions="jpg,jpeg,png,pdf,doc,docx,xls,xlsx">
                                        <label class="custom-file-label" for="attachments">
                                            <i class="fas fa-upload me-2"></i>Choose files...
                                        </label>
                                    </div>
                                </div>
                                <div class="file-upload-info small text-muted mt-2">
                                    <i class="fas fa-info-circle me-1"></i> Maximum file size: 10MB. Allowed formats: JPG, PNG, PDF, DOC, XLS
                                </div>
                                @error('attachments')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            @if(isset($siteSurvey) && $siteSurvey->attachments->count() > 0)
                                <div class="attachments-preview mt-3">
                                    <h6 class="mb-3"><i class="fas fa-paperclip me-2"></i>Current Attachments</h6>
                                    <div class="row">
                                        @foreach($siteSurvey->attachments as $attachment)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card border-0 shadow-sm h-100">
                                                    <div class="card-body p-2">
                                                        <div class="d-flex align-items-center">
                                                            <div class="file-icon me-3">
                                                                @if(in_array($attachment->extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                                    <i class="fas fa-file-image text-primary" style="font-size: 2rem;"></i>
                                                                @elseif($attachment->extension === 'pdf')
                                                                    <i class="fas fa-file-pdf text-danger" style="font-size: 2rem;"></i>
                                                                @elseif(in_array($attachment->extension, ['doc', 'docx']))
                                                                    <i class="fas fa-file-word text-primary" style="font-size: 2rem;"></i>
                                                                @elseif(in_array($attachment->extension, ['xls', 'xlsx']))
                                                                    <i class="fas fa-file-excel text-success" style="font-size: 2rem;"></i>
                                                                @else
                                                                    <i class="fas fa-file-alt text-secondary" style="font-size: 2rem;"></i>
                                                                @endif
                                                            </div>
                                                            <div class="file-info flex-grow-1">
                                                                <div class="file-name text-truncate" style="max-width: 200px;" title="{{ $attachment->file_name }}">
                                                                    {{ $attachment->file_name }}
                                                                </div>
                                                                <div class="file-size small text-muted">
                                                                    {{ number_format($attachment->size / 1024, 1) }} KB
                                                                </div>
                                                            </div>
                                                            <div class="file-actions ms-2">
                                                                <a href="{{ $attachment->getUrl() }}" 
                                                                   class="btn btn-sm btn-outline-primary" 
                                                                   target="_blank"
                                                                   title="View">
                                                                    <i class="fas fa-eye"></i>
                                                                </a>
                                                                <a href="{{ $attachment->getUrl() }}" 
                                                                   class="btn btn-sm btn-outline-secondary" 
                                                                   download
                                                                   title="Download">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <label for="special_requests">Special Requests</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-star"></i></span>
                                <textarea class="form-control @error('special_requests') is-invalid @enderror" 
                                         id="special_requests" 
                                         name="special_requests" 
                                         rows="3"
                                         placeholder="Any special requests or requirements...">{{ getFormValue('special_requests', $siteSurvey) }}</textarea>
                            </div>
                            @error('special_requests')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-4">
                            <label class="form-label fw-semibold d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-tasks me-1 text-primary"></i> Action Items</span>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="addActionItemBtn">
                                    <i class="fas fa-plus me-1"></i> Add Action Item
                                </button>
                            </label>
                            
                            <div id="actionItemsContainer" class="mb-3">
                                @php
                                    $actionItems = old('action_items', $siteSurvey->exists ? (is_array($siteSurvey->action_items) ? $siteSurvey->action_items : [$siteSurvey->action_items]) : ['']);
                                @endphp
                                
                                @foreach($actionItems as $index => $item)
                                    <div class="input-group mb-2 action-item-row">
                                        <span class="input-group-text">
                                            <i class="fas fa-tasks"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control" 
                                               name="action_items[]" 
                                               value="{{ $item }}" 
                                               placeholder="Enter action item">
                                        <button type="button" class="btn btn-outline-danger remove-action-item" {{ $loop->first && count($actionItems) === 1 ? 'disabled' : '' }}>
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            
                            @error('action_items')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Signatures -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSignatures">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSignatures" aria-expanded="false" aria-controls="collapseSignatures">
                        <i class="fas fa-signature me-2"></i>Signatures
                    </button>
                </h2>
                <div id="collapseSignatures" class="accordion-collapse collapse" aria-labelledby="headingSignatures" data-bs-parent="#siteSurveyAccordion">
                    <div class="accordion-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Prepared By</h6>
                                <div class="form-group">
                                    <label for="prepared_by">Name <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-edit"></i></span>
                                        <input type="text" class="form-control @error('prepared_by') is-invalid @enderror" 
                                               id="prepared_by" 
                                               name="prepared_by" 
                                               value="{{ getFormValue('prepared_by', $siteSurvey, auth()->user()->name) }}" 
                                               placeholder="Enter your name"
                                               required>
                                    </div>
                                    @error('prepared_by')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h6>Client Approval</h6>
                                <div class="form-group">
                                    <label for="client_approval_name">Name</label>
                                    <input type="text" class="form-control @error('client_approval_name') is-invalid @enderror" 
                                           id="client_approval_name" name="client_approval_name" 
                                           value="{{ getFormValue('client_approval_name', $siteSurvey, auth()->user()->name) }}">
                                    @error('client_approval_name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scrolling for sidebar navigation
        document.querySelectorAll('.sidebar-nav a').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });

                // Update active class
                document.querySelectorAll('.sidebar-nav a').forEach(link => link.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Highlight active sidebar link on scroll
        const sections = document.querySelectorAll('.form-section-card');
        const navLinks = document.querySelectorAll('.sidebar-nav a');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= sectionTop - 100) { // Adjust offset as needed
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href').includes(current)) {
                    link.classList.add('active');
                }
            });
        });

        // Initialize date pickers
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            orientation: 'bottom auto',
            clearBtn: true,
            todayBtn: 'linked',
            autoclose: true
        });

        // Initialize select2 for attendees and action items
        $('.select2').select2({
            tags: true,
            tokenSeparators: [',', ' '],
            placeholder: 'Type and press Enter to add',
            allowClear: true
        });

        // Set default dates if not set
        $('#site_visit_date').val('{{ old('site_visit_date', $siteSurvey->site_visit_date ? $siteSurvey->site_visit_date->format('Y-m-d') : now()->format('Y-m-d')) }}');
        $('#prepared_date').val('{{ old('prepared_date', $siteSurvey->prepared_date ? $siteSurvey->prepared_date->format('Y-m-d') : now()->format('Y-m-d')) }}');

        // Handle project manager dropdown change
        $('#project_manager').on('change', function() {
            const selectedValue = $(this).val();
            const otherContainer = $('#otherProjectManagerContainer');
            
            if (selectedValue === 'Other') {
                otherContainer.show();
                otherContainer.find('input').attr('required', true);
            } else {
                otherContainer.hide();
                otherContainer.find('input').attr('required', false);
            }
        });
        
        // Trigger change event on page load in case 'Other' is selected
        $('#project_manager').trigger('change');
        
        // If there's a validation error, ensure the other field is shown if needed
        @if(old('project_manager') === 'Other' || ($siteSurvey->exists && $siteSurvey->project_manager === 'Other'))
            $('#otherProjectManagerContainer').show();
        @endif

        // Add attendee
        document.getElementById('addAttendeeBtn').addEventListener('click', function() {
            const container = document.getElementById('attendeesContainer');
            const newRow = document.createElement('div');
            newRow.className = 'input-group mb-2 attendee-row';
            newRow.innerHTML = `
                <span class="input-group-text">
                    <i class="fas fa-user"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       name="attendees[]" 
                       placeholder="Attendee name">
                <button type="button" class="btn btn-outline-danger remove-attendee">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(newRow);
            updateRemoveButtons('attendee');
            newRow.querySelector('input').focus();
        });
        
        // Remove attendee
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-attendee')) {
                const row = e.target.closest('.attendee-row');
                if (row) {
                    row.remove();
                    updateRemoveButtons('attendee');
                }
            }
        });

        // Add action item
        document.getElementById('addActionItemBtn').addEventListener('click', function() {
            const container = document.getElementById('actionItemsContainer');
            const newRow = document.createElement('div');
            newRow.className = 'input-group mb-2 action-item-row';
            newRow.innerHTML = `
                <span class="input-group-text">
                    <i class="fas fa-tasks"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       name="action_items[]" 
                       placeholder="Enter action item">
                <button type="button" class="btn btn-outline-danger remove-action-item">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(newRow);
            updateRemoveButtons('action-item');
            newRow.querySelector('input').focus();
        });
        
        // Remove action item
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-action-item')) {
                const row = e.target.closest('.action-item-row');
                if (row) {
                    row.remove();
                    updateRemoveButtons('action-item');
                }
            }
        });

        function updateRemoveButtons(type) {
            const rows = document.querySelectorAll(`.${type}-row`);
            rows.forEach((row, index) => {
                const removeBtn = row.querySelector(`.remove-${type}`);
                if (removeBtn) {
                    removeBtn.disabled = rows.length === 1;
                }
            });
        }

        // Initial call to set button states
        updateRemoveButtons('attendee');
        updateRemoveButtons('action-item');

        // File input label update
        document.getElementById('attachments').addEventListener('change', function() {
            const files = this.files;
            let label = 'Choose files...';
            if (files.length > 0) {
                label = Array.from(files).map(file => file.name).join(', ');
            }
            this.nextElementSibling.textContent = label;
        });

        // Signature Pad (if needed, ensure canvas elements are present)
        const signaturePads = {};
        document.querySelectorAll('.signature-pad').forEach(canvas => {
            const inputName = canvas.dataset.input;
            const hiddenInput = document.querySelector(`input[name="${inputName}"]`);
            
            signaturePads[inputName] = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)'
            });

            // Load existing signature if any
            if (hiddenInput && hiddenInput.value) {
                signaturePads[inputName].fromDataURL(hiddenInput.value);
            }

            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
                if (hiddenInput && hiddenInput.value) {
                    signaturePads[inputName].fromDataURL(hiddenInput.value);
                } else {
                    signaturePads[inputName].clear();
                }
            }
            window.addEventListener("resize", resizeCanvas);
            resizeCanvas();

            // Save signature to hidden input on end of stroke
            canvas.addEventListener('mouseup', () => {
                if (hiddenInput) {
                    hiddenInput.value = signaturePads[inputName].toDataURL();
                }
            });
            canvas.addEventListener('touchend', () => {
                if (hiddenInput) {
                    hiddenInput.value = signaturePads[inputName].toDataURL();
                }
            });
        });

        // Clear signature button
        document.querySelectorAll('.clear-signature').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const targetInputName = this.dataset.targetInput;
                if (signaturePads[targetInputName]) {
                    signaturePads[targetInputName].clear();
                    document.querySelector(`input[name="${targetInputName}"]`).value = '';
                }
            });
        });

        // Handle form submission for signatures
        document.querySelector('form').addEventListener('submit', function() {
            for (const inputName in signaturePads) {
                const hiddenInput = document.querySelector(`input[name="${inputName}"]`);
                if (hiddenInput && signaturePads[inputName] && !signaturePads[inputName].isEmpty()) {
                    hiddenInput.value = signaturePads[inputName].toDataURL();
                }
            }
        });

        // Sidebar links open accordion and smooth scroll
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                // Remove active from all links
                document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                // Open the corresponding accordion
                const target = document.querySelector(this.dataset.bsTarget);
                if (target) {
                    // Use Bootstrap's Collapse API
                    const bsCollapse = bootstrap.Collapse.getOrCreateInstance(target, {toggle: false});
                    bsCollapse.show();
                    // Smooth scroll to the accordion header
                    const header = target.previousElementSibling;
                    if (header) {
                        header.scrollIntoView({behavior: 'smooth', block: 'start'});
                    } else {
                        target.scrollIntoView({behavior: 'smooth', block: 'start'});
                    }
                }
            });
        });
    });
</script>
@endpush