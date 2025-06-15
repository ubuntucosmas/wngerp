<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Order - {{ $order->project_name ?? 'WOODNORKGREEN' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            background: #ffffff;
            margin: 0;
            padding: 0;
        }
        .excel-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 20px auto;
            border: 1px solid #ccc;
        }
        .excel-table th,
        .excel-table td {
            border: 1px solid #999;
            padding: 6px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
        }
        .excel-table th {
            background-color: #e0f3ff;
            font-weight: bold;
            text-transform: uppercase;
        }
        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            color: #0BADD3;
            margin-top: 20px;
        }
        .subtitle {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-bottom: 20px;
        }
        .section-title {
            background-color: #0BADD3;
            color: #fff;
            font-weight: bold;
            text-align: center;
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
    </style>
</head>
<body>
    <div class="excel-header">WOODNORKGREEN</div>
    <div class="sub-header">
        Karen Village Art Centre, Ngong Rd Nairobi<br>
        www.woodnorkgreen.co.ke | admin@woodnorkgreen.co.ke | +254780 397798
    </div>

    <table class="excel-table">
        <tr class="section-title">
            <th colspan="2">Booking Order - Project Plan B.O</th>
        </tr>
        <tr>
            <td>Reference:</td>
            <td>BO-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td>Generated:</td>
            <td>{{ now()->format('M d, Y H:i') }}</td>
        </tr>

        <!-- Project Information -->
        <tr class="section-title">
            <th colspan="2">Project Information</th>
        </tr>
        <tr><td>Project Name</td><td>{{ $order->project_name ?? '—' }}</td></tr>
        <tr><td>Contact Person</td><td>{{ $order->contact_person ?? '—' }}</td></tr>
        <tr><td>Phone Number</td><td>{{ $order->phone_number ?? '—' }}</td></tr>
        <tr><td>Event Venue</td><td>{{ $order->event_venue ?? '—' }}</td></tr>

        <!-- Project Team -->
        <tr class="section-title">
            <th colspan="2">Project Team</th>
        </tr>
        <tr><td>Project Manager</td><td>{{ $order->project_manager ?? '—' }}</td></tr>
        <tr><td>Project Captain</td><td>{{ $order->project_captain ?? '—' }}</td></tr>
        <tr><td>Asst. Captain</td><td>{{ $order->project_assistant_captain ?? '—' }}</td></tr>

        <!-- Schedule -->
        <tr class="section-title">
            <th colspan="2">Schedule</th>
        </tr>
        <tr><td>Set Down Date</td><td>{{ $order->set_down_date ?? '—' }} | {{ $order->set_down_time ?? '' }}</td></tr>
        <tr><td>Set Up Time</td><td>{{ $order->set_up_time ?? '—' }}</td></tr>
        <tr><td>Est. Set Up Period</td><td>{{ $order->estimated_set_up_period ?? '—' }}</td></tr>

        <!-- Team Members -->
<div class="section-title">Team Members</div>
<table class="excel-table">
    <tr>
        <th>Set Down Team</th>
        <td>
            @if(isset($order->setDownTeam) && count($order->setDownTeam) > 0)
                <ul>
                    @foreach($order->setDownTeam as $member)
                        <li>{{ $member->member_name }}</li>
                    @endforeach
                </ul>
            @else
                —
            @endif
        </td>
    </tr>
    <tr>
        <th>Pasting Team</th>
        <td>
            @if(isset($order->pastingTeam) && count($order->pastingTeam) > 0)
                <ul>
                    @foreach($order->pastingTeam as $member)
                        <li>{{ $member->member_name }}</li>
                    @endforeach
                </ul>
            @else
                —
            @endif
        </td>
    </tr>
    <tr>
        <th>Technical Team</th>
        <td>
            @if(isset($order->technicalTeam) && count($order->technicalTeam) > 0)
                <ul>
                    @foreach($order->technicalTeam as $member)
                        <li>{{ $member->member_name }}</li>
                    @endforeach
                </ul>
            @else
                —
            @endif
        </td>
    </tr>
</table>
<!-- Footer -->
<div class="footer">
    <div>Page <span class="page-number"></span> | Document generated on {{ now()->format('M d, Y \a\t H:i') }}</div>
    <div>© {{ date('Y') }} WOODNORKGREEN. All rights reserved.</div>
</div>
</body>
</html>
