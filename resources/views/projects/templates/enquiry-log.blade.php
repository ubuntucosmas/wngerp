<!-- Header Section -->
<table style="font-family: 'Poppins', Arial, sans-serif;">
    <tr>
        <td colspan="8" align="center" style="font-size:16px;font-weight:bold;padding:8px 0;font-family: 'Poppins', Arial, sans-serif;">Enquiry Log Export</td>
    </tr>
    <tr>
        <td colspan="8" align="center" style="padding-bottom:8px;">
            <!-- Logo Placeholder -->
            <img src="{{ public_path('images/wng-logo.png') }}" alt="Company Logo" height="40">
        </td>
    </tr>
</table>

@php
    $enquiry = $enquiryLog->enquiry;
    // Ensure $project is defined, default to null if not passed
    $project = $project ?? null;
    $dateReceived = $enquiryLog->date_received ? \Carbon\Carbon::parse($enquiryLog->date_received) : null;
    $expectedDelivery = $enquiry && $enquiry->expected_delivery_date ? \Carbon\Carbon::parse($enquiry->expected_delivery_date) : null;
    $scopeItems = is_array($enquiryLog->project_scope_summary) ? $enquiryLog->project_scope_summary : (json_decode($enquiryLog->project_scope_summary, true) ?: array_filter(explode(',', $enquiryLog->project_scope_summary ?? '')));
@endphp

<!-- Project Information -->
<table style="font-family: 'Poppins', Arial, sans-serif; font-size: 11px;">
    <tr>
        <th align="left" style="font-size: 11px; font-weight: 600;">Project ID:</th>
        <td style="font-size: 11px;">{{ $project ? $project->project_id : 'ENQ-' . str_pad($enquiryLog->id, 5, '0', STR_PAD_LEFT) }}</td>
        <th align="left" style="font-size: 11px; font-weight: 600;">{{ isset($enquiry) ? 'Enquiry' : 'Project' }} Name:</th>
        <td style="font-size: 11px;">{{ $enquiryLog->project_name ?? 'N/A' }}</td>
        <th align="left" style="font-size: 11px; font-weight: 600;">Client:</th>
        <td style="font-size: 11px;">{{ $enquiryLog->client_name ?? 'N/A' }}</td>
    </tr>
    <tr>
        <th align="left" style="font-size: 11px; font-weight: 600;">Venue:</th>
        <td style="font-size: 11px;">{{ $enquiryLog->venue ?? 'N/A' }}</td>
        <th align="left" style="font-size: 11px; font-weight: 600;">Contact Person:</th>
        <td style="font-size: 11px;">{{ $enquiryLog->contact_person ?? 'N/A' }}</td>
        <th align="left" style="font-size: 11px; font-weight: 600;">Assigned To:</th>
        <td style="font-size: 11px;">{{ $enquiryLog->assigned_to ?? 'Unassigned' }}</td>
    </tr>
    <tr>
        <th align="left" style="font-size: 11px; font-weight: 600;">Status:</th>
        <td style="font-size: 11px;">{{ $enquiryLog->status ?? 'Open' }}</td>
        <th align="left" style="font-size: 11px; font-weight: 600;">Project Type:</th>
        <td style="font-size: 11px;">{{ $project ? 'Converted Project' : 'Enquiry Log' }}</td>
        <th align="left" style="font-size: 11px; font-weight: 600;">Expected Delivery:</th>
        <td style="font-size: 11px;">{{ $expectedDelivery ? $expectedDelivery->format('M d, Y') : 'N/A' }}</td>
    </tr>
    <tr>
        <th align="left" style="font-size: 11px; font-weight: 600;">Reference:</th>
        <td style="font-size: 11px;">ENQ-{{ str_pad($enquiryLog->id, 5, '0', STR_PAD_LEFT) }}</td>
        <th align="left" style="font-size: 11px; font-weight: 600;"></th>
        <td style="font-size: 11px;"></td>
        <th align="left" style="font-size: 11px; font-weight: 600;"></th>
        <td style="font-size: 11px;"></td>
    </tr>
</table>
<br>

