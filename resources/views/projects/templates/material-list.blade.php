<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Material List - {{ $project->name }}</title>
    <style>
        @page { margin: 0.5cm; }
        body {
            font-family: Arial, sans-serif;
            font-size: 8px;
            background: #ffffff;
            margin: 0;
            padding: 0;
            line-height: 1.2;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        .logo {
            max-height: 30px;
            width: auto;
        }
        .company-info {
            text-align: right;
            font-size: 7px;
            color: #666;
        }
        .excel-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 5px 0;
            border: 1px solid #ccc;
            font-size: 8px;
        }
        .excel-table th,
        .excel-table td {
            border: 1px solid #ddd;
            padding: 3px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
        }
        .excel-table th {
            background-color: #e0f3ff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7px;
            padding: 2px 3px;
        }
        .title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            color: #0BADD3;
            margin: 3px 0;
            padding: 2px 0;
        }
        .subtitle {
            text-align: center;
            font-size: 7px;
            color: #666;
            margin-bottom: 3px;
        }
        .section-title {
            background-color: #0BADD3;
            color: #fff;
            font-weight: bold;
            text-align: center;
            font-size: 8px;
            padding: 2px 0;
            margin: 5px 0 3px 0;
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
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin: 20px 0 5px 0;
        }
        .signature-label {
            font-size: 9px;
            margin-top: 2px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div style="font-weight: bold; font-size: 16px; color: #0BADD3;">
            WOODNORKGREEN
        </div>
        <div colspan="4" class="title">MATERIAL LIST</div>
        <div class="company-info">
            Karen Village Art Centre, Ngong Rd Nairobi<br>
            www.woodnorkgreen.co.ke | admin@woodnorkgreen.co.ke<br>
            +254780 397798 | Generated: {{ now()->format('M d, Y H:i') }}
        </div>
    </div>

    <table class="excel-table">
        <tr>
            <td colspan="4" class="title">MATERIAL LIST - {{ strtoupper($project->name) }}</td>
        </tr>
        <tr>
            <td>Reference:</td>
            <td>ML-{{ str_pad($project->project_id, 5, '0', STR_PAD_LEFT) }}</td>
            <td>Date:</td>
            <td>{{ $materialList->created_at->format('M d, Y') }}</td>
        </tr>
        <tr>
            <td>Project:</td>
            <td colspan="3">{{ $project->name }}</td>
        </tr>
        <tr>
            <td>Client:</td>
            <td colspan="3">{{ $project->client_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Start Date:</td>
            <td>{{ $materialList->start_date ? \Carbon\Carbon::parse($materialList->start_date)->format('M d, Y') : 'N/A' }}</td>
            <td>End Date:</td>
            <td>{{ $materialList->end_date ? \Carbon\Carbon::parse($materialList->end_date)->format('M d, Y') : 'N/A' }}</td>
        </tr>
    </table>

    @if($materialList->productionItems->count() > 0)
        <div>
            <div class="section-title">PRODUCTION MATERIALS</div>
            @foreach($materialList->productionItems as $item)
                <table class="excel-table">
                    <tr>
                        <th colspan="4" style="background-color: #f0f8ff;">{{ $item->item_name }}</th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Particular</th>
                        <th>Unit</th>
                        <th>Quantity</th>

                        
                    </tr>
                    @foreach($item->particulars as $index => $particular)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $particular->particular }}</td>
                            <td>{{ $particular->unit }}</td>
                            <td>{{ number_format($particular->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </table>
            @endforeach
        </div>
    @endif

    @if($materialList->materialsHire->count() > 0)
        <div style="margin-top: 5px;">
            <div class="section-title">MATERIALS FOR HIRE</div>
            <table class="excel-table">
                <tr>
                    <th>#</th>
                    <th>Particular</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                </tr>
                @foreach($materialList->materialsHire as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->particular }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>{{ number_format($item->quantity, 2) }}</td>
                        </tr>
                @endforeach
            </table>
        </div>
    @endif

    @if($materialList->labourItems->count() > 0)
        @foreach($labourItemsByCategory as $category => $items)
            <div style="margin-top: 5px;">
                <div class="section-title">{{ strtoupper(str_replace('_', ' ', $category)) }}</div>
                <table class="excel-table">
                    <tr>
                        <th>#</th>
                        <th>Description</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        </tr>
                    @foreach($items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->particular }}</td>
                            <td>{{ $item->unit }}</td>
                            <td>{{ number_format($item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endforeach
    @endif
    

    <div style="margin-top: 10px;">
        <table style="width: 100%; margin-top: 10px; font-size: 7px;">
            <tr>
                <td style="width: 50%; text-align: center;">
                    <div style="border-top: 1px solid #000; width: 150px; margin: 0 auto 3px auto;"></div>
                    <div>Prepared By</div>
                </td>
                <td style="width: 50%; text-align: center;">
                    <div style="border-top: 1px solid #000; width: 150px; margin: 0 auto 3px auto;"></div>
                    <div>Approved By</div>
                </td>
            </tr>
        </table>
    </div>
    
    <div style="text-align: center; font-size: 6px; color: #999; margin-top: 10px; border-top: 1px solid #eee; padding-top: 3px;">
        Generated on {{ now()->format('M d, Y H:i') }} | WOODNORKGREEN
    </div>

    <script type="text/php">
        if (isset($pdf)) {
            $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
            $size = 7;
            $font = $fontMetrics->getFont("Arial");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 15;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
</body>
</html>
