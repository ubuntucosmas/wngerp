@extends('layouts.master')

@section('title', 'Upload Test')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>File Upload Test</h4>
                    <small class="text-muted">Use this page to test file upload functionality in production</small>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>System Information:</strong><br>
                        PHP Version: {{ phpversion() }}<br>
                        Upload Max Filesize: {{ ini_get('upload_max_filesize') }}<br>
                        Post Max Size: {{ ini_get('post_max_size') }}<br>
                        Max Execution Time: {{ ini_get('max_execution_time') }}s<br>
                        Memory Limit: {{ ini_get('memory_limit') }}<br>
                        Storage Disk: {{ config('filesystems.default') }}
                    </div>

                    <form id="testUploadForm" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="test_file" class="form-label">Select Test File</label>
                            <input type="file" class="form-control" id="test_file" name="test_file" required>
                            <div class="form-text">Maximum file size: 50MB</div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" id="uploadBtn">
                            <i class="bi bi-upload me-1"></i>
                            Test Upload
                        </button>
                        
                        <button type="button" class="btn btn-info" id="checkStorageBtn">
                            <i class="bi bi-folder-check me-1"></i>
                            Check Storage
                        </button>
                    </form>

                    <div id="results" class="mt-4" style="display: none;">
                        <h5>Test Results:</h5>
                        <pre id="resultContent" class="bg-light p-3 rounded"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    const form = $('#testUploadForm');
    const uploadBtn = $('#uploadBtn');
    const results = $('#results');
    const resultContent = $('#resultContent');

    form.on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        uploadBtn.prop('disabled', true).html('<i class="bi bi-hourglass-split me-1"></i> Uploading...');
        
        $.ajax({
            url: '{{ route("test.upload.store") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                results.show();
                resultContent.text(JSON.stringify(response, null, 2));
                
                if (response.success) {
                    Swal.fire('Success!', 'Test upload completed successfully', 'success');
                } else {
                    Swal.fire('Failed!', 'Test upload failed: ' + response.error, 'error');
                }
            },
            error: function(xhr) {
                results.show();
                let errorData = {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText
                };
                
                try {
                    errorData.responseJSON = JSON.parse(xhr.responseText);
                } catch (e) {
                    // Response is not JSON
                }
                
                resultContent.text(JSON.stringify(errorData, null, 2));
                Swal.fire('Error!', 'Upload test failed with status: ' + xhr.status, 'error');
            },
            complete: function() {
                uploadBtn.prop('disabled', false).html('<i class="bi bi-upload me-1"></i> Test Upload');
            }
        });
    });

    $('#checkStorageBtn').on('click', function() {
        $.get('/session/check', function(data) {
            results.show();
            resultContent.text('Storage check completed. User authenticated: ' + data.authenticated);
        }).fail(function(xhr) {
            results.show();
            resultContent.text('Storage check failed: ' + xhr.status + ' - ' + xhr.statusText);
        });
    });
});
</script>
@endsection