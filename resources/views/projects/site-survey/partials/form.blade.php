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
    
    /* Smooth scrolling for form sections */
    html {
        scroll-behavior: smooth;
    }

    .card {
        border: none;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        margin-bottom: 2rem;
        overflow: hidden;
        transition: var(--transition);
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary-color), #0897c4);
        color: white;
        padding: 1.25rem 1.5rem;
        border-bottom: none;
    }

    .card-header h5 {
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
    }

    .card-header h5:before {
        content: '';
        display: inline-block;
        width: 8px;
        height: 20px;
        background: var(--accent-color);
        margin-right: 12px;
        border-radius: 4px;
    }

    .card-body {
        padding: 2rem;
        background: white;
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
    
    /* Improved input group styling */
    .input-group-text {
        background-color: #f8f9fa;
        border-color: #e2e8f0;
        color: var(--secondary-color);
    }
    
    /* Better spacing for form sections */
    .card {
        margin-bottom: var(--form-section-spacing);
    }
    
    /* Required field indicator */
    .required-field::after {
        content: '*';
        color: #dc3545;
        margin-left: 4px;
    }
    
    /* Form section headers */
    .card-header h5 {
        font-size: 1.25rem;
        font-weight: 600;
    }
    
    /* Help text styling */
    .form-text {
        color: #6c757d;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }
    
    /* Improved buttons */
    .btn {
        font-weight: 500;
        padding: 0.6rem 1.5rem;
        border-radius: 6px;
        transition: all 0.2s ease-in-out;
    }
    
    .btn-submit {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        min-width: 150px;
    }
    
    .btn-submit:hover {
        background-color: #0a9cbf;
        border-color: #0a9cbf;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(11, 173, 211, 0.3);
    }
    
    /* Form progress indicator */
    .form-progress {
        position: sticky;
        top: 1rem;
        z-index: 1000;
        margin-bottom: 2rem;
    }

    .form-control:focus, .form-select:focus, .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(11, 173, 211, 0.2);
    }

    .form-control::placeholder {
        color: #94a3b8;
        opacity: 0.7;
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

    .section-divider {
        border-top: 2px dashed #e2e8f0;
        margin: 2rem 0;
        position: relative;
    }

    .section-divider:after {
        content: '';
        position: absolute;
        top: -7px;
        left: 0;
        width: 14px;
        height: 14px;
        background: var(--accent-color);
        transform: rotate(45deg);
    }

    .required-field:after {
        content: ' *';
        color: #ef4444;
    }


    .form-text {
        font-size: 0.85rem;
        color: #64748b;
    }

    .invalid-feedback {
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }

    .form-section {
        margin-bottom: 2.5rem;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem;
        }
        .btn-primary {
            width: 100%;
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

<!-- Basic Information -->
<div class="card mb-4">
    <div class="card-header">
        <h5><i class="fas fa-info-circle me-2"></i>Basic Information</h5>
    </div>
    <div class="card-body">
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
                    <label for="project_manager" class="form-label required-field">Project Manager</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                        <input type="text" 
                               class="form-control @error('project_manager') is-invalid @enderror" 
                               id="project_manager" 
                               name="project_manager" 
                               value="{{ getFormValue('project_manager', $siteSurvey) }}"
                               placeholder="Project manager's name"
                               required>
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
                    <label for="client_name" class="form-label required-field">Client Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                        <input type="text" 
                               class="form-control @error('client_name') is-invalid @enderror" 
                               id="client_name" 
                               name="client_name" 
                               placeholder="Enter client's name"
                               value="{{ getFormValue('client_name', $siteSurvey) }}" 
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
                               value="{{ getFormValue('location', $siteSurvey) }}" 
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

            @push('scripts')
            <script>
            document.addEventListener('DOMContentLoaded', function() {
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
                    
                    // Enable all remove buttons if there's more than one row
                    updateRemoveButtons();
                });
                
                // Remove attendee
                document.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-attendee')) {
                        const row = e.target.closest('.attendee-row');
                        if (row && document.querySelectorAll('.attendee-row').length > 1) {
                            row.remove();
                            updateRemoveButtons();
                        }
                    }
                });
                
                function updateRemoveButtons() {
                    const removeButtons = document.querySelectorAll('.remove-attendee');
                    const hasSingleRow = document.querySelectorAll('.attendee-row').length === 1;
                    removeButtons.forEach(btn => {
                        btn.disabled = hasSingleRow;
                    });
                }
                // Search functionality
                $('#attendeeSearch').on('input', function() {
                    const searchTerm = $(this).val().toLowerCase();
                    $('.list-group-item').each(function() {
                        const text = $(this).text().toLowerCase();
                        $(this).toggle(text.includes(searchTerm));
                    });
                });

                // Save selected attendees
                $('#saveAttendees').on('click', function() {
                    const selectedIds = [];
                    const selectedNames = [];
                    
                    $('.attendee-checkbox:checked').each(function() {
                        const id = $(this).val();
                        const name = $(this).closest('.list-group-item').find('.form-check-label .fw-medium').text();
                        selectedIds.push(id);
                        selectedNames.push(name);
                    });

                    // Update hidden input
                    $('#attendeesInput').val(selectedIds.join(','));
                    
                    // Update display
                    const attendeesContainer = $('#selectedAttendees');
                    if (selectedNames.length > 0) {
                        let badges = '';
                        $('.attendee-checkbox:checked').each(function() {
                            const id = $(this).val();
                            const name = $(this).closest('.list-group-item').find('.form-check-label .fw-medium').text();
                            badges += `
                                <span class="badge bg-primary bg-opacity-10 text-primary p-2 d-flex align-items-center">
                                    <i class="fas fa-user me-1"></i>
                                    ${name}
                                    <button type="button" class="btn-close btn-close-attendee ms-2" data-user-id="${id}" style="font-size: 0.5rem;"></button>
                                </span>`;
                        });
                        attendeesContainer.html(`
                            <div class="d-flex flex-wrap gap-2 mb-2">${badges}</div>
                            <input type="hidden" name="attendees[]" id="attendeesInput" value="${selectedIds.join(',')}">
                        `);
                    } else {
                        attendeesContainer.html(`
                            <p class="text-muted mb-0">No attendees selected</p>
                            <input type="hidden" name="attendees[]" id="attendeesInput" value="">
                        `);
                    }
                    
                    // Rebind remove button events
                    bindRemoveAttendeeEvents();
                    
                    // Close offcanvas
                    const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('selectAttendeesOffcanvas'));
                    offcanvas.hide();
                });

                // Function to bind remove attendee events
                function bindRemoveAttendeeEvents() {
                    $('.btn-close-attendee').on('click', function() {
                        const userId = $(this).data('user-id');
                        const $checkbox = $(`#attendee-${userId}`);
                        
                        if ($checkbox.length) {
                            $checkbox.prop('checked', false);
                        }
                        
                        // Trigger save to update the display
                        $('#saveAttendees').click();
                    });
                }

                // Initial binding
                bindRemoveAttendeeEvents();
            });
            </script>
            <style>
                .offcanvas {
                    width: 400px;
                    max-width: 100%;
                }
                .list-group-item {
                    border-left: none;
                    border-right: none;
                    padding: 1rem 1.25rem;
                }
                .list-group-item:first-child {
                    border-top: none;
                }
                .list-group-item:last-child {
                    border-bottom: none;
                }
                .form-check-input:checked {
                    background-color: #0d6efd;
                    border-color: #0d6efd;
                }
                .form-check-input[type="checkbox"] {
                    width: 1.25em;
                    height: 1.25em;
                    margin-top: 0.15em;
                }
                .form-switch .form-check-input {
                    width: 2.5em;
                    margin-left: 0.5em;
                }
                .btn-close-attendee {
                    opacity: 0.5;
                }
                .btn-close-attendee:hover {
                    opacity: 1;
                }
                .offcanvas-body {
                    padding: 0;
                }
            </style>
            @endpush

        </div>
    </div>
