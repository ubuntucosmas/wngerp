
<div class="modal fade" id="newProjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <form action="{{ route('projects.store') }}" method="POST" class="modern-form">
            @csrf
            <div class="modal-content shadow rounded-4 border-0">
                <div class="modal-header border-0">
                    <h6 class="modal-title fw-semibold text-muted">New Project</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="text" name="project_id" class="form-control futuristic-input mb-2" placeholder="Project ID (leave blank for auto-generation)">
                    <div class="form-text mb-2 small text-muted">Optional - leave blank to auto-generate (e.g., WNG0125001)</div>

                    <select name="name" class="form-select futuristic-input mb-2" required onchange="updateClientName(this)">
                        <option value="" disabled selected>Select Project</option>
                        @foreach ($enquiryprojects as $enquiryproject) 
                            <option value="{{ $enquiryproject->project_name }}" data-name="{{ $enquiryproject->project_name }}">{{ $enquiryproject->project_name }}</option>
                        @endforeach
                    </select>

                    <input type="hidden" name="client_name" class="form-control" readonly>

                    <select name="client_id" class="form-select futuristic-input mb-2" required onchange="updateClientName(this)">
                        <option value="" disabled selected>Select Client</option>
                        @foreach ($clients as $client) 
                            <option value="{{ $client->ClientID }}" data-name="{{ $client->FullName }}">{{ $client->FullName }}</option>
                        @endforeach
                    </select>

                    <input type="text" name="venue" class="form-control futuristic-input mb-2" placeholder="Venue" required>

                    <input type="date" name="start_date" class="form-control futuristic-input mb-2" required>
                    <input type="date" name="end_date" class="form-control futuristic-input mb-2" required>
                </div>

                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-4 shadow-sm">Create</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function updateClientName(select) {
        const clientNameInput = document.querySelector('input[name="client_name"]');
        const selectedOption = select.options[select.selectedIndex];
        clientNameInput.value = selectedOption ? selectedOption.getAttribute('data-name') : '';
    }
</script>
