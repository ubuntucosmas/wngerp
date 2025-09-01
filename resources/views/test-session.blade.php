@extends('layouts.master')

@section('title', 'Session Test')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Session Expiration Test</h4>
                </div>
                <div class="card-body">
                    <p>This page helps test the session expiration functionality.</p>
                    
                    <div class="mb-3">
                        <strong>Current Session Info:</strong><br>
                        User: {{ auth()->user()->name ?? 'Not authenticated' }}<br>
                        Session ID: {{ session()->getId() }}<br>
                        Session Lifetime: {{ config('session.lifetime') }} minutes
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <button id="testAjax" class="btn btn-primary mb-2">Test AJAX Request</button><br>
                            <button id="checkSession" class="btn btn-info mb-2">Check Session Status</button><br>
                            <button id="extendSession" class="btn btn-success mb-2">Extend Session</button><br>
                            <button id="simulateExpiry" class="btn btn-warning mb-2">Simulate Session Expiry</button>
                        </div>
                        <div class="col-md-6">
                            <div id="testResults" class="border p-3" style="min-height: 200px; background-color: #f8f9fa;">
                                <strong>Test Results:</strong><br>
                                <div id="results"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    function addResult(message, type = 'info') {
        const timestamp = new Date().toLocaleTimeString();
        const color = type === 'error' ? 'text-danger' : (type === 'success' ? 'text-success' : 'text-info');
        $('#results').append(`<div class="${color}">[${timestamp}] ${message}</div>`);
        $('#testResults').scrollTop($('#testResults')[0].scrollHeight);
    }

    $('#testAjax').click(function() {
        addResult('Making AJAX request...');
        $.ajax({
            url: '{{ route("session.check") }}',
            type: 'GET',
            success: function(response) {
                addResult('AJAX Success: ' + JSON.stringify(response), 'success');
            },
            error: function(xhr) {
                addResult('AJAX Error: ' + xhr.status + ' - ' + xhr.statusText, 'error');
            }
        });
    });

    $('#checkSession').click(function() {
        addResult('Checking session status...');
        $.ajax({
            url: '{{ route("session.check") }}',
            type: 'GET',
            success: function(response) {
                if (response.authenticated) {
                    addResult('Session is active for user: ' + response.user.name, 'success');
                } else {
                    addResult('Session is not active', 'error');
                }
            },
            error: function(xhr) {
                addResult('Session check failed: ' + xhr.status, 'error');
            }
        });
    });

    $('#extendSession').click(function() {
        addResult('Extending session...');
        $.ajax({
            url: '{{ route("session.extend") }}',
            type: 'POST',
            success: function(response) {
                addResult('Session extended successfully', 'success');
            },
            error: function(xhr) {
                addResult('Failed to extend session: ' + xhr.status, 'error');
            }
        });
    });

    $('#simulateExpiry').click(function() {
        addResult('Simulating session expiry...');
        // Make a request to a protected route with an invalid token
        $.ajax({
            url: '{{ route("session.check") }}',
            type: 'GET',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Authorization', 'Bearer invalid-token');
            },
            success: function(response) {
                addResult('Unexpected success', 'error');
            },
            error: function(xhr) {
                addResult('Simulated expiry triggered: ' + xhr.status, 'success');
            }
        });
    });

    addResult('Session test page loaded');
});
</script>
@endsection