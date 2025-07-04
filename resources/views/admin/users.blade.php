@extends('layouts.master')

@section('title', 'User Management')
@section('navbar-title', 'User Management')

@section('sidebar-menu')
    <li class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
    </li>
    <li class="nav-item {{ request()->is('admin/users*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.users') }}">
            <i class="bi bi-people"></i> User Management
        </a>
    </li>
@endsection

@section('content')
<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    .gradient-header {
        background: linear-gradient(45deg, #007bff, #00b7ff);
        color: white;
    }
    .table-responsive {
        border-radius: 10px;
        overflow: hidden;
    }
    .badge {
        font-size: 0.8em;
        padding: 0.4em 0.6em;
    }
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.7);
    }
    .modal-content {
        animation: fadeIn 0.3s ease-out;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    .modal-header {
        padding: 1.5rem 1rem;
        background: linear-gradient(45deg, #007bff, #00b7ff);
        color: white;
        border-bottom: 0;
    }
    .modal-body {
        padding: 2rem 1.5rem;
    }
    .modal-footer {
        border-top: 0;
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
    .btn-outline-gradient {
        border: 1px solid transparent;
        background: transparent;
        color: #007bff;
        position: relative;
        overflow: hidden;
    }
    .btn-outline-gradient:hover {
        color: white;
        background: linear-gradient(45deg, #007bff, #00b7ff);
        border-color: transparent;
    }
    .btn-outline-primary:hover {
        background: linear-gradient(45deg, #007bff, #00b7ff);
        color: white;
        transform: scale(1.05);
        transition: all 0.2s ease;
    }
</style>

<div class="container-fluid py-3 bg-light min-vh-100">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-11">
            <!-- Alerts -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3 rounded-3" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-3 rounded-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm border-0 rounded-3 overflow-hidden mb-3">
                <div class="card-header gradient-header p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h4 mb-0">User Management</h1>
                        <button class="btn btn-light btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#addUserModal">+ Add User</button>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive p-3">
                        <table class="table table-hover align-middle table-sm">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th scope="col" class="py-2 px-3 rounded-start">ID</th>
                                    <th scope="col" class="py-2 px-3">Name</th>
                                    <th scope="col" class="py-2 px-3">Email</th>
                                    <th scope="col" class="py-2 px-3">Role</th>
                                    <th scope="col" class="py-2 px-3">Department</th>
                                    <th scope="col" class="py-2 px-3">Level</th>
                                    <th scope="col" class="py-2 px-3 rounded-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td class="py-2 px-3">{{ $user->id }}</td>
                                        <td class="py-2 px-3">{{ $user->name }}</td>
                                        <td class="py-2 px-3">{{ $user->email }}</td>
                                        <td class="py-2 px-3">{{ $user->role ?: 'None' }}</td>
                                        <td class="py-2 px-3">{{ $user->department ?: 'None' }}</td>
                                        <td class="py-2 px-3">{{ $user->level ?: 'None' }}</td>
                                        <td class="py-2 px-3">
                                            <div class="d-flex gap-1">
                                                 <button class="btn btn-outline-primary btn-sm rounded-pill px-2 btn-edit" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#updateUserModal"
                                                        data-user-id="{{ $user->id }}"
                                                        data-user-name="{{ $user->name }}"
                                                        data-user-email="{{ $user->email }}"
                                                        data-user-role="{{ $user->roles->first()?->name }}"
                                                        data-user-department="{{ $user->department }}"
                                                        data-user-level="{{ $user->level }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                @role('admin|super-admin')
                                                    @if(auth()->user()->level >= 4)
                                                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-outline-danger btn-sm rounded-pill px-2" type="submit" onclick="return confirm('Are you sure you want to delete this user?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endrole
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.users.create') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Name</label>
                                <input type="text" name="name" id="name" class="form-control rounded-pill" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" id="email" class="form-control rounded-pill" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input type="password" name="password" id="password" class="form-control rounded-pill" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-pill" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label fw-semibold">Role</label>
                                <select name="role" id="role" class="form-control rounded-pill" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ ucfirst(str_replace('-', ' ', $role->name)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="department" class="form-label fw-semibold">Department</label>
                                <select name="department" id="department" class="form-control rounded-pill">
                                    <option value="">None</option>
                                    <option value="administration">Administration</option>
                                    <option value="ict">IT</option>
                                    <option value="projects">Projects</option>
                                    <option value="hr">HR</option>
                                    <option value="stores">Stores</option>
                                    <option value="procurement">Procurement</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="level" class="form-label fw-semibold">Access Level (1-5)</label>
                                <select name="level" id="level" class="form-control rounded-pill" required>
                                    <option value="1">1 - Basic</option>
                                    <option value="2">2 - Intermediate</option>
                                    <option value="3">3 - Advanced</option>
                                    <option value="4">4 - Senior</option>
                                    <option value="5">5 - Highest</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient rounded-pill px-4">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update User Modal -->
<div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="updateUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="updateUserModalLabel">Update User</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.users.update', 0) }}" method="POST" id="updateUserForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" id="modal_user_id">
                    @include('admin.partials.user-form-fields', ['roles' => $roles, 'modal' => true])
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient rounded-pill px-4">Update User</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all modals
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        new bootstrap.Modal(modal);
    });

    // Handle edit button click
    const editButtons = document.querySelectorAll('.btn-edit');
    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('modal_user_id').value = this.getAttribute('data-user-id');
            document.getElementById('modal_name').value = this.getAttribute('data-user-name');
            document.getElementById('modal_email').value = this.getAttribute('data-user-email');
            document.getElementById('modal_role').value = this.getAttribute('data-user-role');
            document.getElementById('modal_department').value = this.getAttribute('data-user-department');
            document.getElementById('modal_level').value = this.getAttribute('data-user-level');
            // Update form action to include user ID
            document.getElementById('updateUserForm').action = `/admin/users/${this.getAttribute('data-user-id')}`;
            // Show update modal
            const updateModal = new bootstrap.Modal(document.getElementById('updateUserModal'));
            updateModal.show();
        });
    });

    // Add form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const errorElements = form.querySelectorAll('.is-invalid');
            errorElements.forEach(el => el.classList.remove('is-invalid'));
        });
    });
});
</script>
</section>

