<style>
    .form-card {
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border-radius: 1rem;
        padding: 1.5rem;
        background-color: #fff;
        font-size: 0.875rem;
    }
    .form-card label {
        font-weight: 500;
        margin-bottom: 0.25rem;
    }
    .form-card input.form-control {
        font-size: 0.85rem;
        padding: 0.4rem 0.6rem;
    }
    .form-check-label {
        font-size: 0.85rem;
    }
</style>

<div class="container mt-4">
    <div class="card form-card">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-4">
                    <label>Contact Person</label>
                    <input type="text" name="contact_person" class="form-control" value="{{ old('contact_person', $bookingOrder->contact_person ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label>Phone Number</label>
                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $bookingOrder->phone_number ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label>Project Manager</label>
                    <input type="text" name="project_manager" class="form-control" value="{{ old('project_manager', $bookingOrder->project_manager ?? '') }}" required>
                </div>

                <div class="col-md-4">
                    <label>Project Captain</label>
                    <input type="text" name="project_captain" class="form-control" value="{{ old('project_captain', $bookingOrder->project_captain ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label>Assistant Captain</label>
                    <input type="text" name="project_assistant_captain" class="form-control" value="{{ old('project_assistant_captain', $bookingOrder->project_assistant_captain ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label>Event Venue</label>
                    <input type="text" name="event_venue" class="form-control" value="{{ old('event_venue', $bookingOrder->event_venue ?? '') }}" required>
                </div>
                <div class="col-md-3">
                    <label>Set Down Date</label>
                    <input type="date" name="set_down_date" class="form-control" value="{{ old('set_down_date', $bookingOrder->set_down_date ?? '') }}" required>
                </div>
                <div class="col-md-3">
                    <label>Set Down Time</label>
                    <input type="time" name="set_down_time" class="form-control" value="{{ old('set_down_time', $bookingOrder->set_down_time ?? '') }}" required>
                </div>

                <div class="col-md-3">
                    <label>Set Up Time</label>
                    <input type="time" name="set_up_time" class="form-control" value="{{ old('set_up_time', $bookingOrder->set_up_time ?? '') }}" required>
                </div>
                <div class="col-md-3">
                    <label>Estimated Set Up Period</label>
                    <input type="text" name="estimated_set_up_period" class="form-control" value="{{ old('estimated_set_up_period', $bookingOrder->estimated_set_up_period ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label>Fabrication Preparation</label>
                    <input type="text" name="fabrication_preparation" class="form-control" value="{{ old('fabrication_preparation', $bookingOrder->fabrication_preparation ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label>Designated Truck</label>
                    <input type="text" name="logistics_designated_truck" class="form-control" value="{{ old('logistics_designated_truck', $bookingOrder->logistics_designated_truck ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label>Driver</label>
                    <input type="text" name="driver" class="form-control" value="{{ old('driver', $bookingOrder->driver ?? '') }}" required>
                </div>
                <div class="col-md-4">
                    <label>Loading Time</label>
                    <input type="time" name="time_of_loading_departure" class="form-control" value="{{ old('time_of_loading_departure', $bookingOrder->time_of_loading_departure ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label>Safety Gear Checker</label>
                    <input type="text" name="safety_gear_checker" class="form-control" value="{{ old('safety_gear_checker', $bookingOrder->safety_gear_checker ?? '') }}">
                </div>

                <div class="col-md-12">
                    <label>Set Down Team (comma-separated)</label>
                    <input type="text" name="set_down_team" class="form-control" value="{{ old('set_down_team') }}">
                </div>
                <div class="col-md-12">
                    <label>Pasting Team (comma-separated)</label>
                    <input type="text" name="pasting_team" class="form-control" value="{{ old('pasting_team') }}">
                </div>
                <div class="col-md-12">
                    <label>Technical Team (comma-separated)</label>
                    <input type="text" name="technical_team" class="form-control" value="{{ old('technical_team') }}">
                </div>

                <div class="col-md-4 mt-3 form-check">
                    <input type="hidden" name="loading_team_confirmed" value="0">
                    <input type="checkbox" class="form-check-input" name="loading_team_confirmed" value="1" {{ old('loading_team_confirmed', $bookingOrder->loading_team_confirmed ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label">Loading Team Confirmed</label>
                </div>

                <div class="col-md-4 mt-3 form-check">
                    <input type="hidden" name="printed_collateral_shared" value="0">
                    <input type="checkbox" class="form-check-input" name="printed_collateral_shared" value="1" {{ old('printed_collateral_shared', $bookingOrder->printed_collateral_shared ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label">Printed Collateral Shared</label>
                </div>

                <div class="col-md-4 mt-3 form-check">
                    <input type="hidden" name="approved_mock_up_shared" value="0">
                    <input type="checkbox" class="form-check-input" name="approved_mock_up_shared" value="1" {{ old('approved_mock_up_shared', $bookingOrder->approved_mock_up_shared ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label">Mock Up Shared</label>
                </div>
            </div>
        </div>
    </div>
</div>
