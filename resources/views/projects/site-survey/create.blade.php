@extends('layouts.master')

@push('styles')
<!-- Bootstrap Datepicker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            @if(isset($enquiry))
                                <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}">Enquiries</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('enquiries.files', $enquiry) }}">{{ $enquiry->project_name }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('enquiries.files.client-engagement', $enquiry) }}">Client Engagement</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Create Site Survey</li>
                            @else
                                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">{{ $project->name }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('projects.files.client-engagement', $project) }}">Client Engagement</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Create Site Survey</li>
                            @endif
                        </ol>
                    </nav>
                    
                    <h2 class="mb-0">Create Site Survey</h2>
                </div>
                <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.files.client-engagement', $enquiry) : route('projects.files.client-engagement', $project) }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Client Engagement
                </a>
            </div>
            <form action="{{ isset($enquiry) ? route('enquiries.site-survey.store', $enquiry) : route('projects.site-survey.store', $project) }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                @include('projects.site-survey.partials.form', ['project' => isset($enquiry) ? $enquiry : $project])
                
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary">
                         <i class="fas fa-save"></i> Save Site Survey
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
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
        $('#site_visit_date').val('{{ old('site_visit_date', now()->format('Y-m-d')) }}');
        $('#prepared_date').val('{{ old('prepared_date', now()->format('Y-m-d')) }}');
    });
</script>
@endpush
