@extends('layouts.master')

@section('title', 'Booking Order')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

    :root {
        --primary-accent: #007bff;
        --text-primary: #212529;
        --text-secondary: #6c757d;
        --background-color: #f8f9fa;
        --card-background: #ffffff;
        --card-border-color: #e9ecef;
    }

    body {
        background-color: var(--background-color);
        color: var(--text-primary);
        font-family: 'Poppins', sans-serif;
    }

    .header-actions .btn {
        font-size: 0.9rem;
        padding: 0.4rem 0.8rem;
    }

    .booking-order-card {
        background-color: var(--card-background);
        border: 1px solid var(--card-border-color);
        border-radius: 12px;
        margin-top: 1.5rem;
    }

    .booking-order-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--card-border-color);
    }

    .booking-order-header h5 {
        font-weight: 600;
        margin: 0;
    }

    .booking-order-body {
        padding: 1.5rem;
    }

    .info-section {
        margin-bottom: 2rem;
    }

    .info-section h6 {
        font-weight: 600;
        color: var(--primary-accent);
        margin-bottom: 1rem;
        border-bottom: 2px solid var(--primary-accent);
        padding-bottom: 0.5rem;
        display: inline-block;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .info-item strong {
        display: block;
        color: var(--text-secondary);
        font-weight: 500;
        font-size: 0.9rem;
    }

    .team-list {
        list-style: none;
        padding: 0;
    }

    .team-list li {
        background-color: #e9ecef;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        margin-bottom: 0.5rem;
    }

</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3 header-actions">
        <div>
            <a href="{{ route('projects.files.index', ['project' => $project->id]) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to Files
            </a>
        </div>
        @if($bookingOrders->isNotEmpty())
            <div class="btn-group">
                <a href="{{ route('projects.booking-order.edit', [$project->id, $bookingOrders->first()->id]) }}" class="btn btn-outline-primary"><i class="bi bi-pencil me-1"></i> Edit</a>
                <a href="{{ route('projects.booking-order.download', $project->id) }}" class="btn btn-outline-secondary"><i class="bi bi-download me-1"></i> Download</a>
                <a href="{{ route('projects.booking-order.print', $project->id) }}" target="_blank" class="btn btn-outline-secondary"><i class="bi bi-printer me-1"></i> Print</a>
            </div>
        @else
            <a href="{{ route('projects.booking-order.create', ['project' => $project->id]) }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> New Booking Order
            </a>
        @endif
    </div>

    @forelse($bookingOrders as $order)
        <div class="booking-order-card">
            <div class="booking-order-header">
                <h5>Booking Order: {{ $order->project_name }}</h5>
            </div>
            <div class="booking-order-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="info-section">
                            <h6>Key Information</h6>
                            <div class="info-grid">
                                <div class="info-item"><strong>Contact</strong> {{ $order->contact_person }}</div>
                                <div class="info-item"><strong>Phone</strong> {{ $order->phone_number }}</div>
                                <div class="info-item"><strong>Project Manager</strong> {{ $order->project_manager }}</div>
                                <div class="info-item"><strong>Captain / Asst.</strong> {{ $order->project_captain }} / {{ $order->project_assistant_captain ?: 'N/A' }}</div>
                                <div class="info-item"><strong>Event Venue</strong> {{ $order->event_venue }}</div>
                            </div>
                        </div>

                        <div class="info-section">
                            <h6>Event Schedule</h6>
                            <div class="info-grid">
                                <div class="info-item"><strong>Set Down</strong> {{ $order->set_down_date }} {{ $order->set_down_time }}</div>
                                <div class="info-item"><strong>Set Up Time</strong> {{ $order->set_up_time }}</div>
                                <div class="info-item"><strong>Est. Period</strong> {{ $order->estimated_set_up_period }}</div>
                                <div class="info-item"><strong>Load Departure</strong> {{ $order->time_of_loading_departure }}</div>
                                <div class="info-item"><strong>Loading Confirmed</strong> <span class="badge bg-{{ $order->loading_team_confirmed ? 'success' : 'secondary' }}">{{ $order->loading_team_confirmed ? 'Yes' : 'No' }}</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="info-section">
                            <h6>Teams</h6>
                            @foreach(['set_down' => 'Set Down', 'pasting' => 'Pasting', 'technical' => 'Technical'] as $type => $title)
                                <strong>{{ $title }} Team</strong>
                                <ul class="team-list mt-2 mb-3">
                                    @forelse($order->teams->where('team_type', $type) as $member)
                                        <li>{{ $member->member_name }}</li>
                                    @empty
                                        <li>No members assigned.</li>
                                    @endforelse
                                </ul>
                            @endforeach
                        </div>

                        <div class="info-section">
                            <h6>Logistics & Safety</h6>
                            <div class="info-grid">
                                <div class="info-item"><strong>Truck</strong> {{ $order->logistics_designated_truck ?: 'N/A' }}</div>
                                <div class="info-item"><strong>Driver</strong> {{ $order->driver ?: 'N/A' }}</div>
                                <div class="info-item"><strong>Gear Checker</strong> {{ $order->safety_gear_checker ?: 'N/A' }}</div>
                                <div class="info-item"><strong>Fabrication</strong> {{ $order->fabrication_preparation ?: 'N/A' }}</div>
                                <div class="info-item"><strong>Collateral Shared</strong> <span class="badge bg-{{ $order->printed_collateral_shared ? 'success' : 'secondary' }}">{{ $order->printed_collateral_shared ? 'Yes' : 'No' }}</span></div>
                                <div class="info-item"><strong>Mockup Shared</strong> <span class="badge bg-{{ $order->approved_mock_up_shared ? 'success' : 'secondary' }}">{{ $order->approved_mock_up_shared ? 'Yes' : 'No' }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="bi bi-file-earmark-text-fill" style="font-size: 4rem; color: #e9ecef;"></i>
            <h4 class="mt-3">No Booking Order Found</h4>
            <p class="text-secondary">A booking order has not been created for this project yet.</p>
        </div>
    @endforelse
</div>
@endsection
