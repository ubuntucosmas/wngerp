@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Create Close Out Report</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('projects.close-out-report.store', $project) }}" method="POST" enctype="multipart/form-data">
                        @include('projects.close-out-report._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
