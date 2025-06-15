@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Close Out Report</h5>
                    <span class="badge bg-{{ $report->status === 'approved' ? 'success' : ($report->status === 'rejected' ? 'danger' : 'info') }}">
                        {{ ucfirst($report->status) }}
                    </span>
                </div>

                <div class="card-body">
                    <form action="{{ route('projects.close-out-report.update', [$project, $report]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('projects.close-out-report._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
