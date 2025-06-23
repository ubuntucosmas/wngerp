<!DOCTYPE html>
<html>
<head>
    <title>Site Survey - {{ $project->name ?? 'WOODNORKGREEN' }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: normal;
            src: url('{{ storage_path('fonts/dejavu/DejaVuSans.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: bold;
            src: url('{{ storage_path('fonts/dejavu/DejaVuSans-Bold.ttf') }}') format('truetype');
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
            background: #ffffff;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-height: 60px;
            margin-bottom: 10px;
        }

        .header h1 {
            color: #0BADD3;
            font-size: 18px;
            margin: 5px 0;
        }

        .header p {
            color: #6E6F71;
            margin: 2px 0;
        }

        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .section-title {
            background-color: #124653;
            color: #fff;
            padding: 4px 8px;
            font-weight: bold;
            font-size: 11px;
            margin: 10px 0 5px 0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }

        .info-item {
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            color: #6E6F71;
            margin-bottom: 2px;
        }

        .info-value {
            padding: 3px 0;
        }

        .signature-box {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }

        .signature-line {
            width: 200px;
            border-top: 1px solid #000;
            margin: 15px 0 5px 0;
        }

        .page-break {
            page-break-before: always;
        }

        .footer {
            font-size: 8px;
            text-align: center;
            color: #6E6F71;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }

        .attendees-list {
            list-style-type: none;
            padding: 0;
            margin: 5px 0;
        }

        .attendees-list li {
            margin-bottom: 3px;
            padding: 3px 0;
            border-bottom: 1px dashed #eee;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .mb-10 {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/wng-logo.png') }}" alt="WOODNORKGREEN Logo">
        <h1>SITE SURVEY REPORT</h1>
        <h2>PROJECT ID: {{ $project->project_id }}</h2>
    </div>

    <!-- Project Information -->
    <div class="section">
        <div class="section-title">Project Information</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Project Name</div>
                <div class="info-value">{{ $project->name }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Client</div>
                <div class="info-value">{{ $project->client_name ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Site Visit Date</div>
                <div class="info-value">{{ $siteSurvey->site_visit_date ? \Carbon\Carbon::parse($siteSurvey->site_visit_date)->format('M d, Y') : 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Location</div>
                <div class="info-value">{{ $siteSurvey->location ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Attendees -->
    @if(!empty($siteSurvey->attendees) && count($siteSurvey->attendees) > 0)
    <div class="section">
        <div class="section-title">Attendees</div>
        <ul class="attendees-list">
            @foreach($siteSurvey->attendees as $attendee)
                <li>{{ $attendee }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Site Assessment -->
    <div class="section">
        <div class="section-title">Site Assessment</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Current Condition</div>
                <div class="info-value">{{ $siteSurvey->current_condition ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Existing Branding</div>
                <div class="info-value">{{ $siteSurvey->existing_branding ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Access & Logistics</div>
                <div class="info-value">{{ $siteSurvey->access_logistics ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Parking Availability</div>
                <div class="info-value">{{ $siteSurvey->parking_availability ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Client Requirements -->
    <div class="section">
        <div class="section-title">Client Requirements</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Branding Preferences</div>
                <div class="info-value">{{ $siteSurvey->branding_preferences ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Material Preferences</div>
                <div class="info-value">{{ $siteSurvey->material_preferences ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Color Scheme</div>
                <div class="info-value">{{ $siteSurvey->color_scheme ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Project Description -->
    @if($siteSurvey->project_description)
    <div class="section">
        <div class="section-title">Project Description</div>
        <div class="info-item">
            <div class="info-value">{{ $siteSurvey->project_description }}</div>
        </div>
    </div>
    @endif

    <!-- Project Objectives -->
    @if($siteSurvey->objectives)
    <div class="section">
        <div class="section-title">Project Objectives</div>
        <div class="info-item">
            <div class="info-value">{{ $siteSurvey->objectives }}</div>
        </div>
    </div>
    @endif

    <!-- Site Specifications -->
    <div class="section">
        <div class="section-title">Site Specifications</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Lifts</div>
                <div class="info-value">{{ $siteSurvey->lifts ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Door Sizes</div>
                <div class="info-value">{{ $siteSurvey->door_sizes ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Loading Areas</div>
                <div class="info-value">{{ $siteSurvey->loading_areas ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Room Size</div>
                <div class="info-value">{{ $siteSurvey->room_size ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Site Measurements</div>
                <div class="info-value">{{ $siteSurvey->site_measurements ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Constraints</div>
                <div class="info-value">{{ $siteSurvey->constraints ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Electrical Outlets</div>
                <div class="info-value">{{ $siteSurvey->electrical_outlets ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Food & Refreshment</div>
                <div class="info-value">{{ $siteSurvey->food_refreshment ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Brand Guidelines & Special Instructions -->
    @if($siteSurvey->brand_guidelines || $siteSurvey->special_instructions)
    <div class="section">
        <div class="section-title">Branding & Instructions</div>
        @if($siteSurvey->brand_guidelines)
        <div class="info-item">
            <div class="info-label">Brand Guidelines</div>
            <div class="info-value">{{ $siteSurvey->brand_guidelines }}</div>
        </div>
        @endif
        @if($siteSurvey->special_instructions)
        <div class="info-item">
            <div class="info-label">Special Instructions</div>
            <div class="info-value">{{ $siteSurvey->special_instructions }}</div>
        </div>
        @endif
    </div>
    @endif

    <!-- Project Timeline -->
    <div class="section">
        <div class="section-title">Project Timeline</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Proposed Start Date</div>
                <div class="info-value">
                    @if($siteSurvey->project_start_date)
                        {{ \Carbon\Carbon::parse($siteSurvey->project_start_date)->format('M d, Y') }}
                    @else
                        N/A
                    @endif
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Project Deadline</div>
                <div class="info-value">
                    @if($siteSurvey->project_deadline)
                        {{ \Carbon\Carbon::parse($siteSurvey->project_deadline)->format('M d, Y') }}
                    @else
                        N/A
                    @endif
                </div>
            </div>
        </div>
        @if($siteSurvey->milestones && is_array($siteSurvey->milestones) && count($siteSurvey->milestones) > 0)
        <div class="info-item mt-10">
            <div class="info-label">Milestones</div>
            <ul class="mb-0">
                @foreach($siteSurvey->milestones as $milestone)
                    <li>{{ $milestone }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <!-- Health and Safety -->
    <div class="section">
        <div class="section-title">Health and Safety</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Safety Conditions</div>
                <div class="info-value">{{ $siteSurvey->safety_conditions ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Potential Hazards</div>
                <div class="info-value">{{ $siteSurvey->potential_hazards ?? 'N/A' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Safety Requirements</div>
                <div class="info-value">{{ $siteSurvey->safety_requirements ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    @if($siteSurvey->additional_notes || $siteSurvey->special_requests || ($siteSurvey->action_items && is_array($siteSurvey->action_items) && count($siteSurvey->action_items) > 0))
    <div class="section">
        <div class="section-title">Additional Information</div>
        @if($siteSurvey->additional_notes)
        <div class="info-item">
            <div class="info-label">Additional Notes</div>
            <div class="info-value">{{ $siteSurvey->additional_notes }}</div>
        </div>
        @endif
        @if($siteSurvey->special_requests)
        <div class="info-item">
            <div class="info-label">Special Requests</div>
            <div class="info-value">{{ $siteSurvey->special_requests }}</div>
        </div>
        @endif
        @if($siteSurvey->action_items && is_array($siteSurvey->action_items) && count($siteSurvey->action_items) > 0)
        <div class="info-item">
            <div class="info-label">Action Items</div>
            <ul class="mb-0">
                @foreach($siteSurvey->action_items as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    @endif

    <!-- Signatures -->
    <div class="section">
        <div class="section-title">Signatures</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Prepared By</div>
                <div class="info-value">{{ $siteSurvey->prepared_by ?? 'N/A' }}</div>
                @if($siteSurvey->prepared_signature)
                    <div class="signature-image" style="height: 50px; margin: 10px 0;">
                        <img src="{{ $siteSurvey->prepared_signature }}" alt="Signature" style="max-height: 100%; max-width: 200px;">
                    </div>
                @else
                    <div class="signature-line"></div>
                @endif
                <div class="text-center" style="font-size: 9px;">
                    Signature / {{ $siteSurvey->prepared_date ? \Carbon\Carbon::parse($siteSurvey->prepared_date)->format('M d, Y') : 'Date' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Client Approval</div>
                <div class="info-value">{{ $siteSurvey->client_approval ?? 'N/A' }}</div>
                @if($siteSurvey->client_signature)
                    <div class="signature-image" style="height: 50px; margin: 10px 0;">
                        <img src="{{ $siteSurvey->client_signature }}" alt="Client Signature" style="max-height: 100%; max-width: 200px;">
                    </div>
                @else
                    <div class="signature-line"></div>
                @endif
                <div class="text-center" style="font-size: 9px;">
                    Signature / {{ $siteSurvey->client_approval_date ? \Carbon\Carbon::parse($siteSurvey->client_approval_date)->format('M d, Y') : 'Date' }}
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('M d, Y h:i A') }} | {{ config('app.name') }} - {{ config('app.url') }}</p>
    </div>
</body>
</html>
