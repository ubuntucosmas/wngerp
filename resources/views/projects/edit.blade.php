@extends('layouts.master')

@section('title', 'Edit Project')
@section('navbar-title', 'Edit Project')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Project: {{ $project->name }}
                    </h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>There were some problems with your input.</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('projects.update', $project) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_id" class="form-label">Project ID</label>
                                    <input type="text" name="project_id" id="project_id" class="form-control" 
                                           value="{{ old('project_id', $project->project_id) }}" 
                                           placeholder="Current: {{ $project->project_id }}">
                                    <div class="form-text">Leave blank to keep current ID: {{ $project->project_id }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label">Project Name<span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" 
                                           value="{{ old('name', $project->name) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="client_id" class="form-label">Client<span class="text-danger">*</span></label>
                                    <select name="client_id" id="client_id" class="form-select" required>
                                        <option value="">-- Select Client --</option>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->ClientID }}" 
                                                    {{ old('client_id', $project->client_id) == $client->ClientID ? 'selected' : '' }}>
                                                {{ $client->FullName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="venue" class="form-label">Venue<span class="text-danger">*</span></label>
                                    <input type="text" name="venue" id="venue" class="form-control" 
                                           value="{{ old('venue', $project->venue) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date" class="form-label">Start Date<span class="text-danger">*</span></label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" 
                                           value="{{ old('start_date', $project->start_date->format('Y-m-d')) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date" class="form-label">End Date<span class="text-danger">*</span></label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" 
                                           value="{{ old('end_date', $project->end_date->format('Y-m-d')) }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Update Project
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection