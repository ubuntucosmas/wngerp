@extends('layouts.master')

@section('title', 'HR Dashboard')
@section('navbar-title', 'HR Management')

@section('sidebar-menu')
    <li class="nav-item">
        <a class="nav-link text-black" href="/employees">Employees</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-black" href="/leave">Leave Management</a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-black" href="/payroll">Payroll</a>
    </li>
@endsection

@section('content')
    <h1>Welcome to the HR Dashboard</h1>
    <p>Manage employee records, leave applications, and payroll here.</p>
@endsection