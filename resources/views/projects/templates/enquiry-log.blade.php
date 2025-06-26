<!DOCTYPE html>
<html>
<head>
    <title>Enquiry Log - {{ $enquiryLog->project_name ?? 'WOODNORKGREEN' }}</title>
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

        .excel-style-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .excel-style-table th, .excel-style-table td {
            border: 1px solid #dee2e6;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        .excel-style-table th {
            background-color: #124653; /* Baby Blue */
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .excel-header {
            text-align: center;
            font-size: 18px;
            color: #0BADD3;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .sub-header {
            text-align: center;
            color: #6E6F71;
            font-size: 9px;
            margin-bottom: 20px;
        }

        .section-title {
            background-color:  #124653; /* Blue */
            color: #fff;
            padding: 5px 10px;
            font-weight: bold;
            font-size: 11px;
        }

        .status-badge {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-Open { background: #fff3cd; color: #856404; }
        .status-Quoted { background: #cce5ff; color: #004085; }
        .status-Approved { background: #d4edda; color: #155724; }
        .status-Declined { background: #f8d7da; color: #721c24; }

        ul.scope-list {
            margin: 0;
            padding-left: 15px;
        }

        ul.scope-list li {
            margin-bottom: 3px;
        }

        .notes-content {
            white-space: pre-wrap;
        }

        .footer {
            position: absolute;
            bottom: 10mm;
            left: 20mm;
            right: 20mm;
            text-align: center;
            font-size: 7px;
            color: #6E6F71;
            padding-top: 3mm;
            border-top: 1px solid #e0e0e0;
        }
        .logo img {
            text-align: center;
            margin-bottom: 10px;
            height: 60px;
        }

        h1 {
            font-size: 16px;
            text-align: center;
            margin: 0;
            color: #145da0;
          }

          p {
            font-size: 10px;
            text-align: center;
            margin: 0;
            color: #6E6F71;
          }

          hr {
            border: none;
            border-top: 1px solid #b1d4e0;
            margin: 10px 0;
          }

          .company-header {
            text-align: center;
            margin-bottom: 8px;
          }
    </style>
</head>
<body>
    <div class="company-header">
        <div class="logo">
            <img src="{{ public_path('images/wng-logo.png') }}" alt="Company Logo">
        </div>
        <h1>WOODNORKGREEN</h1>
        <h2>PROJECT BRIEF</h2>
        <p>Karen Village Art Centre, Ngong Rd Nairobi</p>
        <p>www.woodnorkgreen.co.ke | admin@woodnorkgreen.co.ke | +254780 397798</p>
    </div>
    <hr>    
    <div class="section-title">Client & Project Information</div>
    <table class="excel-style-table">
        <tr>
            <th>Project ID</th>
            <td>{{ $enquiryLog->project->project_id ?? '—' }}</td>
            <th>Project Name</th>
            <td>{{ $enquiryLog->project_name ?? '—' }}</td>
        </tr>
        <tr>
            <th>Client Name</th>
            <td>{{ $enquiryLog->client_name ?? '—' }}</td>
            <th>Contact Person</th>
            <td>{{ $enquiryLog->contact_person ?? '—' }}</td>
        </tr>
        <tr>
            <th>Venue</th>
            <td>{{ $enquiryLog->venue ?? '—' }}</td>
        </tr>
    </table>

    <div class="section-title">Project Details</div>
    <table class="excel-style-table">
        <tr>
            <th>Date Received</th>
            <td>{{ $enquiryLog->date_received ? \Carbon\Carbon::parse($enquiryLog->date_received)->format('M d, Y') : '—' }}</td>
            <th>Status</th>
            <td>
                <span class="status-badge status-{{ $enquiryLog->status ?? 'Open' }}">
                    {{ $enquiryLog->status ?? 'Open' }}
                </span>
            </td>
        </tr>
        <tr>
            <th>Assigned To</th>
            <td>{{ $enquiryLog->assigned_to ?? '—' }}</td>
            <th>Reference</th>
            <td>ENQ-{{ str_pad($enquiryLog->id, 5, '0', STR_PAD_LEFT) }}</td>
        </tr>
    </table>

    <div class="section-title">Project Scope Summary</div>
    <table class="excel-style-table">
        <tr>
            <td>
                @php
                    $scopeSummary = $enquiryLog->project_scope_summary ?? '';
                    $scopeItems = is_array($scopeSummary) 
                        ? $scopeSummary 
                        : (json_decode($scopeSummary, true) ?: array_filter(explode(',', $scopeSummary)));
                @endphp
                @if(is_array($scopeItems) && count($scopeItems))
                    <ul class="scope-list">
                        @foreach($scopeItems as $item)
                            <li>{{ trim($item) }}</li>
                        @endforeach
                    </ul>
                @else
                    <p style="color: #6E6F71; font-style: italic;">No scope summary provided</p>
                @endif
            </td>
        </tr>
    </table>

    <div class="section-title">Follow Up Notes</div>
    <table class="excel-style-table">
        <tr>
            <td class="notes-content">{{ $enquiryLog->follow_up_notes ?? 'No notes available.' }}</td>
        </tr>
    </table>

    <div style="border: 1px solid #2e8bc0; padding: 20px; border-radius: 8px; margin-top: 30px; font-family: Arial, sans-serif; width: 100%; max-width: 600px;">
        <h3 style="color: #0c2d48; margin-top: 0;">Project Handover Details</h3>
    
        <div style="margin-bottom: 15px;">
            <strong>Handed Over At:</strong>
            <span style="margin-left: 10px;">{{ now()->format('M d, Y \a\t H:i') }}</span>
        </div>
    
        <div>
            <strong>Officer:</strong>
            <span style="margin-left: 10px;">{{ Auth::user()->name }}</span>
        </div>
    </div>
    

    <!-- Footer -->
    <div class="footer">
        <div>Page <span class="page-number"></span> | Document generated on {{ now()->format('M d, Y \a\t H:i') }}</div>
        <div>© {{ date('Y') }} WOODNORKGREEN. All rights reserved.</div>
    </div>

</body>
</html>
