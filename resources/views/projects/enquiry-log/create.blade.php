@extends('layouts.master')

@section('title', 'Create Enquiry Log')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @if(isset($enquiry))
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.index') }}">Enquiries</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.files', $enquiry) }}">{{ $enquiry->project_name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('enquiries.files.client-engagement', $enquiry) }}">Client Engagement</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Enquiry Log</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">{{ $project->name }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.files.client-engagement', $project) }}">Client Engagement</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Enquiry Log</li>
                    @endif
                </ol>
            </nav>
            <h2 class="mb-0">Create Enquiry Log</h2>
        </div>
        <a href="{{ (isset($enquiry) && is_object($enquiry) && isset($enquiry->id)) ? route('enquiries.files.client-engagement', $enquiry) : route('projects.files.client-engagement', $project) }}" class="btn btn-primary">
            <i class="bi bi-arrow-left me-2"></i>Back to Client Engagement
        </a>
    </div>

    <h4 class="fw-semibold mb-3 text-dark">{{ isset($enquiry) ? 'Enquiry' : 'Project' }} Brief for <span class="text-primary">{{ isset($enquiry) ? 'Enquiry' : 'Project' }}</span></h4>

    <div class="container py-4">
        <h4 class="mb-4">Create {{ isset($enquiry) ? 'Enquiry' : 'Project' }} Brief for {{ isset($enquiry) ? 'Enquiry' : 'Project' }}: <strong>{{ isset($enquiry) ? $enquiry->project_name : $project->name }}</strong></h4>

        <form action="{{ isset($enquiry) ? route('enquiries.enquiry-log.store', $enquiry) : route('projects.enquiry-log.store', $project) }}" method="POST">
        @csrf

        @include('projects.enquiry-log.partials._form', ['buttonText' => 'Save Enquiry Log', 'enquiryLog' => null])
    </form>
</div>
</div>
@endsection