</div>

<!-- Contact Information -->
<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-address-card me-2"></i>Contact Information</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group mb-4">
                    <label for="client_contact_person" class="form-label required-field">Contact Person</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" 
                               class="form-control @error('client_contact_person') is-invalid @enderror" 
                               id="client_contact_person" 
                               name="client_contact_person" 
                               placeholder="Contact person's name"
                               value="{{ getFormValue('client_contact_person', $siteSurvey) }}" 
                               required>
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
                    <label for="client_phone" class="form-label required-field">Phone</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        <input type="text" 
                               class="form-control @error('client_phone') is-invalid @enderror" 
                               id="client_phone" 
                               name="client_phone" 
                               placeholder="Contact phone number"
                               data-inputmask="'mask': '+255 999 999 999'"
                               value="{{ getFormValue('client_phone', $siteSurvey) }}" 
                               required>
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
                               placeholder="contact@example.com"
                               value="{{ getFormValue('client_email', $siteSurvey) }}">
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

<!-- Project Overview -->
<div class="card shadow-sm" id="project-overview">
    <div class="card-header">
        <h5><i class="fas fa-project-diagram me-2"></i>Project Overview</h5>
    </div>
    <div class="card-body">
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

<!-- Site Assessment -->
<div class="card shadow-sm" id="site-assessment">
    <div class="card-header">
        <h5><i class="fas fa-clipboard-check me-2"></i>Site Assessment</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
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
            
            <div class="col-md-6">
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
        </div>
        
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
        
        <div class="row">
            <div class="col-md-6">
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
            <div class="col-md-6">
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
        </div>
        
        <div class="row">
            <div class="col-md-6">
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
            <div class="col-md-6">
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
        </div>
        
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
        
        <div class="row">
            <div class="col-md-6">
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
            <div class="col-md-6">
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
        </div>
        
        <div class="row">
            <div class="col-md-6">
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
            <div class="col-md-6">
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

