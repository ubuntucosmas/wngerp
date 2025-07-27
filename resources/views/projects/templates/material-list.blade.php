<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Material List - {{ isset($enquiry) && $enquiry->project_name ? $enquiry->project_name : ($project->name ?? '') }}</title>
    <style>
        @page { margin: 1cm; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 9px;
            background: #fff;
            margin: 0;
            padding: 0;
            color: #222;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #0BADD3;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }
        .logo {
            max-height: 32px;
        }
        .company-info {
            text-align: right;
            font-size: 8px;
            color: #666;
        }
        .title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            color: #0BADD3;
            margin: 0 0 2px 0;
        }
        .subtitle {
            text-align: center;
            font-size: 10px;
            color: #555;
            margin-bottom: 8px;
        }
        .summary-box {
            border: 1px solid #0BADD3;
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 12px;
            background: #f8faff;
        }
        .summary-table td {
            padding: 2px 8px;
            font-size: 9px;
        }
        .section-title {
            background-color: #0BADD3;
            color: #fff;
            font-weight: bold;
            text-align: left;
            font-size: 10px;
            padding: 4px 8px;
            margin: 12px 0 2px 0;
            border-radius: 4px 4px 0 0;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9px;
        }
        .data-table th, .data-table td {
            border: 1px solid #cce6f7;
            padding: 4px 6px;
            text-align: left;
        }
        .data-table th {
            background: #e0f3ff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 8px;
        }
        .data-table tr:nth-child(even) {
            background: #f6fbff;
        }
        .subtotal-row {
            background: #e0f3ff;
            font-weight: bold;
        }
        .grand-total {
            background: #0BADD3;
            color: #fff;
            font-size: 11px;
            font-weight: bold;
            text-align: right;
            padding: 6px 10px;
            border-radius: 0 0 6px 6px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #6E6F71;
            border-top: 1px solid #e0e0e0;
            padding-top: 3px;
        }
        .signature-block {
            margin-top: 18px;
            display: flex;
            justify-content: space-between;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 180px;
            margin: 0 auto 2px auto;
        }
        .signature-label {
            font-size: 9px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <img src="{{ public_path('images/wng-logo.png') }}" class="logo" alt="Company Logo">
        </div>
        <div class="title">MATERIAL LIST</div>
        <div class="company-info">
            Karen Village Art Centre, Ngong Rd Nairobi<br>
            www.woodnorkgreen.co.ke | admin@woodnorkgreen.co.ke<br>
            +254780 397798 | Generated: {{ now()->format('M d, Y H:i') }}
        </div>
    </div>

    <div class="summary-box">
        <table class="summary-table" width="100%">
        <tr>
                <td><strong>Reference:</strong></td>
            <td>ML-{{ isset($project) && $project ? str_pad($project->project_id, 5, '0', STR_PAD_LEFT) : 'N/A' }}</td>
                <td><strong>Date:</strong></td>
            <td>{{ $materialList->created_at->format('M d, Y') }}</td>
        </tr>
        <tr>
                <td><strong>Project:</strong></td>
                <td colspan="3">{{ isset($enquiry) && $enquiry->project_name ? $enquiry->project_name : ($project->name ?? '') }}</td>
        </tr>
        <tr>
                <td><strong>Client:</strong></td>
                <td>{{ isset($enquiry) && $enquiry->client_name ? $enquiry->client_name : ($project->client_name ?? 'N/A') }}</td>
                <td><strong>Venue:</strong></td>
                <td>{{ isset($enquiry) && $enquiry->venue ? $enquiry->venue : ($project->venue ?? 'N/A') }}</td>
        </tr>
        <tr>
                <td><strong>Start Date:</strong></td>
            <td>{{ $materialList->start_date ? \Carbon\Carbon::parse($materialList->start_date)->format('M d, Y') : 'N/A' }}</td>
                <td><strong>End Date:</strong></td>
            <td>{{ $materialList->end_date ? \Carbon\Carbon::parse($materialList->end_date)->format('M d, Y') : 'N/A' }}</td>
        </tr>
            <tr>
                <td><strong>Prepared By:</strong></td>
                <td>{{ $materialList->approved_by ?? '-' }}</td>
                <td><strong>Departments:</strong></td>
                <td>{{ $materialList->approved_departments ?? '-' }}</td>
            </tr>
    </table>
    </div>

    @php
        $nonEmptyProductionItems = $materialList->productionItems->filter(function($item) {
            return $item->particulars && $item->particulars->filter(function($p) {
                return !empty($p->particular) && $p->quantity > 0;
            })->isNotEmpty();
        });
        $nonEmptyMaterialsHire = $materialList->materialsHire->filter(function($item) {
            return (!empty($item->particular) || !empty($item->item_name)) && $item->quantity > 0;
        });
        $grandTotal = 0;
    @endphp
    @if($nonEmptyProductionItems->count() > 0)
        <div>
            <div class="section-title">PRODUCTION MATERIALS</div>
            @foreach($nonEmptyProductionItems as $item)
                @php
                    $filteredParticulars = $item->particulars->filter(function($p) {
                        return !empty($p->particular) && $p->quantity > 0;
                    });
                    $itemTotal = $filteredParticulars->sum(function($p) {
                        return ($p->quantity ?? 0) * ($p->unit_price ?? 0);
                    });
                    $grandTotal += $itemTotal;
                @endphp
                @if($filteredParticulars->isNotEmpty())
                <table class="data-table">
                    <tr>
                        <th colspan="6" style="background-color: #f0f8ff;">{{ $item->item_name }}</th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Particular</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                    @foreach($filteredParticulars as $index => $particular)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $particular->particular }}</td>
                            <td>{{ $particular->unit }}</td>
                            <td>{{ number_format($particular->quantity, 2) }}</td>
                            <td>{{ number_format($particular->unit_price, 2) }}</td>
                            <td>{{ number_format(($particular->quantity ?? 0) * ($particular->unit_price ?? 0), 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="subtotal-row">
                        <td colspan="5" class="text-end">Subtotal for {{ $item->item_name }}</td>
                        <td>{{ number_format($itemTotal, 2) }}</td>
                    </tr>
                </table>
                @endif
            @endforeach
        </div>
    @endif

    @if($nonEmptyMaterialsHire->count() > 0)
        <div style="margin-top: 5px;">
            <div class="section-title">MATERIALS FOR HIRE</div>
            <table class="data-table">
                <tr>
                    <th>#</th>
                    <th>Particular</th>
                    <th>Unit</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Subtotal</th>
                </tr>
                @foreach($nonEmptyMaterialsHire as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->particular }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>{{ number_format($item->quantity, 2) }}</td>
                        <td>{{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ number_format(($item->quantity ?? 0) * ($item->unit_price ?? 0), 2) }}</td>
                        </tr>
                @endforeach
                <tr class="subtotal-row">
                    <td colspan="5" class="text-end">Subtotal for Hire</td>
                    <td>{{ number_format($nonEmptyMaterialsHire->sum(function($item) { return ($item->quantity ?? 0) * ($item->unit_price ?? 0); }), 2) }}</td>
                </tr>
            </table>
            @php $grandTotal += $nonEmptyMaterialsHire->sum(function($item) { return ($item->quantity ?? 0) * ($item->unit_price ?? 0); }); @endphp
        </div>
    @endif

    @if($materialList->labourItems->count() > 0)
        @foreach($labourItemsByCategory as $category => $items)
            @php
                $filteredItems = collect($items)->filter(function($item) {
                    return (!empty($item->particular) || !empty($item->item_name)) && $item->quantity > 0;
                });
                $catTotal = $filteredItems->sum(function($item) { return ($item->quantity ?? 0) * ($item->unit_price ?? 0); });
                $grandTotal += $catTotal;
            @endphp
            @if($filteredItems->count() > 0)
            <div style="margin-top: 5px;">
                <div class="section-title">{{ strtoupper(str_replace('_', ' ', $category)) }}</div>
                <table class="data-table">
                    <tr>
                        <th>#</th>
                        <th>Description</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                        </tr>
                    @foreach($filteredItems as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->particular }}</td>
                            <td>{{ $item->unit }}</td>
                            <td>{{ number_format($item->quantity, 2) }}</td>
                            <td>{{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ number_format(($item->quantity ?? 0) * ($item->unit_price ?? 0), 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="subtotal-row">
                        <td colspan="5" class="text-end">Subtotal for {{ strtoupper(str_replace('_', ' ', $category)) }}</td>
                        <td>{{ number_format($catTotal, 2) }}</td>
                    </tr>
                </table>
            </div>
            @endif
        @endforeach
    @endif
    
    <div class="grand-total">
        Grand Total: {{ number_format($grandTotal, 2) }}
    </div>
    
    <div class="signature-block">
        <div>
            <div class="signature-line"></div>
            <div class="signature-label">Prepared By</div>
        </div>
        <div>
            <div class="signature-line"></div>
            <div class="signature-label">Approved By</div>
        </div>
    </div>

    <div class="footer">
        Generated on {{ now()->format('M d, Y H:i') }} | WOODNORKGREEN | Page <span class="pagenum"></span>
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
