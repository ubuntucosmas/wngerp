<?php

namespace App\Http\Controllers\projects;
use App\Http\Controllers\Controller;

use App\Models\Task;
use App\Models\Deliverable;
use App\Models\Phase;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PhaseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Phase::create($request->all());

        return back()->with('success', 'Phase added successfully.');
    }

    public function edit($id)
    {
        $phase = Phase::findOrFail($id);
        return view('projects.phases.edit', compact('phase'));
    }

    public function update(Request $request, $id)
    {
        $phase = Phase::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            // 'status' => 'required|in:Pending,In Progress,Completed',
            // 'progress' => 'required|integer|min:0|max:100',
        ]);

        $phase->update($request->only(['title', 'description', 'start_date', 'end_date']));

        return redirect()->back()->with('success', 'Phase updated successfully.');
    }

    public function destroy($id)
    {
        $phase = Phase::findOrFail($id);   
        $phase->delete();

        return redirect()->route('projects.index')->with('success', 'Phase deleted successfully.');
    }

    public function storeTask(Request $request)
    {
        $validated = $request->validate([
            'phase_id' => 'required|exists:phases,id',
            'name' => 'required|string|max:255',
            'status' => 'required|in:Pending,In Progress,Completed',
            'assigned_to' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'deliverables' => 'nullable|array',
            'comments.*' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:pdf,xlsx,xls,doc,docx,ppt,pptx,jpg,jpeg,png',
        ]);
    
        DB::transaction(function () use ($request) {
            // Create task
            $task = new Task();
            $task->phase_id = $request->phase_id;
            $task->name = $request->name;
            $task->status = $request->status;
            $task->assigned_to = $request->assigned_to;
            $task->start_date = Carbon::parse($request->start_date)->format('Y-m-d');
            $task->due_date = Carbon::parse($request->due_date)->format('Y-m-d');
            $task->description = $request->description;
            $task->user_id = auth()->id();
            $task->save();
    
            // Save deliverables if provided
            if ($request->has('deliverables')) {
                foreach ($request->deliverables as $item) {
                    Deliverable::create([
                        'task_id' => $task->id,
                        'item' => $item,
                        'done' => false,
                    ]);
                }
            }
    
            // Save comments if provided
            if ($request->has('comments')) {
                foreach ($request->comments as $commentText) {
                    if (!empty($commentText)) {
                        $task->comments()->create([
                            'comment' => $commentText,
                            'user_id'=> auth()->user()->id,      
                        ]);
                    }
                }
            }

    
            // Save attachments if uploaded
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('tasks', 'public');// returns e.g., 'tasks/abc123.pdf'
            
                    $task->attachments()->create([
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                    ]);
                }
            }
            
        });
    
        return back()->with('success', 'Task added successfully.');
    }

    public function updateTask(Request $request, Task $task)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:Pending,In Progress,Complete',
            'assigned_to' => 'nullable|string|max:255',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'deliverables' => 'nullable|array',
            'deliverables.*' => 'nullable|string|max:255',
            'comments' => 'nullable|array',
            'comments.*' => 'nullable|string|max:1000',
            'attachments.*' => 'nullable|file|mimes:pdf,xlsx,xls,doc,docx,ppt,pptx,jpg,jpeg,png|max:10240',
            'deliverables' => 'nullable|array',
            'deliverables.*' => 'nullable|string|max:255',
            'comments' => 'nullable|array',
            'comments.*' => 'nullable|string|max:1000',
            'attachments.*' => 'nullable|file|mimes:pdf,xlsx,xls,doc,docx,ppt,pptx,jpg,jpeg,png|max:10240',
    ]);
    try {
    DB::transaction(function () use ($request, $task) {
        // Update base task fields
        $task->update([
            'name' => $request->name,
            'status' => $request->status,
            'assigned_to' => $request->assigned_to,
            'start_date' => $request->start_date,
            'due_date' => $request->due_date,
            'description' => $request->description,
        ]);

        // Replace deliverables
        if ($request->filled('deliverables')) {
            $task->deliverables()->delete();
            foreach ($request->deliverables as $item) {
                if (!empty($item)) {
                    Deliverable::create([
                        'task_id' => $task->id,
                        'item' => $item,
                        'done' => false,
                    ]);
                }
            }
        }

        // Append new comments
        if ($request->filled('comments')) {
            foreach ($request->comments as $commentText) {
                if (!empty($commentText)) {
                    $task->comments()->create([
                        'comment' => $commentText,
                        'user_id' => auth()->id(),
                    ]);
                }
            }
        }

        // Append new attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tasks', 'public');
                $task->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        // Optional phase status update
        if ($task->phase) {
            $task->phase->updateStatusFromTasks();
        }
    });
        } catch (\Exception $e) {
            \Log::error('Task update failed', ['error' => $e->getMessage()]);
            return back()->withErrors('Something went wrong.');
        }

        return back()->with('success', 'Task updated successfully.');
}
    

    public function updateDeliverables(Request $request, Task $task)
    {
        // Clear existing deliverables
        $task->deliverables()->delete();

        // Save submitted deliverables with done status
        if (is_array($request->deliverables)) {
            foreach ($request->deliverables as $entry) {
                Deliverable::create([
                    'task_id' => $task->id,
                    'item' => $entry['item'],
                    'done' => isset($entry['done']) && $entry['done'] == '1',
                ]);
            }
        }

        // Update task status based on deliverables
        $task->load('deliverables');
        $allDone = $task->deliverables->every(fn($d) => $d->done);
        $task->status = $allDone ? 'Complete' : 'In Progress';
        $task->save();
        $task->phase->updateStatusFromTasks(); // updates phase ststus dynamically

        return back()->with('success', 'Checklist saved.');
    }


    public function deleteAttachment(Request $request, $id)
    {
        $attachment = Attachment::findOrFail($id);
        
        // Delete the file from storage
        if (file_exists(storage_path('app/public/' . $attachment->file_path))) {
            unlink(storage_path('app/public/' . $attachment->file_path));
        }
        
        // Delete the database record
        $attachment->delete();
        
        return back()->with('success', 'File deleted successfully.');
    }

    public function storeAttachment(Request $request, $phaseId)
    {
        $validated = $request->validate([
            'attachments.*' => 'required|file|max:10240', // 10MB max per file
            'task_id' => 'required|exists:tasks,id',
        ]);

        $task = Task::findOrFail($validated['task_id']);

        foreach ($request->file('attachments') as $file) {
            // Get file details
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;

            // Store file in public storage
            $path = $file->storeAs('tasks', $fileName, 'public');

            // Create attachment record
            $attachment = new Attachment([
                'file_path' => $path,
                'file_name' => $originalName,
            ]);

            $task->attachments()->save($attachment);
        }

        return response()->json([
            'success' => true,
            'message' => 'Files uploaded successfully.'
        ]);
    }


    public function showPhase($id)
    {
        $phase = Phase::with('tasks.deliverables', 'tasks.user')->findOrFail($id);
        return view('projects.phases.show', compact('phase'));
    }

    public function deleteTask(Task $task)
    {
        $task->delete();
        return back()->with('success', 'Task deleted successfully.');
    }
}
