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
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Create Site Survey - {{ $project->name }}</h5>
                    <a href="{{ route('projects.files.index', $project) }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Project Files
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('projects.site-survey.store', $project) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        @include('projects.site-survey.partials.form')
                        
                        <div class="form-group row mb-0">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Site Survey
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
