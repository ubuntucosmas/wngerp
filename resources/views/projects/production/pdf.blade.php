<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Production Details - {{ $project->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #0d6efd;
        }
        .header h1 {
            color: #0d6efd;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            background-color: #f8f9fa;
            padding: 5px 10px;
            font-weight: bold;
            border-left: 3px solid #0d6efd;
            margin-bottom: 10px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        .info-item {
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 10px;
            background-color: #f8f9fa;
            margin-bottom: 15px;
        }
        .info-item div:first-child {
            margin-bottom: 5px;
            font-weight: 600;
            color: #495057;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 3px;
        }
        .info-label {
            font-weight: bold;
            color: #000000;
        }
        .notes {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            border-left: 3px solid #0d6efd;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
        .logo {
            max-width: 200px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Production Job Brief</h1>
        <p>Project: {{ $project->name }}</p>
        <p>Generated on: {{ now()->format('F j, Y') }}</p>
    </div>

    <div class="section" style="width: 100%;">
        <div class="section-title">Job Description</div>
        <table style="width: 100%; border-collapse: separate; border-spacing: 10px; margin: -10px 0 5px -10px;">
            <tr>
                <td style="width: 20%; padding: 0 0 10px 10px; vertical-align: top;">
                    <div class="info-label" style="margin-bottom: 5px;">Job Number</div>
                    <div style="padding: 8px; background: #f8f9fa; border-radius: 4px; min-height: 38px;">
                        {{ $production->job_number ?? 'N/A' }}
                    </div>
                </td>
                <td style="width: 20%; padding: 0 0 10px 10px; vertical-align: top;">
                    <div class="info-label" style="margin-bottom: 5px;">Client</div>
                    <div style="padding: 8px; background: #f8f9fa; border-radius: 4px; min-height: 38px;">
                        {{ $production->client_name ?? 'N/A' }}
                    </div>
                </td>
                <td style="width: 40%; padding: 0 0 10px 10px; vertical-align: top;">
                    <div class="info-label" style="margin-bottom: 5px;">Project Title</div>
                    <div style="padding: 8px; background: #f8f9fa; border-radius: 4px; min-height: 38px;">
                        {{ $production->project_title ?? 'N/A' }}
                    </div>
                </td>
                <td style="width: 20%; padding: 0 0 10px 10px; vertical-align: top;">
                    <div class="info-label" style="margin-bottom: 5px;">Briefed By</div>
                    <div style="padding: 8px; background: #f8f9fa; border-radius: 4px; min-height: 38px;">
                        {{ $production->briefed_by ?? 'N/A' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section" style="width: 100%;">
        <div class="section-title">Timeline</div>
        <table style="width: 100%; border-collapse: separate; border-spacing: 10px; margin: -10px 0 5px -10px;">
            <tr>
                <td style="width: 50%; padding: 0 0 10px 10px; vertical-align: top;">
                    <div class="info-label" style="margin-bottom: 5px;">Briefing Date</div>
                    <div style="padding: 8px; background: #f8f9fa; border-radius: 4px; min-height: 38px;">
                        {{ $production->briefing_date ? $production->briefing_date->format('F j, Y') : 'N/A' }}
                    </div>
                </td>
                <td style="width: 50%; padding: 0 0 10px 10px; vertical-align: top;">
                    <div class="info-label" style="margin-bottom: 5px;">Delivery Date</div>
                    <div style="padding: 8px; background: #f8f9fa; border-radius: 4px; min-height: 38px;">
                       {{ $production->delivery_date ? $production->delivery_date->format('F j, Y') : 'N/A' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Production Team</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Production Team</div>
                <div>
                    @if($production->production_team)
                        @if(is_array($production->production_team) && count($production->production_team) > 0)
                            @foreach($production->production_team as $member)
                                <div>- {{ $member }}</div>
                            @endforeach
                        @elseif(is_string($production->production_team))
                            @php
                                // Handle newline-separated values
                                $members = array_filter(preg_split('/\r\n|\r|\n/', $production->production_team));
                            @endphp
                            @if(count($members) > 0)
                                @foreach($members as $member)
                                    <div>- {{ trim($member) }}</div>
                                @endforeach
                            @else
                                {{ $production->production_team }}
                            @endif
                        @else
                            N/A
                        @endif
                    @else
                        N/A
                    @endif
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">External Team</div>
                <div>
                    @if($production->external_team && is_array($production->external_team) && count($production->external_team) > 0)
                        @foreach($production->external_team as $member)
                            <div>- {{ $member }}</div>
                        @endforeach
                    @else
                        N/A
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Materials & Resources</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Required Materials</div>
                <div>
                    @if($production->materials_required)
                        @if(is_array($production->materials_required) && count($production->materials_required) > 0)
                            @foreach($production->materials_required as $material)
                                <div>- {{ $material }}</div>
                            @endforeach
                        @elseif(is_string($production->materials_required))
                            @php
                                // Handle newline-separated values
                                $materials = array_filter(preg_split('/\r\n|\r|\n/', $production->materials_required));
                            @endphp
                            @if(count($materials) > 0)
                                @foreach($materials as $material)
                                    <div>- {{ trim($material) }}</div>
                                @endforeach
                            @else
                                {{ $production->materials_required }}
                            @endif
                        @else
                            N/A
                        @endif
                    @else
                        N/A
                    @endif
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Equipment Needed</div>
                <div>
                    @if($production->equipment_needed)
                        @if(is_array($production->equipment_needed) && count($production->equipment_needed) > 0)
                            @foreach($production->equipment_needed as $equipment)
                                <div>- {{ $equipment }}</div>
                            @endforeach
                        @elseif(is_string($production->equipment_needed))
                            @php
                                // Handle newline-separated values
                                $equipmentList = array_filter(preg_split('/\r\n|\r|\n/', $production->equipment_needed));
                            @endphp
                            @if(count($equipmentList) > 0)
                                @foreach($equipmentList as $equipment)
                                    <div>- {{ trim($equipment) }}</div>
                                @endforeach
                            @else
                                {{ $production->equipment_needed }}
                            @endif
                        @else
                            N/A
                        @endif
                    @else
                        N/A
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($production->team_members && count($production->team_members) > 0)
    <div class="section">
        <div class="section-title">Team Members</div>
        <div>
            @foreach($production->team_members as $member)
                <div>- {{ $member }}</div>
            @endforeach
        </div>
    </div>
    @endif

    @if($production->materials && count($production->materials) > 0)
    <div class="section">
        <div class="section-title">Required Materials</div>
        <div>
            @foreach($production->materials as $material)
                <div>- {{ $material }}</div>
            @endforeach
        </div>
    </div>
    @endif

    @if($production->key_instructions)
    <div class="section">
        <div class="section-title">Key Instructions</div>
        <div class="notes">
            {!! nl2br(e($production->key_instructions)) !!}
        </div>
    </div>
    @endif

    @if($production->special_considerations)
    <div class="section">
        <div class="section-title">Special Considerations</div>
        <div class="notes">
            {!! nl2br(e($production->special_considerations)) !!}
        </div>
    </div>
    @endif

    @if($production->additional_notes)
    <div class="section">
        <div class="section-title">Additional Notes</div>
        <div class="notes">
            {!! nl2br(e($production->additional_notes)) !!}
        </div>
    </div>
    @endif

    <div class="footer">
        <p>WOODNORKGREEN LIMITED</p>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>
