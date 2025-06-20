<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index()
    {
        $enquiries = Enquiry::orderBy('date_received', 'desc')->paginate(10);
        $statuses = ['Open', 'Quoted', 'Approved', 'Declined'];
        return view('projects.Enquiry.index', compact('enquiries', 'statuses'));
    }

    public function create()
    {
        $statuses = ['Open', 'Quoted', 'Approved', 'Declined'];
        return view('projects.Enquiry.create', compact('statuses'));
    }

    public function show(Enquiry $enquiry)
    {
        return view('projects.Enquiry.show', compact('enquiry'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date_received' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:date_received',
            'client_name' => 'required|string|max:255',
            'project_name' => 'nullable|string|max:255',
            'project_deliverables' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'status' => 'required|in:Open,Quoted,Approved,Declined',
            'assigned_po' => 'nullable|string|max:255',
            'follow_up_notes' => 'nullable|string',
            'project_id' => 'nullable|string|max:255',
        ]);

        Enquiry::create($validated);

            // Redirect back with success message
        return redirect()->back()->with('success', 'Enquiry created successfully.');
    }

    public function edit(Enquiry $enquiry)
    {
        $statuses = ['Open', 'Quoted', 'Approved', 'Declined'];
        return view('projects.Enquiry.edit', compact('enquiry', 'statuses'));
    }

    public function update(Request $request, Enquiry $enquiry)
    {
        $validated = $request->validate([
            'date_received' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:date_received',
            'client_name' => 'required|string|max:255',
            'project_name' => 'nullable|string|max:255',
            'project_deliverables' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'status' => 'required|in:Open,Quoted,Approved,Declined',
            'assigned_po' => 'nullable|string|max:255',
            'follow_up_notes' => 'nullable|string',
            'project_id' => 'nullable|string|max:255',
        ]);

        $enquiry->update($validated);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Enquiry updated successfully.');
    }

    public function destroy(Enquiry $enquiry)
    {
        $enquiry->delete();
        return redirect()->route('enquiries.index');
    }
}