@extends('layouts.master')

@section('title', 'Edit User')
@section('navbar-title', 'Admin Dashboard')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Edit User</h2>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                                <input type="password" name="password" id="password" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select name="role" id="role" class="form-control" required>
                                    @foreach(\Spatie\Permission\Models\Role::all() as $role)
                                        <option value="{{ $role->name }}" {{ old('role', $user->roles->first()?->name) === $role->name ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="department" class="form-label">Department</label>
                                <select name="department" id="department" class="form-control">
                                    <option value="" {{ old('department', $user->department) === null ? 'selected' : '' }}>None</option>
                                    <option value="IT" {{ old('department', $user->department) === 'IT' ? 'selected' : '' }}>IT</option>
                                    <option value="HR" {{ old('department', $user->department) === 'HR' ? 'selected' : '' }}>HR</option>
                                    <option value="Procurement" {{ old('department', $user->department) === 'Procurement' ? 'selected' : '' }}>Procurement</option>
                                    <option value="Projects" {{ old('department', $user->department) === 'Projects' ? 'selected' : '' }}>Projects</option>
                                    <option value="Stores" {{ old('department', $user->department) === 'Stores' ? 'selected' : '' }}>Stores</option>
                                    <option value="Administration" {{ old('department', $user->department) === 'Administration' ? 'selected' : '' }}>Administration</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="level" class="form-label">Access Level</label>
                                <select name="level" id="level" class="form-control" required>
                                    <option value="1" {{ old('level', $user->level) == 1 ? 'selected' : '' }}>Level 1 (Basic)</option>
                                    <option value="2" {{ old('level', $user->level) == 2 ? 'selected' : '' }}>Level 2 (Standard)</option>
                                    <option value="3" {{ old('level', $user->level) == 3 ? 'selected' : '' }}>Level 3 (Advanced)</option>
                                    <option value="4" {{ old('level', $user->level) == 4 ? 'selected' : '' }}>Level 4 (Manager)</option>
                                    <option value="5" {{ old('level', $user->level) == 5 ? 'selected' : '' }}>Level 5 (Admin)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-save me-1"></i> Save Changes
                            </button>
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Back to Users
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection