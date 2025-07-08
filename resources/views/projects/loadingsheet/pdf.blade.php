<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Loading Sheet - {{ $project->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
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
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #f8f9fa;
            padding: 10px;
            font-weight: bold;
            border-left: 3px solid #0d6efd;
            margin-bottom: 15px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .info-item {
            border: 1px solid #e9ecef;
            border-radius: 4px;
            padding: 15px;
            background-color: #f8f9fa;
        }
        .info-item strong {
            display: block;
            margin-bottom: 5px;
            color: #0d6efd;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th,
        .table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .signature-box {
            border-top: 2px dashed #dee2e6;
            padding-top: 20px;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LOADING SHEET</h1>
        <p>Project: {{ $project->name }}</p>
        <p>Date: {{ now()->format('d/m/Y') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Vehicle & Driver Information</div>
        <div class="info-grid">
            <div class="info-item">
                <strong>Vehicle Registration</strong>
                {{ $loadingsheet->vehicle_number }}
            </div>
            <div class="info-item">
                <strong>Driver Name</strong>
                {{ $loadingsheet->driver_name }}
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Loading Details</div>
        <div class="info-grid">
            <div class="info-item">
                <strong>Loading Point</strong>
                {{ $loadingsheet->loading_point }}
            </div>
            <div class="info-item">
                <strong>Unloading Point</strong>
                {{ $loadingsheet->unloading_point }}
            </div>
        </div>
        <div class="info-grid">
            <div class="info-item">
                <strong>Loading Date</strong>
                {{ \Carbon\Carbon::parse($loadingsheet->loading_date)->format('d/m/Y') }}
            </div>
            <div class="info-item">
                <strong>Unloading Date</strong>
                {{ \Carbon\Carbon::parse($loadingsheet->unloading_date)->format('d/m/Y') }}
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Items to be Loaded</div>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loadingsheet->items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item['description'] }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>{{ $item['unit'] }}</td>
                    <td>{{ $item['notes'] ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Special Instructions</div>
        <p>{{ $loadingsheet->special_instructions ?? 'No special instructions' }}</p>
    </div>

    <div class="section">
        <div class="info-grid">
            <div class="info-item">
                <strong>Prepared By</strong>
                <div class="signature-box">____________________</div>
            </div>
            <div class="info-item">
                <strong>Approved By</strong>
                <div class="signature-box">____________________</div>
            </div>
        </div>
    </div>
</body>
</html>