<!-- Client Requirements -->
<div class="card shadow-sm" id="client-requirements">
    <div class="card-header">
        <h5><i class="fas fa-clipboard-list me-2"></i>Client Requirements</h5>
    </div>
    <div class="card-body">
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

<!-- Project Timeline -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Project Timeline</h5>
    </div>
    <div class="card-body">
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

<!-- Health and Safety -->
<div class="card shadow-sm" id="health-safety">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Health and Safety</h5>
    </div>
    <div class="card-body">
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

<!-- Additional Information -->
<div class="card shadow-sm" id="additional-info">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Additional Information</h5>
    </div>
    <div class="card-body">
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
        
        @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                
                // Enable all remove buttons when adding a new row
                const removeButtons = document.querySelectorAll('.remove-action-item');
                removeButtons.forEach(btn => btn.disabled = false);
                
                // Focus the new input
                newRow.querySelector('input').focus();
            });
            
            // Remove action item
            document.addEventListener('click', function(e) {
                if (e.target.closest('.remove-action-item')) {
                    const row = e.target.closest('.action-item-row');
                    const container = document.getElementById('actionItemsContainer');
                    const rows = container.querySelectorAll('.action-item-row');
                    
                    if (rows.length > 1) {
                        row.remove();
                        
                        // If only one row left, disable its remove button
                        if (rows.length === 2) {
                            const remainingButton = container.querySelector('.remove-action-item');
                            if (remainingButton) remainingButton.disabled = true;
                        }
                    }
                }
            });
        });
        </script>
        @endpush
    </div>
</div>

<!-- Signatures -->
<div class="card shadow-sm" id="signatures">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-signature me-2"></i>Signatures</h5>
    </div>
    <div class="card-body">
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
