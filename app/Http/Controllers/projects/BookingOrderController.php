<?php

namespace App\Http\Controllers\projects;
use App\Http\Controllers\Controller;

use App\Models\BookingOrder;
use App\Models\Project;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf;

class BookingOrderController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function index(Project $project)
    {
        // Check if user can view this project
        $this->authorize('view', $project);

        $bookingOrder = BookingOrder::where('project_id', $project->id)
            ->with('teams')
            ->latest()
            ->first();
        return view('projects.bookingOrder.index', compact('project', 'bookingOrder'));
    }

    public function create(Project $project)
    {
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);

        return view('projects.bookingOrder.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);

        $data = $this->prepareBookingOrderData($request);
        $data['project_id'] = $project->id;

        // Create the BookingOrder
        $bookingOrder = BookingOrder::create($data);

        // Store the team members
        $this->storeTeamMembers($bookingOrder, $request);

        return redirect()
            ->route('projects.logistics.booking-orders.index', $project)
            ->with('success', 'Booking Order created successfully.');
    }

    public function edit(Project $project, BookingOrder $bookingOrder)
    {
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);

        return view('projects.bookingOrder.edit', compact('project', 'bookingOrder'));
    }

    public function update(Request $request, Project $project, BookingOrder $bookingOrder)
    {
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);

        $data = $this->prepareBookingOrderData($request);
        $bookingOrder->update($data);

        // Replace old team members with new ones
        $bookingOrder->teams()->delete();
        $this->storeTeamMembers($bookingOrder, $request);

        return redirect()
            ->route('projects.logistics.booking-orders.index', $project)
            ->with('success', 'Booking Order updated successfully.');
    }

    // === Helper Methods ===

    private function validateData(Request $request): array
    {
        return $request->validate([
            'project_name' => 'required|string',
            'contact_person' => 'required|string',
            'project_manager' => 'required|string',
            'project_captain' => 'required|string',
            'project_assistant_captain' => 'required|string',
            'phone_number' => 'required|string',
            'set_down_date' => 'required|date',
            'set_down_time' => 'required|string',
            'event_venue' => 'required|string',
            'set_up_time' => 'required|string',
            'estimated_set_up_period' => 'required|string',
            'set_down_team' => 'nullable|string',
            'pasting_team' => 'nullable|string',
            'technical_team' => 'nullable|string',
            'logistics_designated_truck' => 'required|string',
            'driver' => 'required|string',
            'loading_team_confirmed' => 'required|boolean',
            'printed_collateral_shared' => 'required|boolean',
            'approved_mock_up_shared' => 'required|boolean',
            'fabrication_preparation' => 'required|string',
            'time_of_loading_departure' => 'required|string',
            'safety_gear_checker' => 'required|string',
        ]);
    }

    private function prepareBookingOrderData(Request $request): array
    {
        // Prepare the validated data
        $data = $this->validateData($request);

        // Convert the team fields to JSON
        $data['set_down_team'] = json_encode(array_filter(array_map('trim', explode(',', $request->input('set_down_team', '')))));
        $data['pasting_team'] = json_encode(array_filter(array_map('trim', explode(',', $request->input('pasting_team', '')))));
        $data['technical_team'] = json_encode(array_filter(array_map('trim', explode(',', $request->input('technical_team', '')))));

        return $data;
    }

    private function storeTeamMembers(BookingOrder $order, Request $request)
    {
        $teamTypes = [
            'set_down_team' => 'set_down',
            'pasting_team' => 'pasting',
            'technical_team' => 'technical',
        ];

        foreach ($teamTypes as $inputField => $type) {
            $members = array_filter(array_map('trim', explode(',', $request->input($inputField, ''))));

            foreach ($members as $member) {
                $order->teams()->create([
                    'team_type' => $type,
                    'member_name' => $member, 
                ]);
            }
        }
    }


    public function downloadBookingOrder(Project $project)
    {
        // Check if user can view this project
        $this->authorize('view', $project);

        $order = $project->bookingOrder()->latest()->first();

        if (!$order) {
            abort(404, 'No booking order found for this project.');
        }
        $pdf = Pdf::loadView('projects.templates.booking-order', compact('project', 'order'));
        return $pdf->download('booking-order-' . $project->id . '.pdf');
    }
    
    public function printBookingOrder(Project $project)
    {
        // Check if user can view this project
        $this->authorize('view', $project);

        $order = $project->bookingOrder()->latest()->first();

        if (!$order) {
            abort(404, 'No booking order found for this project.');
        }

        $pdf = Pdf::loadView('projects.templates.booking-order', compact('project', 'order'));
        return $pdf->stream('booking-order-' . $project->id . '.pdf');
    }

    /**
     * Remove the specified booking order
     */
    public function destroy(Project $project, BookingOrder $bookingOrder)
    {
        // Check if user can edit this project (not just view)
        $this->authorize('edit', $project);
        
        $bookingOrder->delete();
        return redirect()->back()->with('success', 'Booking order deleted successfully.');
    }


}