@if(count($scopeItems) > 0)
<table border="1" style="border-collapse:collapse; font-family: 'Poppins', Arial, sans-serif;">
    <thead style="background:#f0f0f0;">
        <tr>
            <th colspan="3" style="font-size:12px;text-align:left;padding:6px;font-weight:600;">Project Scope Summary</th>
        </tr>
        <tr>
            <th style="font-size:10px;padding:4px;font-weight:600;">#</th>
            <th style="font-size:10px;padding:4px;font-weight:600;">Scope Item</th>
            <th style="font-size:10px;padding:4px;font-weight:600;">Description</th>
        </tr>
    </thead>
    <tbody>
        @foreach($scopeItems as $index => $item)
        <tr>
            <td style="font-size:10px;padding:4px;">{{ $index + 1 }}</td>
            <td style="font-size:10px;padding:4px;">{{ trim($item) }}</td>
            <td style="font-size:10px;padding:4px;">{{ strlen(trim($item)) > 50 ? 'Detailed scope item' : 'Standard scope item' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
<br>

@php
    $daysElapsed = $dateReceived ? $dateReceived->diffInDays(now()) : 0;
    $daysToDelivery = $expectedDelivery ? now()->diffInDays($expectedDelivery, false) : null;
    $scopeCount = count($scopeItems);
@endphp

<!-- Timeline Information -->
<table border="1" style="border-collapse:collapse; font-family: 'Poppins', Arial, sans-serif;">
    <thead style="background:#f0f0f0;">
        <tr>
            <th colspan="3" style="font-size:12px;text-align:left;padding:6px;font-weight:600;">Timeline Information</th>
        </tr>
        <tr>
            <th style="font-size:10px;padding:4px;font-weight:600;">Milestone</th>
            <th style="font-size:10px;padding:4px;font-weight:600;">Date</th>
            <th style="font-size:10px;padding:4px;font-weight:600;">Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="font-size:10px;padding:4px;">Enquiry Received</td>
            <td style="font-size:10px;padding:4px;">{{ $dateReceived ? $dateReceived->format('M d, Y') : 'N/A' }}</td>
            <td style="font-size:10px;padding:4px;">{{ $daysElapsed }} days ago</td>
        </tr>
        <tr>
            <td style="font-size:10px;padding:4px;">Expected Delivery</td>
            <td style="font-size:10px;padding:4px;">{{ $expectedDelivery ? $expectedDelivery->format('M d, Y') : 'Not Set' }}</td>
            <td style="font-size:10px;padding:4px;">{{ $daysToDelivery !== null ? ($daysToDelivery < 0 ? 'OVERDUE' : $daysToDelivery . ' days left') : 'N/A' }}</td>
        </tr>
        <tr>
            <td style="font-size:10px;padding:4px;">Last Updated</td>
            <td style="font-size:10px;padding:4px;">{{ $enquiryLog->updated_at->format('M d, Y H:i') }}</td>
            <td style="font-size:10px;padding:4px;">{{ $enquiryLog->updated_at->diffForHumans() }}</td>
        </tr>
        <tr>
            <td style="font-size:10px;padding:4px;">Project Conversion</td>
            <td style="font-size:10px;padding:4px;">{{ $project ? $project->created_at->format('M d, Y') : 'Pending' }}</td>
            <td style="font-size:10px;padding:4px;">{{ $project ? 'COMPLETED' : 'PENDING' }}</td>
        </tr>
    </tbody>
</table>
<br>

@if($enquiry && $enquiry->phases->count() > 0)
<table border="1" style="border-collapse:collapse; font-family: 'Poppins', Arial, sans-serif;">
    <thead style="background:#f0f0f0;">
        <tr>
            <th colspan="4" style="font-size:12px;text-align:left;padding:6px;font-weight:600;">Project Phases</th>
        </tr>
        <tr>
            <th style="font-size:10px;padding:4px;font-weight:600;">Phase Name</th>
            <th style="font-size:10px;padding:4px;font-weight:600;">Status</th>
            <th style="font-size:10px;padding:4px;font-weight:600;">Start Date</th>
            <th style="font-size:10px;padding:4px;font-weight:600;">End Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($enquiry->phases as $phase)
            <tr>
                <td style="font-size:10px;padding:4px;">{{ $phase->name }}</td>
                <td style="font-size:10px;padding:4px;">{{ $phase->status }}</td>
                <td style="font-size:10px;padding:4px;">{{ $phase->start_date ? \Carbon\Carbon::parse($phase->start_date)->format('M d, Y') : 'N/A' }}</td>
                <td style="font-size:10px;padding:4px;">{{ $phase->end_date ? \Carbon\Carbon::parse($phase->end_date)->format('M d, Y') : 'N/A' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<br>
@endif

<!-- Follow Up Notes -->
<table border="1" style="border-collapse:collapse; font-family: 'Poppins', Arial, sans-serif;">
    <thead style="background:#f0f0f0;">
        <tr>
            <th style="font-size:12px;text-align:left;padding:6px;font-weight:600;">Follow Up Notes</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:6px;font-size:10px;">{{ $enquiryLog->follow_up_notes ?? 'No follow-up notes recorded.' }}</td>
        </tr>
    </tbody>
</table>
<br>

<!-- Project Handover Details -->
<table border="1" style="border-collapse:collapse; font-family: 'Poppins', Arial, sans-serif;">
    <thead style="background:#f0f0f0;">
        <tr>
            <th style="font-size:12px;text-align:left;padding:6px;font-weight:600;">Project Handover Details</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="padding:6px;font-size:10px;">
                <strong>Handed Over At:</strong> {{ now()->format('M d, Y \a\t H:i') }}<br>
                <strong>Officer:</strong> {{ Auth::user()->name ?? 'System' }}
            </td>
        </tr>
    </tbody>
</table>
<br>

<!-- Footer -->
<table style="font-family: 'Poppins', Arial, sans-serif;">
    <tr>
        <td colspan="8" align="center" style="font-size:9px;color:#666;padding-top:15px;border-top:1px solid #ccc;">
            Document generated on {{ now()->format('M d, Y \a\t H:i') }} | © {{ date('Y') }} WOODNORKGREEN. All rights reserved.
        </td>
    </tr>
</table>
