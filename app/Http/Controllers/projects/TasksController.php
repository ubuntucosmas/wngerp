<?php

namespace App\Http\Controllers\projects;
use App\Http\Controllers\Controller;

use App\Models\Task;
use App\Models\Phase;
use App\Models\Deliverable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TasksController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Task form data received', $request->all());

        // Authorization Check
        if (!auth()->check() || !in_array(auth()->user()->role, ['pm', 'po'])) {
            Log::error('Unauthorized access to create task');
            return redirect()->back()->withErrors(['error' => 'Unauthorized access']);
        }

        // Validate Input
        try {
            $validated = $request->validate([
                'phase_id' => 'required|exists:phases,id',
                'task_name' => 'required|string|max:255',
                'task_status' => 'required|in:Pending,In Progress,Completed',
                'comment' => 'nullable|string|max:1000',
                'file' => 'nullable|file|max:10240', // 10MB max
                'deliverables' => 'required|array|min:1',
                'deliverables.*.description' => 'required|string|max:255',
                'deliverables.*.completed' => 'nullable|boolean',
            ]);
            Log::info('Validation passed', $validated);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        // Create Task with Transaction
        DB::beginTransaction();
        try {
            Log::info('Creating task');

            // Handle File Upload
            $filePath = $request->hasFile('file') ? $request->file('file')->store('tasks', 'public') : null;
            Log::info('File uploaded', ['file_path' => $filePath]);

            // Create Task
            $task = Task::create([
                'phase_id' => $validated['phase_id'],
                'name' => $validated['task_name'],
                'status' => $validated['task_status'],
                'comment' => $validated['comment'],
                'file_path' => $filePath,
                'progress' => 0,
            ]);
            Log::info('Task created', ['task_id' => $task->id]);

            // Bulk Insert Deliverables
            $deliverables = collect($validated['deliverables'])->map(function ($deliverable) use ($task) {
                return [
                    'task_id' => $task->id,
                    'description' => $deliverable['description'],
                    'completed' => isset($deliverable['completed']) ? $deliverable['completed'] : false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            Deliverable::insert($deliverables);
            Log::info('Deliverables created for task', ['task_id' => $task->id]);

            // Update Task Progress
            $task->calculateProgress();
            Log::info('Task progress calculated', ['task_id' => $task->id, 'progress' => $task->progress]);

            DB::commit();
            return redirect()->route('projects.show', $task->phase->project_id)
                ->with('success', 'Task created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create task', [
                'message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withErrors(['error' => 'Something went wrong. Please try again.'])->withInput();
        }
    }

    public function updateDeliverables(Request $request, Task $task)
    {
        $request->validate([
            'deliverables.*.completed' => 'nullable|boolean',
        ]);

        foreach ($task->deliverables as $index => $deliverable) {
            $deliverable->update([
                'completed' => isset($request->deliverables[$index]['completed']),
            ]);
        }

        $task->calculateProgress();
        return redirect()->back()->with('success', 'Deliverables updated!');
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            'task_status' => 'required|in:Pending,In Progress,Completed',
            'comment' => 'nullable|string|max:1000',
            'deliverables' => 'nullable|array',
            'deliverables.*.id' => 'nullable|exists:deliverables,id',
            'deliverables.*.description' => 'required|string|max:255',
            'deliverables.*.completed' => 'nullable|boolean',
            'deletedDeliverables' => 'nullable|string',
        ]);

        $task->update([
            'name' => $validated['task_name'],
            'status' => $validated['task_status'],
            'comment' => $validated['comment'],
        ]);

        // Delete deliverables
        if (!empty($validated['deletedDeliverables'])) {
            $idsToDelete = explode(',', $validated['deletedDeliverables']);
            Deliverable::whereIn('id', $idsToDelete)->delete();
        }

        // Update or create deliverables
        foreach ($validated['deliverables'] as $deliverableData) {
            Deliverable::updateOrCreate(
                ['id' => $deliverableData['id'] ?? null], // Checks if exists
                [
                    'task_id' => $task->id,
                    'description' => $deliverableData['description'],
                    'completed' => isset($deliverableData['completed']),
                ]
            );
        }

        return redirect()->back()->with('success', 'Task updated successfully!');
    }
    public function destroy(Task $task)
    
    {
        $task->delete();
        return redirect()->back()->with('success', 'Task deleted successfully!');
    }
}