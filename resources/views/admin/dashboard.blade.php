@extends('layouts.master')

@section('title', 'Admin Dashboard')
@section('navbar-title', 'Admin Management')

@section('sidebar-menu')
    <li class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item {{ request()->is('admin/users*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.users.index') }}">
            <i class="bi bi-people"></i> User Management
        </a>
    </li>
@endsection

@section('content')
<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease; /* Add transition for smoother hover effect */
    }
    .card:hover {
        transform: scale(1.01); /* Slight scaling on hover */
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    .page-link i {
        font-size: 1.25rem; /* Adjust icon size */
    }
    .stat-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        border-radius: 10px;
        overflow: hidden;
    }
    .stat-card .card-header {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
    .stat-card .card-body {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .stat-card h5 {
        font-size: 1.5rem;
        margin-bottom: 0.3rem;
    }
    .stat-card p {
        font-size: 0.8rem;
        margin-bottom: 0;
    }
    .pagination-container {
        padding: 0.5rem 1rem;
    }
    .pagination {
        margin-bottom: 0;
    }
    .page-item .page-link {
        padding: 0.3rem 0.75rem;
        font-size: 0.9rem;
    }
    .gradient-header {
        background: linear-gradient(45deg, #007bff, #00b7ff);
        color: white;
    }
    .btn-gradient {
        background: linear-gradient(45deg, #007bff, #00b7ff);
        color: white;
        border: none;
    }
    .btn-gradient:hover {
        background: linear-gradient(45deg, #0056b3, #0086cc);
        color: white;
    }
</style>
<div class="px-3 mx-10 mt-2 w-100">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden mb-3">
                <div class="card-header gradient-header p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h4 mb-0">Admin Dashboard</h1>
                    </div>
                </div>
                <div class="card-body p-3">
                    <!-- Log Filters -->
                    <div class="card mb-3 shadow-sm border-0 rounded-3">
                        <div class="card-header bg-light p-2">
                            <h6 class="mb-0">Filter Logs</h6>
                        </div>
                        <div class="card-body p-3">
                            <form method="GET" action="{{ route('admin.dashboard') }}">
                                <div class="row g-2">
                                    <div class="col-md-3 col-sm-6">
                                        <input type="text" class="form-control form-control-sm rounded-pill" name="action" placeholder="Action Type" value="{{ request('action') }}">
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <input type="text" class="form-control form-control-sm rounded-pill" name="performed_by" placeholder="Performed By" value="{{ request('performed_by') }}">
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <input type="date" class="form-control form-control-sm rounded-pill" name="start_date" value="{{ request('start_date') }}">
                                    </div>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="d-flex gap-2">
                                            <input type="date" class="form-control form-control-sm rounded-pill" name="end_date" value="{{ request('end_date') }}">
                                            <button type="submit" class="btn btn-gradient btn-sm rounded-pill px-3">Filter</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="row mb-3 g-3">
                        <!-- Total Logs Card -->
                        <div class="col-md-4 col-sm-12">
                            <div class="card stat-card text-white h-100 shadow-sm border-0 rounded-3" style="background: linear-gradient(45deg, #007bff, #00b7ff);">
                                <div class="card-header" style="background: linear-gradient(45deg, #0056b3, #0086cc);">Total Logs</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $totalLogs }}</h5>
                                    <p class="card-text">Total actions recorded in the system.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Logs Today Card -->
                        <div class="col-md-4 col-sm-12">
                            <div class="card stat-card text-white h-100 shadow-sm border-0 rounded-3" style="background: linear-gradient(45deg, #00b09b, #96c93d);">
                                <div class="card-header" style="background: linear-gradient(45deg, #008c7a, #78a32e);">Logs Today</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $logsToday }}</h5>
                                    <p class="card-text">Actions logged in the past 24 hours.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Unique Users Card -->
                        <div class="col-md-4 col-sm-12">
                            <div class="card stat-card text-white h-100 shadow-sm border-0 rounded-3" style="background: linear-gradient(45deg, #ff904d, #ffdb58);">
                                <div class="card-header" style="background: linear-gradient(45deg, #cc703d, #ccab46);">Unique Users</div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $uniqueUsers }}</h5>
                                    <p class="card-text">Users who performed actions.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logs Table -->
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-light p-2">
                            <h6 class="mb-0">Recent Logs</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="p-2">#</th>
                                            <th class="p-2">Action</th>
                                            <th class="p-2">Performed By</th>
                                            <th class="p-2">Details</th>
                                            <th class="p-2">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($logs as $log)
                                            <tr>
                                                <td class="p-2">{{ $loop->iteration }}</td>
                                                <td class="p-2">{{ $log->action }}</td>
                                                <td class="p-2">{{ $log->performed_by }}</td>
                                                <td class="p-2">{{ $log->details }}</td>
                                                <td class="p-2">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center p-3">No logs found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="pagination-container">
                        {{ $logs->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
