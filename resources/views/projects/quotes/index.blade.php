@extends('layouts.master')

@section('title', 'Quotes')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project->id) }}">Project Files</a></li>
            <li class="breadcrumb-item active" aria-current="page">Quotes</li>
        </ol>
    </nav>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Quotes</h1>
        <a href="{{ route('quotes.create', $project) }}" class="btn btn-outline-primary">
            <i class="fas fa-plus"></i> Create New Quote
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($quotes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Quote #</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Reference</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quotes as $quote)
                                @php
                                    $total = $quote->lineItems->sum('total');
                                @endphp
                                <tr>
                                    <td>#{{ $quote->id }}</td>
                                    <td>{{ $quote->customer_name }}</td>
                                    <td>{{ $quote->quote_date->format('M d, Y') }}</td>
                                    <td>{{ $quote->reference ?? 'N/A' }}</td>
                                    <td>KES {{ number_format($total, 2) }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('quotes.show', ['project' => $project->id, 'quote' => $quote->id]) }}" class="btn btn-sm btn-outline-info" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('quotes.edit', ['project' => $project->id, 'quote' => $quote->id]) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('quotes.destroy', ['project' => $project->id, 'quote' => $quote->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this quote?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger delete-quote" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $quotes->appends(['project' => $project->id])->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-file-invoice fa-4x text-muted mb-3"></i>
                    <h4>No quotes found</h4>
                    <p class="text-muted">Get started by creating a new quote</p>
                    <a href="{{ route('quotes.create', $project) }}" class="btn btn-outline-primary mt-2">
                        <i class="bi bi-plus"></i> Create Quote
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush
@endsection

<script>
        // Delete confirmation
        document.addEventListener('DOMContentLoaded', function() {
        // Delete confirmation
        document.querySelectorAll('.delete-quote').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
