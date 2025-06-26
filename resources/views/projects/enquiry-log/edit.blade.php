@extends('layouts.master')

@section('title', 'Edit Project Brief')

@section('content')
<div class="container py-4">
    <h4 class="mb-4">Edit Project Brief for Project: <strong>{{ $project->name }}</strong></h4>

    <form action="{{ route('projects.enquiry-log.update', [$project, $enquiryLog]) }}" method="POST">
        @csrf
        @method('PUT')

        @include('projects.enquiry-log.partials._form', ['buttonText' => 'Update Enquiry Log'])
    </form>
</div>
@endsection
