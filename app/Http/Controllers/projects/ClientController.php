<?php

namespace App\Http\Controllers\projects;
use App\Http\Controllers\Controller;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Display a list of all clients
    public function index()
    {
        $clients = Client::latest()->paginate(10); // Added pagination
        return view('projects.client', compact('clients'));
    }

    // Store a new client
    public function store(Request $request)
    {
        $validated = $request->validate([
            'FullName' => 'required|string|max:255',
            'ContactPerson' => 'nullable|string|max:255',
            'Email' => 'nullable|email|max:255',
            'Phone' => 'nullable|string|max:20',
            'AltContact' => 'nullable|string|max:20',
            'Address' => 'nullable|string|max:255',
            'City' => 'nullable|string|max:100',
            'County' => 'nullable|string|max:100',
            'PostalAddress' => 'nullable|string|max:50',
            'CustomerType' => 'nullable|string|max:50',
            'LeadSource' => 'nullable|string|max:100',
            'PreferredContact' => 'nullable|string|max:100',
            'Industry' => 'nullable|string|max:100',
            'CreatedBy' => 'required|integer',
        ]);

        try {
        Client::create($validated);
        return redirect()->route('clients.index')->with('success', 'Client added successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to add client. Please try again.');
        }
    }

    // Show form for editing
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('projects.client', compact('client')); // Fixed view path
    }

    // Update existing client
    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'FullName' => 'required|string|max:255',
            'ContactPerson' => 'nullable|string|max:255',
            'Email' => 'nullable|email|max:255',
            'Phone' => 'nullable|string|max:20',
            'AltContact' => 'nullable|string|max:20',
            'Address' => 'nullable|string|max:255',
            'City' => 'nullable|string|max:100',
            'County' => 'nullable|string|max:100',
            'PostalAddress' => 'nullable|string|max:50',
            'CustomerType' => 'nullable|string|max:50',
            'LeadSource' => 'nullable|string|max:100',
            'PreferredContact' => 'nullable|string|max:100',
            'Industry' => 'nullable|string|max:100',
        ]);

        try {
        $client->update($validated);
        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to update client. Please try again.');
        }
    }

    // Delete client
    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();
            return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete client. Please try again.');
        }
    }

    // Show client details (for AJAX requests)
    public function show($id)
    {
        $client = Client::findOrFail($id);
        return response()->json($client);
    }
}
