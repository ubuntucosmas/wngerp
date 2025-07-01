@extends('layouts.master')

@section('title', 'Project Budgets')

@section('content')
<div class="container mt-4">

    {{-- Breadcrumbs --}}
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-2 rounded">
            <li class="breadcrumb-item"><a href="{{ route('projects.index') }}"><i class="fas fa-home"></i>Projects</a></li>
            <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project->id) }}">Projects Files</a></li>
            <li class="breadcrumb-item active" aria-current="page">Project Budgets</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Budgets for {{ $project->name }}</h4>
        <a href="{{ route('budget.create', $project) }}" class="btn btn-primary btn-sm">+ New Budget</a>
    </div>

    @if($budgets->count())
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total Budget</th>
                    <th>Invoice</th>
                    <th>Profit</th>
                    <th>Status</th>
                    <th>Approved By</th>
                    <th>Departments</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($budgets as $index => $budget)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($budget->start_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($budget->end_date)->format('d M Y') }}</td>
                        <td>KES {{ number_format($budget->budget_total, 2) }}</td>
                        <td>KES {{ number_format($budget->invoice, 2) }}</td>
                        <td>KES {{ number_format($budget->profit, 2) }}</td>
                        <td>
                            <span class="{{ $budget->status == 'approved' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($budget->status) }}
                            </span>
                        </td>
                        <td>{{ $budget->approved_by ?? '-' }}</td>
                        <td>{{ $budget->approved_departments ?? '-' }}</td>
                        <td>{{ $budget->created_at->format('d M Y H:i') }}</td>
                        <td>
                            <a href="{{ route('budget.show', [$project, $budget]) }}" class="btn btn-info btn-sm">View</a>

                            @hasanyrole('finance|accounts|super-admin')
                                <a href="{{ route('budget.edit', [$project, $budget]) }}" class="btn btn-warning btn-sm">Edit</a>

                                @if(auth()->user()->hasRole('super-admin'))
                                    <form action="{{ route('budget.destroy', ['project' => $project->id, 'budget' => $budget->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm delete-budget" onclick="return confirm('Are you sure you want to delete this budget? This action cannot be undone.')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                @endif
                            @endhasanyrole
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
        <div class="alert alert-warning">No budgets have been created yet for this project.</div>
    @endif
</div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush
@endsection
<script>
        // Delete confirmation
    document.addEventListener('DOMContentLoaded', function() {
        // Delete confirmation
        document.querySelectorAll('.delete-budget').forEach(button => {
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