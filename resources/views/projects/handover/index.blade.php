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
                                        <!-- Sample row - replace with dynamic data from your backend -->
                                        <tr>
                                            <td>1</td>
                                            <td>Sample Client</td>
                                            <td>John Doe</td>
                                            <td>2025-07-04</td>
                                            <td>All good, thanks!</td>
                                        </tr>
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
        // Any initialization code can go here
    });

    function recordAcknowledgment() {
        const clientName = document.querySelector('.form-control-plaintext').textContent.trim();
        const contactPerson = document.getElementById('contactPerson').value.trim();
        const acknowledgmentDate = document.getElementById('acknowledgmentDate').value;
        const clientComment = document.getElementById('clientComment').value.trim();

        if (clientName === 'Client Name Not Found' && !contactPerson) {
            alert('Please enter a contact person since client name is not available');
            return;
        }

        const acknowledgedBy = contactPerson || clientName;
        
        // Create new table row
        const tableBody = document.getElementById('handoverTableBody');
        const newRow = tableBody.insertRow(0); // Insert at the top
        
        // Add cells to the new row
        const rowCount = tableBody.rows.length;
        newRow.innerHTML = `
            <td>${rowCount}</td>
            <td>${clientName === 'Client Name Not Found' ? 'N/A' : clientName}</td>
            <td>${contactPerson || 'N/A'}</td>
            <td>${acknowledgmentDate}</td>
            <td>${clientComment || 'No comments'}</td>
        `;

        // Show success message
        const toast = `
            <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header bg-success text-white">
                        <strong class="me-auto">Success</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        Handover acknowledgment saved successfully!
                    </div>
                </div>
            </div>
        `;
        
        // Add toast to body
        const toastContainer = document.createElement('div');
        toastContainer.innerHTML = toast;
        document.body.appendChild(toastContainer);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toastContainer.remove();
        }, 3000);

        // Reset form
        document.getElementById('contactPerson').value = '';
        document.getElementById('clientComment').value = '';
    }
</script>
@endpush
