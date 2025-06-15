@extends('layouts.master')

@section('title', 'Returns')
@section('navbar-title', 'Returns')

@section('content')


<div class="returns-container">
    <h3 class="mb-4 text-black">Return Records</h3>

    <table class="table table-bordered table-hover table-striped returns-table">
        <thead class=table-dark>
            <tr>
                <th>Return ID</th>
                <th>SKU</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Reason</th>
                <th>Return Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($returns as $return)
                <tr>
                    <td>{{ $return->id }}</td>
                    <td>{{ $return->sku }}</td>
                    <td>{{ $return->item_name }}</td>
                    <td>{{ $return->quantity }}</td>
                    <td>{{ $return->reason }}</td>
                    <td>{{ \Carbon\Carbon::parse($return->return_date)->format('M d, Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No return records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination links -->
    <div class="d-flex justify-content-center mt-4">
        {{ $returns->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
