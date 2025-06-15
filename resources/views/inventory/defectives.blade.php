@extends('layouts.master')

@section('title', 'Defective Items')
@section('navbar-title', 'Defective Items')

@section('content')

        <h2 class="text-center mb-4">Defective Items</h2>
        <table class="table table-bordered table-hover table-striped returns-table">
            <thead class=table-dark>
                <tr>
                    <th>Defect ID</th>
                    <th>SKU</th>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Defect Type</th>
                    <th>Reported By</th>
                    <th>Date Reported</th>
                    <th>Remarks</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($defectiveItems as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->sku }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ ucfirst($item->defect_type) }}</td>
                    <td>{{ $item->reported_by }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->date_reported)->format('M d, Y') }}</td>
                    <td>{{ $item->remarks ?? 'N/A' }}</td>
                    <td>
                        <span class="badge bg-{{ $item->status === 'pending' ? 'warning' : ($item->status === 'disposed' ? 'danger' : 'success') }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

@endsection
