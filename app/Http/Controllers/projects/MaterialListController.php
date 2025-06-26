<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Material;

class MaterialListController extends Controller
{
    public function show(Project $project)
    {
        $materials = $project->materials()->latest()->get()->groupBy('item');
        return view('projects.material-list.show', compact('project', 'materials'));
    }

    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'item' => 'required|string|max:255',
            'materials' => 'required|array',
            'materials.*.material' => 'required|string|max:255',
            'materials.*.specification' => 'nullable|string',
            'materials.*.unit' => 'nullable|string|max:50',
            'materials.*.quantity' => 'nullable|numeric',
            'materials.*.notes' => 'nullable|string',
            'materials.*.design_reference' => 'nullable|url',
            'materials.*.approved_by' => 'nullable|string|max:255',
        ]);

        foreach ($validated['materials'] as $mat) {
            $project->materials()->create([
                'item' => $validated['item'],
                'material' => $mat['material'],
                'specification' => $mat['specification'] ?? null,
                'unit' => $mat['unit'] ?? null,
                'quantity' => $mat['quantity'] ?? null,
                'notes' => $mat['notes'] ?? null,
                'design_reference' => $mat['design_reference'] ?? null,
                'approved_by' => $mat['approved_by'] ?? null,
            ]);
        }

        return redirect()->back()->with('success', 'Materials added successfully.');
    }

    public function index(Project $project)
    {
        //$materials = Material::all();
        return view('projects.files.material', compact('project'));
    }

    public function edit(Project $project, $item)
    {
        $materials = $project->materials()->where('item', $item)->get();
        return view('projects.material-list.edit-item', compact('project', 'item', 'materials'));
    }

    public function update(Request $request, Project $project, $item)
    {
        $validated = $request->validate([
            'item' => 'required|string|max:255',
            'materials' => 'required|array|min:1',
            'materials.*.material' => 'required|string|max:255',
            'materials.*.specification' => 'nullable|string',
            'materials.*.unit' => 'nullable|string|max:50',
            'materials.*.quantity' => 'nullable|numeric',
            'materials.*.notes' => 'nullable|string',
            'materials.*.design_reference' => 'nullable|url',
            'materials.*.approved_by' => 'nullable|string|max:255',
        ]);

        $project->materials()->where('item', $item)->delete();

        foreach ($validated['materials'] as $mat) {
            $project->materials()->create([
                'item' => $validated['item'],
                'material' => $mat['material'],
                'specification' => $mat['specification'] ?? null,
                'unit' => $mat['unit'] ?? null,
                'quantity' => $mat['quantity'] ?? null,
                'notes' => $mat['notes'] ?? null,
                'design_reference' => $mat['design_reference'] ?? null,
                'approved_by' => $mat['approved_by'] ?? null,
            ]);
        }

        return redirect()->route('projects.material-list.show', $project)
            ->with('success', 'Material list updated successfully.');
    }

    public function destroy(Project $project, $item)
    {
        $project->materials()->where('item', $item)->delete();

        return redirect()->route('projects.material-list.show', $project)
            ->with('success', 'Item and its materials deleted.');
    }
}

