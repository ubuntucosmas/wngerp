<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

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

    public function showUsers()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('admin.users', compact('users', 'roles'));
    }

    public function showEditUser(User $user)
    {
        $roles = Role::all();
        return view('admin.edit-user', compact('user', 'roles'));
    }

    public function createUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', 'string', 'exists:roles,name'],
            'department' => 'nullable|string|max:255',
            'level' => 'required|integer|between:1,5',
        ], [
            'password.confirmed' => 'The password confirmation does not match.',
            'level.between' => 'The access level must be between 1 and 5.',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'role' => $validated['role'],
                    'department' => $validated['department'] ?? null,
                    'level' => $validated['level'],
                ]);

                $user->assignRole($validated['role']);

                // Log the creation
                Log::create([
                    'action' => 'User Created',
                    'performed_by' => auth()->user()->name,
                    'details' => "Created user: {$validated['name']} with role: {$validated['role']}",
                ]);
            });

            return redirect()->route('admin.users')
                ->with('success', 'User created successfully.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error creating user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Error creating user: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateUser(Request $request)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                'user_id' => ['required', 'exists:users,id'],
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($request->user_id)],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
                'role' => ['required', 'string', 'exists:roles,name'],
                'department' => ['nullable', 'string', 'max:255'],
                'level' => ['required', 'integer', 'between:1,5'],
            ], [
                'email.unique' => 'The email address is already in use by another user.',
                'level.between' => 'The access level must be between 1 and 5.',
                'password.confirmed' => 'The password confirmation does not match.',
            ]);

            // Get the user
            $user = User::findOrFail($validated['user_id']);

            // Prepare update data
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'department' => $validated['department'] ?? null,
                'level' => $validated['level'],
            ];

            // Only update password if provided
            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            // Update user and sync roles in a single transaction
            DB::transaction(function () use ($user, $updateData, $validated) {
                $user->update($updateData);
                $user->syncRoles([$validated['role']]);

                // Log the update
                Log::create([
                    'action' => 'User Updated',
                    'performed_by' => auth()->user()->name,
                    'details' => "Updated user: {$user->name} with role: {$validated['role']}\nChanges: " . json_encode($user->getChanges()),
                ]);
            });

            return redirect()->route('admin.users')
                ->with('success', 'User updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation failed
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error updating user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $request->user_id,
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Error updating user: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function deleteUser(User $user)
    {
        // Prevent deletion of self
        if ($user->id === auth()->user()->id) {
            return redirect()->route('admin.users')
                ->with('error', 'You cannot delete your own account.');
        }

        try {
            DB::transaction(function () use ($user) {
                // Log the deletion first
                Log::create([
                    'action' => 'User Deleted',
                    'performed_by' => auth()->user()->name,
                    'details' => "Deleted user: {$user->name}",
                ]);

                // Soft delete the user
                $user->delete();
            });

            return redirect()->route('admin.users')
                ->with('success', 'User deleted successfully.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error deleting user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id
            ]);

            return redirect()->route('admin.users')
                ->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    public function viewLogs()
    {
        $logs = Log::latest()->paginate(20); // Paginate logs
        return view('admin.logs', compact('logs'));
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