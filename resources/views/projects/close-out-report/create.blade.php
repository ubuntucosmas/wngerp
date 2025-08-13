@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Generate Close Out Report</h5>
                </div>

                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-file-alt fa-5x text-primary mb-3"></i>
                        <h4>Auto-Generate Close Out Report</h4>
                        <p class="text-muted">Generate a comprehensive close-out report automatically from your project data.</p>
                    </div>

                    <div class="alert alert-info text-start mb-4">
                        <h6><i class="fas fa-info-circle"></i> What will be included:</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="mb-0">
                                    <li>Project details and timeline</li>
                                    <li>Material lists and procurement data</li>
                                    <li>Budget vs actual expenses</li>
                                    <li>Team composition and roles</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="mb-0">
                                    <li>Client feedback and interactions</li>
                                    <li>Issues and challenges encountered</li>
                                    <li>Quality control findings</li>
                                    <li>Project recommendations</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('projects.close-out-report.generate', $project) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-magic"></i> Generate Report Now
                        </button>
                    </form>
                    
                    <div class="mt-3">
                        <small class="text-muted">You can review and edit the generated report before submitting for approval.</small>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('projects.files.index', $project) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Project
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
