<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget #{{ $budget->id }} - Print</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: white;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 18px;
            color: #666;
            margin-bottom: 10px;
        }
        .budget-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-section {
            flex: 1;
        }
        .info-section h3 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 16px;
        }
        .info-item {
            margin-bottom: 5px;
            font-size: 14px;
        }
        .info-label {
            font-weight: bold;
            color: #666;
        }
        .budget-total {
            text-align: center;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .total-amount {
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
        }
        .budget-items {
            margin-bottom: 30px;
        }
        .category-section {
            margin-bottom: 25px;
        }
        .category-title {
            background: #e9ecef;
            padding: 10px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">WNG EVENTS</div>
        <div class="document-title">PROJECT BUDGET</div>
        <div>Budget #{{ $budget->id }}</div>
    </div>

    <div class="budget-info">
        <div class="info-section">
            <h3>Project Information</h3>
            <div class="info-item">
                <span class="info-label">Project:</span> 
                {{ isset($project) ? $project->name : ($enquiry ? $enquiry->project_name : 'N/A') }}
            </div>
            <div class="info-item">
                <span class="info-label">Budget Period:</span> 
                {{ \Carbon\Carbon::parse($budget->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($budget->end_date)->format('M d, Y') }}
            </div>
            <div class="info-item">
                <span class="info-label">Created:</span> 
                {{ $budget->created_at->format('M d, Y') }}
            </div>
        </div>
        <div class="info-section">
            <h3>Budget Details</h3>
            <div class="info-item">
                <span class="info-label">Prepared by:</span> 
                {{ $budget->approved_by ?? 'N/A' }}
            </div>
            <div class="info-item">
                <span class="info-label">Departments:</span> 
                {{ $budget->approved_departments ?? 'N/A' }}
            </div>
            <div class="info-item">
                <span class="info-label">Status:</span> 
                {{ ucfirst($budget->status ?? 'Draft') }}
            </div>
        </div>
    </div>

    <div class="budget-total">
        <div class="total-amount">KES {{ number_format($budget->budget_total, 2) }}</div>
        <div>Total Budget Amount</div>
    </div>

    <div class="budget-items">
        @php
            $groupedItems = $budget->items->groupBy('category');
        @endphp

        @foreach($groupedItems as $category => $items)
            @php
                $categoryTotal = $items->sum('budgeted_cost');
                $hasValidItems = $items->where('particular', '!=', '')->where('quantity', '>', 0)->count() > 0;
            @endphp
            
            @if($hasValidItems)
                <div class="category-section">
                    <div class="category-title">{{ $category }}</div>
                    <table>
                        <thead>
                            <tr>
                                <th>Particular</th>
                                <th>Unit</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Budgeted Cost</th>
                                <th>Comment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                @if($item->particular && $item->quantity > 0)
                                    <tr>
                                        <td>{{ $item->particular }}</td>
                                        <td>{{ $item->unit }}</td>
                                        <td>{{ number_format($item->quantity, 2) }}</td>
                                        <td>KES {{ number_format($item->unit_price, 2) }}</td>
                                        <td>KES {{ number_format($item->budgeted_cost, 2) }}</td>
                                        <td>{{ $item->comment ?? '' }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            <tr style="background: #f8f9fa; font-weight: bold;">
                                <td colspan="4" style="text-align: right;">Category Total:</td>
                                <td>KES {{ number_format($categoryTotal, 2) }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        @endforeach
    </div>

    <div class="footer">
        <p>This budget was generated on {{ now()->format('M d, Y \a\t g:i A') }}</p>
        <p>WNG Events - Professional Event Management Services</p>
    </div>

    <div class="no-print" style="position: fixed; top: 20px; right: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Print Budget
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Close
        </button>
    </div>
</body>
</html> 