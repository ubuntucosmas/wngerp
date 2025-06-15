@extends('layouts.master')

@section('title', 'Edit Booking Order')
@section('navbar-title', 'Edit Booking Order for ' . $project->name)

@section('content')
<div class="container mt-4">
    <form action="{{ route('projects.booking-order.update', [$project->id, $bookingOrder->id]) }}" method="POST" class="needs-validation" novalidate>
        @csrf
        @method('PUT')
        <input type="hidden" name="project_name" value="{{ $bookingOrder->project_name }}">

        <div class="d-flex justify-content-between align-items-center mt-4">
            <div>
                <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Back to Projects
                </a>
            </div>
            <div>
                <button type="reset" class="btn btn-outline-danger me-2">
                    <i class="bi bi-x-circle"></i> Reset Changes
                </button>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i> Update Booking Order
                </button>
            </div>
        </div>

        @include('projects.bookingOrder.partials.form', ['bookingOrder' => $bookingOrder])

    </form>
</div>
@endsection
