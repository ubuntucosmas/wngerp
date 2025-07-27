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
        $clients = Client::latest()->simplePaginate(10);
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

        Client::create($validated);

        return redirect()->route('clients.index')->with('success', 'Client added successfully.');
    }

    // Show form for editing (optional for now)
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('clients.edit', compact('client'));
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

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

    // Show client details
    public function show($id)
    {
        $client = Client::findOrFail($id);
        return view('projects.client-show', compact('client'));
    }

    // Delete client
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')
                         ->with('success', 'Client deleted successfully.');
    }
}
