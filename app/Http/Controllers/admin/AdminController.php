<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Log;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Log::query();
    
        // Apply filters if any
        if ($request->filled('action')) {
            $query->where('action', 'like', '%' . $request->action . '%');
        }
    
        if ($request->filled('performed_by')) {
            $query->where('performed_by', 'like', '%' . $request->performed_by . '%');
        }
    
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }
    
        // Fetch logs
        $logs = $query->latest()->paginate(5);
    
        // Define the metrics
        $totalLogs = Log::count(); // Total number of logs
        $logsToday = Log::whereDate('created_at', now()->toDateString())->count(); // Logs added today
        $uniqueUsers = Log::distinct('performed_by')->count('performed_by'); // Unique users
    
        // Pass variables to the view
        return view('admin.dashboard', compact('logs', 'totalLogs', 'logsToday', 'uniqueUsers'));
    }
    public function users()
    {
        $users = User::with('roles')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', 'string', 'exists:roles,name'],
            'department' => 'nullable|string|max:255',
            'level' => 'required|integer|between:1,5',
        ]);

                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role' => $validated['role'],  // Add this line to update the role column
                    'department' => $validated['department'] ?? null,
                    'level' => $validated['level'],
                ]);
                $user->assignRole($validated['role']);  // This is for Spatie's role management

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', 'string', 'exists:roles,name'],
            'department' => 'nullable|string|max:255',
            'level' => 'required|integer|between:1,5',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];  // Update the role column
        $user->department = $validated['department'] ?? null;
        $user->level = $validated['level'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();
        $user->syncRoles([$validated['role']]);  // This is for Spatie's role management

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }


    public function setDepartment(Request $request)
    {
        $request->validate([
            'active_department' => 'required|in:stores,projects,procurement,HR,IT',
        ]);
    
        $department = $request->active_department;
        session(['active_department' => $department]);
    
        // Define where to redirect based on department
        $redirectRoutes = [
            'stores'      => route('inventory.dashboard'),
            'projects'    => route('projects.overview'),
        ];
    
        return redirect($redirectRoutes[$department] ?? route('admin.dashboard'))
            ->with('success', ucfirst($department).' dashboard loaded.');
    }
    
}