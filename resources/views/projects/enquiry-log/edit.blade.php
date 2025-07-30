@extends('layouts.master')

@section('title', 'Edit Enquiry Log')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @if(isset($enquiry))
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}">Enquiries</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.files', $enquiry) }}">{{ $enquiry->project_name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.files.client-engagement', $enquiry) }}">Client Engagement</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.enquiry-log.show', [$enquiry, $enquiryLog]) }}">View Enquiry Log</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Enquiry Log</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">{{ $project->name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.files.client-engagement', $project) }}">Client Engagement</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.enquiry-log.show', [$project, $enquiryLog]) }}">View Enquiry Log</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Enquiry Log</li>
                    @endif
                </ol>
            </nav>
            <h2 class="mb-0">Edit Enquiry Log</h2>
        </div>
        <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.enquiry-log.show', [$enquiry, $enquiryLog]) : route('projects.enquiry-log.show', [$project, $enquiryLog]) }}" class="btn btn-primary">
            <i class="bi bi-arrow-left me-2"></i>Back to Enquiry Log
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($enquiry) ? route('enquiries.enquiry-log.update', [$enquiry, $enquiryLog]) : route('projects.enquiry-log.update', [$project, $enquiryLog]) }}" method="POST" id="enquiryLogForm">
        @csrf
        @method('PUT')

        @include('projects.enquiry-log.partials._form', ['buttonText' => 'Update Enquiry Log'])
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('enquiryLogForm');
    const submitButton = form.querySelector('button[type="submit"]');
    
    // Add form submission handler
    form.addEventListener('submit', function(e) {
        console.log('Form submission started');
        
        // Disable submit button to prevent double submission
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Updating...';
        
        // Log form data for debugging
        const formData = new FormData(form);
        console.log('Form data:', Object.fromEntries(formData));
        
        // Re-enable button after 3 seconds in case of issues
        setTimeout(() => {
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="bi bi-save me-1"></i> Update Enquiry Log';
        }, 3000);
    });
    
    // Add validation for required fields
    const requiredFields = form.querySelectorAll('input[required], textarea[required]');
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });
    
    console.log('Enquiry log form initialized');
});
</script>
@endpush
