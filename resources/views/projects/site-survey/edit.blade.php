@extends('layouts.master')

@push('styles')
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
<!-- Bootstrap Datepicker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    :root {
        --primary-color: #0BADD3;
        --secondary-color: #6E6F71;
        --accent-color: #C8DA30;
        --light-bg: #f8fafc;
        --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }
    
    .required-field::after {
        content: " *";
        color: #dc3545;
    }
    
    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .card {
        margin-bottom: 2rem;
        border: none;
        border-radius: 10px;
        box-shadow: var(--card-shadow);
        transition: var(--transition);
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }
    
    .card-header {
        background-color: var(--primary-color);
        color: white;
        border-bottom: none;
        padding: 1rem 1.5rem;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
        background-color: #0998bb;
        border-color: #0998bb;
    }
    
    .section-title {
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        font-weight: 600;
        display: flex;
        align-items: center;
    }
    
    .section-title i {
        margin-right: 10px;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(11, 173, 211, 0.25);
    }
    
    .nav-pills .nav-link.active {
        background-color: var(--primary-color);
    }
    
    .nav-pills .nav-link {
        color: var(--secondary-color);
    }
    
    .file-upload-wrapper {
        position: relative;
        margin-bottom: 1rem;
    }
    
    .file-upload-label {
        display: block;
        padding: 0.5rem 1rem;
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        cursor: pointer;
    }
    
    .file-upload-input {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
    
    .signature-pad {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
        cursor: crosshair;
        margin-bottom: 1rem;
    }
    
    .attachments-preview img {
        max-width: 100%;
        height: auto;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        padding: 0.25rem;
        background-color: #fff;
    }
    
    .attachments-preview .attachment-item {
        position: relative;
        margin-bottom: 1rem;
    }
    
    .attachments-preview .remove-attachment {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(220, 53, 69, 0.8);
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    
    .attachments-preview .remove-attachment:hover {
        background: #dc3545;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            @if(isset($enquiry))
                                <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}">Enquiries</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('enquiries.files', $enquiry) }}">{{ $enquiry->project_name }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('enquiries.files.client-engagement', $enquiry) }}">Client Engagement</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('enquiries.site-survey.show', [$enquiry, $siteSurvey]) }}">View Site Survey</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Site Survey</li>
                            @else
                                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">{{ $project->name }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('projects.files.client-engagement', $project) }}">Client Engagement</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('projects.site-survey.show', [$project, $siteSurvey]) }}">View Site Survey</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Edit Site Survey</li>
                            @endif
                        </ol>
                    </nav>
                    <h2 class="mb-0">Edit Site Survey</h2>
                </div>
                <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.site-survey.show', [$enquiry, $siteSurvey]) : route('projects.site-survey.show', [$project, $siteSurvey]) }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Site Survey
                </a>
            </div>
            <form action="{{ isset($enquiry) ? route('enquiries.site-survey.update', [$enquiry, $siteSurvey]) : route('projects.site-survey.update', [$project, $siteSurvey]) }}" method="POST" enctype="multipart/form-data" id="siteSurveyForm">
                @csrf
                @method('PUT')
                
                @include('projects.site-survey.partials.form')
                
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.site-survey.show', [$enquiry, $siteSurvey]) : route('projects.site-survey.show', [$project, $siteSurvey]) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Site Survey
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Signature Pad -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {


        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select an option',
            allowClear: true
        });

        // Initialize Select2 for multiple select
        $('.select2-multiple').select2({
            theme: 'bootstrap-5',
            placeholder: 'Select options',
            allowClear: true,
            multiple: true
        });

        // File input preview
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        // Initialize signature pads
        const signaturePads = {};
        $('.signature-pad').each(function() {
            const canvas = this;
            const input = document.querySelector(`input[name="${$(canvas).data('input')}"]`);
            
            signaturePads[$(canvas).attr('id')] = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)'
            });
            
            // Handle canvas resize
            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
                signaturePads[$(canvas).attr('id')].clear();
            }
            
            window.addEventListener("resize", resizeCanvas);
            resizeCanvas();
            
            // Save signature to hidden input
            $(canvas).on('endStroke', function() {
                input.value = signaturePads[$(canvas).attr('id')].toDataURL();
            });
        });
        
        // Clear signature button
        $('.clear-signature').on('click', function(e) {
            e.preventDefault();
            const canvasId = $(this).data('target');
            if (signaturePads[canvasId]) {
                signaturePads[canvasId].clear();
                $(`input[name="${$(this).data('input')}"]`).val('');
            }
        });
        
        // Handle form submission
        $('#siteSurveyForm').on('submit', function() {
            // Save all signature pads to their respective inputs
            $('.signature-pad').each(function() {
                const canvasId = $(this).attr('id');
                const input = document.querySelector(`input[name="${$(this).data('input')}"]`);
                if (signaturePads[canvasId] && !signaturePads[canvasId].isEmpty()) {
                    input.value = signaturePads[canvasId].toDataURL();
                }
            });
        });
        
        // Handle remove attachment
        $(document).on('click', '.remove-attachment', function(e) {
            e.preventDefault();
            const attachmentId = $(this).data('id');
            if (confirm('Are you sure you want to remove this attachment?')) {
                $(`#attachment-${attachmentId}`).remove();
                $('<input>').attr({
                    type: 'hidden',
                    name: 'removed_attachments[]',
                    value: attachmentId
                }).appendTo('#siteSurveyForm');
            }
        });
        
        // Toggle fields based on other field values
        $('.toggle-field').on('change', function() {
            const target = $(this).data('target');
            const value = $(this).val();
            $(target).toggle(value === $(this).data('value'));
        }).trigger('change');
        
        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
