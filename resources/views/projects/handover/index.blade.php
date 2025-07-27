@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title mb-0">Client Handover - {{ $project->name }}</h4>
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('projects.index') }}">Projects</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('projects.files.index', $project) }}">Files</a></li>
                        <li class="breadcrumb-item active">Client Handover</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4 mt-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <img src="{{ asset('images/handover.jpg') }}" alt="Handover" class="img-fluid" style="max-height: 200px;">
                        <h4 class="mt-3 mb-1">Project Handover Sign-Off</h4>
                        <p class="text-muted">Scan this QR code to complete the project handover process and sign off on the deliverables</p>
                    </div>
                    <hr>
                    <div class="mb-4">
                        <h5 class="mb-3 fw-semibold">New Handover Acknowledgment</h5>
                        <div class="bg-light p-4 rounded border">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label small mb-1">Client Name</label>
                                        <div class="form-control-plaintext fw-semibold">
                                            {{ $project->client->FullName ?? 'Client Name Not Found' }}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="contactPerson" class="form-label small mb-1">Contact Person</label>
                                        <input type="text" class="form-control form-control-sm" id="contactPerson" 
                                            placeholder="If different from client" value="{{ $project->client->ContactPerson ?? '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="acknowledgmentDate" class="form-label small mb-1">Acknowledgment Date</label>
                                        <input type="date" class="form-control form-control-sm" id="acknowledgmentDate" value="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="clientComment" class="form-label small mb-1">Client Comments</label>
                                        <input type="text" class="form-control form-control-sm" id="clientComment" 
                                            placeholder="Any feedback from client">
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <button type="button" class="btn btn-primary btn-sm px-4" onclick="recordAcknowledgment()">
                                            <i class="fas fa-check me-1"></i> Save Acknowledgment
                                        </button>
                                    </div>
                                </div>
                            </div>


                        <!-- End Acknowledgment Section -->

                        <!-- Handover Records Table -->
                        <div class="mt-5">
                            <h5 class="mb-3 fw-semibold">Handover Records</h5>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Client Name</th>
                                            <th>Contact Person</th>
                                            <th>Date</th>
                                            <th>Comments</th>
                                        </tr>
                                    </thead>
                                    <tbody id="handoverTableBody">
                                        @foreach ($reports as $report)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $report->client_name }}</td>
                                                <td>{{ $report->contact_person }}</td>
                                                <td>{{ $report->formatted_date }}</td>
                                                <td>{{ $report->client_comments }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load existing handover records
    loadHandoverRecords();
});

function loadHandoverRecords() {
    fetch(`{{ route('projects.handover.data', $project) }}`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('handoverTableBody');
            tableBody.innerHTML = '';
            
            // Check if data exists and is an array
            const reports = data.data || [];
            
            reports.forEach((report, index) => {
                const row = tableBody.insertRow();
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${report.client_name || 'N/A'}</td>
                    <td>${report.contact_person || 'N/A'}</td>
                    <td>${report.acknowledgment_date || 'N/A'}</td>
                    <td>${report.client_comments || 'N/A'}</td>
                `;
            });
        })
        .catch(error => {
            console.error('Error loading handover records:', error);
            showToast('error', 'Failed to load handover records');
        });
}

function showToast(type, message) {
    const toast = `
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-${type === 'success' ? 'success' : 'danger'} text-white">
                    <strong class="me-auto">${type === 'success' ? 'Success' : 'Error'}</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', toast);
    setTimeout(() => {
        document.querySelector('.toast').classList.remove('show');
    }, 5000);
}

function recordAcknowledgment() {
    const clientName = document.querySelector('.form-control-plaintext').textContent.trim();
    const contactPerson = document.getElementById('contactPerson').value.trim();
    const acknowledgmentDate = document.getElementById('acknowledgmentDate').value;
    const clientComment = document.getElementById('clientComment').value.trim();

    if (clientName === 'Client Name Not Found' && !contactPerson) {
        alert('Please enter a contact person since client name is not available');
        return;
    }

    const formData = {
        client_name: clientName,
        contact_person: contactPerson,
        acknowledgment_date: acknowledgmentDate,
        client_comments: clientComment
    };

    fetch(`{{ route('projects.handover.store', $project) }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear form
            document.getElementById('contactPerson').value = '';
            document.getElementById('clientComment').value = '';
            
            // Reload the table
            loadHandoverRecords();
            
            // Show success message
            showToast('success', 'Handover acknowledgment saved successfully!');
        } else {
            showToast('error', data.message || 'Failed to save handover acknowledgment');
        }
    })
    .catch(error => {
        showToast('error', 'An error occurred while saving the acknowledgment');
        console.error('Error:', error);
    });
}
</script>
@endpush
