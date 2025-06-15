@extends('layouts.master')

@section('title', 'Create Booking Order')
@section('navbar-title', 'Create Booking Order for ' . $project->name)

@section('content')
<div class="container mt-4">
    <form action="{{ route('projects.booking-order.store', $project->id) }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <input type="hidden" name="project_name" value="{{ $project->name }}">
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div>
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Back to Projects
                </a>
                <a href="{{ route('projects.booking-order.index', $project->id) }}" class="btn btn-secondary me-2">
                    <i class="bi bi-list"></i> View Booking Orders
                </a>
            </div>
            <div>
                <button type="reset" class="btn btn-outline-danger me-2">
                    <i class="bi bi-x-circle"></i> Reset Form
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Create Booking Order
                </button>
            </div>
        </div>

        @include('projects.bookingOrder.partials.form', ['bookingOrder' => null])

    </form>
</div>
@endsection
