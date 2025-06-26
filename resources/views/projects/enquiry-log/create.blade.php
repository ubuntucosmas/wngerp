@extends('layouts.master')

@section('title', 'Create Enquiry Log')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
        <div>
            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Back to Projects
            </a>
            <a href="{{ route('projects.files.index', ['project' => $project->id]) }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-folder"></i> Project Files
            </a>
        </div>
        <div>
            <a href="" class="btn btn-outline-primary">
                <i class="bi bi-plus-circle"></i> New Project Brief   
            </a>
        </div>
    </div>

    <h4 class="fw-semibold mb-3 text-dark">Project Brief for <span class="text-primary">Project</span></h4>



    <div class="container py-4">
    <h4 class="mb-4">Create Project Brief for Project: <strong>{{ $project->name }}</strong></h4>

    <form action="{{ route('projects.enquiry-log.store', $project) }}" method="POST">
        @csrf

        @include('projects.enquiry-log.partials._form', ['buttonText' => 'Save Enquiry Log', 'enquiryLog' => null])
    </form>
</div>

    
</div>
@endsection

