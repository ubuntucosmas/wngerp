<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Close Out Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; margin-bottom: 10px; }
        h2 { font-size: 14px; margin-top: 20px; }
        .section { margin-bottom: 12px; }
        .box { border: 1px solid #ddd; padding: 10px; border-radius: 6px; }
    </style>
    </head>
<body>
    <h1>Project Close Out Report</h1>
    <div class="section">
        <strong>Project Title:</strong> {{ $report->project_title ?? $project->name }}<br>
        <strong>Client Name:</strong> {{ $report->client_name ?? ($project->client_name ?? optional($project->client)->FullName) }}<br>
        <strong>Project Code/ID:</strong> {{ $report->project_code ?? $project->project_id }}<br>
        <strong>Project Officer:</strong> {{ $report->project_officer ?? optional($project->projectOfficer)->name }}<br>
        <strong>Set Up Date:</strong> {{ $report->set_up_date ? \Carbon\Carbon::parse($report->set_up_date)->format('M d, Y') : optional($project->start_date)->format('M d, Y') }}<br>
        <strong>Set Down Date:</strong> {{ $report->set_down_date ? \Carbon\Carbon::parse($report->set_down_date)->format('M d, Y') : optional($project->end_date)->format('M d, Y') }}<br>
        <strong>Site Location:</strong> {{ $report->site_location ?? $project->venue }}
    </div>

    <h2>Section 2: Project Scope Summary</h2>
    <div class="box">{!! nl2br(e($report->scope_summary)) !!}</div>

    <h2>Section 3: Procurement & Inventory</h2>
    <div class="box"><strong>Materials requested:</strong><br>{!! nl2br(e($report->materials_requested_notes)) !!}</div>
    <div class="box"><strong>Items sourced externally:</strong><br>{!! nl2br(e($report->items_sourced_externally)) !!}</div>
    <div class="box"><strong>Store-issued items:</strong><br>{!! nl2br(e($report->store_issued_items)) !!}</div>
    <div class="box"><strong>Inventory returns & balance:</strong><br>{!! nl2br(e($report->inventory_returns_balance)) !!}</div>
    <div class="box"><strong>Challenges faced in acquiring materials:</strong><br>{!! nl2br(e($report->procurement_challenges)) !!}</div>

    <h2>Section 4: Fabrication & Quality Control</h2>
    <div class="box"><strong>Date production started:</strong> {{ $report->production_start_date ? \Carbon\Carbon::parse($report->production_start_date)->format('M d, Y') : '' }}</div>
    <div class="box"><strong>Packaging and labeling status:</strong><br>{!! nl2br(e($report->packaging_labeling_status)) !!}</div>
    <div class="box"><strong>QC findings and resolutions:</strong><br>{!! nl2br(e($report->qc_findings_resolutions)) !!}</div>
    <div class="box"><strong>Challenges faced in production:</strong><br>{!! nl2br(e($report->production_challenges)) !!}</div>

    <h2>Section 5: On-Site Setup Summary</h2>
    <div class="box"><strong>Setup date(s):</strong> {!! nl2br(e($report->setup_dates)) !!}</div>
    <div class="box"><strong>Estimated set up time:</strong> {{ $report->estimated_setup_time }}</div>
    <div class="box"><strong>Actual set up time:</strong> {{ $report->actual_setup_time }}</div>
    <div class="box"><strong>Team composition:</strong><br>{!! nl2br(e($report->team_composition)) !!}</div>
    <div class="box"><strong>Challenges faced on site:</strong><br>{!! nl2br(e($report->onsite_challenges)) !!}</div>
    <div class="box"><strong>Client interactions and observations:</strong><br>{!! nl2br(e($report->client_interactions)) !!}</div>
    <div class="box"><strong>Safety issues or incidents:</strong><br>{!! nl2br(e($report->safety_issues)) !!}</div>

    <h2>Section 6: Client Handover</h2>
    <div class="box"><strong>Date of handover:</strong> {{ $report->handover_date ? \Carbon\Carbon::parse($report->handover_date)->format('M d, Y') : '' }}</div>
    <div class="box"><strong>Client sign-off status:</strong> {{ $report->client_signoff_status }}</div>
    <div class="box"><strong>Client feedback / QR CODE:</strong><br>{!! nl2br(e($report->client_feedback_qr)) !!}</div>
    <div class="box"><strong>Post-handover adjustments made:</strong><br>{!! nl2br(e($report->post_handover_adjustments)) !!}</div>

    <h2>Section 7: Set-Down & Debrief Summary</h2>
    <div class="box"><strong>Date of set-down:</strong> {{ $report->set_down_date ? \Carbon\Carbon::parse($report->set_down_date)->format('M d, Y') : '' }}</div>
    <div class="box"><strong>Condition of items returned:</strong><br>{!! nl2br(e($report->condition_of_items_returned)) !!}</div>
    <div class="box"><strong>Site clearance status:</strong><br>{!! nl2br(e($report->site_clearance_status)) !!}</div>
    <div class="box"><strong>Debrief notes:</strong><br>{!! nl2br(e($report->debrief_notes)) !!}</div>

    <h2>Section 8: Attachments Checklist</h2>
    <div class="box">
        <ul>
            <li>[{{ $report->att_deliverables_ppt ? 'x' : ' ' }}] Deliverables PPT (PDF)</li>
            <li>[{{ $report->att_cutlist ? 'x' : ' ' }}] Cutlist</li>
            <li>[{{ $report->att_site_survey ? 'x' : ' ' }}] Site Survey Form</li>
            <li>[{{ $report->att_project_budget ? 'x' : ' ' }}] Project Budget File</li>
            <li>[{{ $report->att_mrf_or_material_list ? 'x' : ' ' }}] Material Requisition Form (MRF) / Material List</li>
            <li>[{{ $report->att_qc_checklist ? 'x' : ' ' }}] QC Checklist</li>
            <li>[{{ $report->att_setup_setdown_checklists ? 'x' : ' ' }}] Setup & Set-Down Checklists</li>
            <li>[{{ $report->att_client_feedback_form ? 'x' : ' ' }}] Client Feedback Form (QR Code)</li>
        </ul>
    </div>

    <h2>Section 10: Final Approval</h2>
    <div class="box">
        <strong>Project Officer Name & Signature:</strong> {{ $report->po_name_signature }} &nbsp;&nbsp; Date: {{ $report->po_signature_date ? \Carbon\Carbon::parse($report->po_signature_date)->format('M d, Y') : '' }}<br>
        <strong>Reviewed by (Supervisor):</strong> {{ $report->supervisor_reviewed_by }} &nbsp;&nbsp; Date: {{ $report->supervisor_review_date ? \Carbon\Carbon::parse($report->supervisor_review_date)->format('M d, Y') : '' }}
    </div>
</body>
</html>


