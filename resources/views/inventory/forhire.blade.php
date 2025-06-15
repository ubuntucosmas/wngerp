@extends('layouts.master')

@section('title', 'For Hire')
@section('navbar-title', 'For Hire')

@section('content')
<div class="container mt-4">
    <h1>For Hire</h1>

    <!-- Add New Hire Button -->
    <a href="#" class="btn btn-outline-primary mb-3" data-bs-toggle="modal" data-bs-target="#addHireModal">Add New Hire</a>

    <!-- Table -->
    <div class="card">
        <div class="card-header">Hired Items</div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>SKU</th>
                        <th>Client</th>
                        <th>Contacts</th>
                        <th>Quantity</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($hires as $hire)
                    <tr>
                        <td>{{ $hire->sku }}</td>
                        <td>{{ $hire->client }}</td>
                        <td>{{ $hire->contacts }}</td>
                        <td>{{ $hire->quantity }}</td>
                        <td>{{ $hire->start_date }}</td>
                        <td>{{ $hire->end_date }}</td>
                        <td>{{ $hire->status }}</td>
                        <td>
                            <!-- Actions here like Edit/Delete -->
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add New Hire Modal -->
    <div class="modal fade" id="addHireModal" tabindex="-1" aria-labelledby="addHireModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addHireModalLabel">Add New Hire</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('inventory.hires.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- SKU Dropdown -->
                        <div class="mb-3">
                            <label for="sku" class="form-label">SKU</label>
                            <select class="form-select" id="sku" name="sku" required>
                                <option value="">-- Select SKU --</option>
                                @foreach($skus as $sku)
                                    <option value="{{ $sku }}">{{ $sku }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Client -->
                        <div class="mb-3">
                            <label for="client" class="form-label">Client</label>
                            <input type="text" class="form-control" id="client" name="client" required>
                        </div>
                        <!-- Contacts -->
                        <div class="mb-3">
                            <label for="contacts" class="form-label">Contacts</label>
                            <input type="text" class="form-control" id="contacts" name="contacts" required>
                        </div>
                        <!-- quantity -->
                        <div class="mb-3">
                            <label for="contacts" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required min="1">
                        </div>
                        <!-- Start Date -->
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <!-- End Date -->
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                        <!-- Hire Fee -->
                        <div class="mb-3">
                            <label for="hire_fee" class="form-label">Hire Fee</label>
                            <input type="number" class="form-control" id="hire_fee" name="hire_fee" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection