<table>
    <tr>
        <td colspan="8" align="center" style="font-size:22px;font-weight:bold;padding:10px 0;">Project Budget Export</td>
    </tr>
    <tr>
        <td colspan="8" align="center" style="padding-bottom:10px;">
            <!-- Logo Placeholder -->
            <img src="{{ public_path('images/wng-logo.png') }}" alt="Company Logo" height="50">
        </td>
    </tr>
</table>
<table>
    <tr>
        <th align="left">Project Name:</th>
        <td>{{ $project->name ?? '' }}</td>
        <th align="left">Client:</th>
        <td>{{ $project->client_name ?? '' }}</td>
        <th align="left">Venue:</th>
        <td>{{ $project->venue ?? '' }}</td>
    </tr>
    <tr>
        <th align="left">Start Date:</th>
        <td>{{ $project->start_date }}</td>
        <th align="left">End Date:</th>
        <td>{{ $project->end_date }}</td>
        <th align="left">Status:</th>
        <td>{{ ucfirst($budget->status) }}</td>
    </tr>
</table>
<br>
@foreach($grouped as $category => $items)
    @php
        $isProduction = str_replace(' ', '', strtolower($category)) === 'materials-production';
        $byItem = $items->groupBy('item_name');
    @endphp
    @if($isProduction)
        <table border="1" style="border-collapse:collapse; margin-bottom: 20px;">
            <thead style="background:#f0f0f0;">
                <tr>
                    <th colspan="7" style="font-size:16px;text-align:left;padding:8px;">Production Materials</th>
                </tr>
            </thead>
        </table>
        @foreach($byItem as $itemName => $particulars)
            @php $item = $particulars->first(); $itemTotal = $particulars->sum('budgeted_cost'); @endphp
            <table border="1" style="border-collapse:collapse; margin-bottom: 10px;">
                <thead style="background:#e3f2fd;">
                    <tr>
                        <th colspan="7" style="font-size:14px;text-align:left;padding:8px;">
                            <strong>{{ $itemName }}</strong>
                            @if($item->template)
                                <br><small>Template: {{ $item->template->name }}</small>
                            @endif
                        </th>
                    </tr>
                    <tr>
                        <th>Particular</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Cost</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($particulars as $particular)
                    <tr>
                        <td>{{ $particular->particular }}</td>
                        <td>{{ $particular->unit }}</td>
                        <td>{{ $particular->quantity }}</td>
                        <td>KES {{ number_format($particular->unit_price, 2) }}</td>
                        <td>KES {{ number_format($particular->budgeted_cost, 2) }}</td>
                        <td>{{ $particular->comment }}</td>
                    </tr>
                    @endforeach
                    <tr style="font-weight:bold;background:#e0f7fa;">
                        <td colspan="4" align="right">Subtotal for {{ $itemName }}</td>
                        <td colspan="2">KES {{ number_format($itemTotal, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach
        <table border="1" style="border-collapse:collapse; margin-bottom: 20px;">
            <tr style="font-weight:bold;background:#fff3e0;">
                <td colspan="4" align="right">Category Subtotal: Production Materials</td>
                <td colspan="2">KES {{ number_format($items->sum('budgeted_cost'), 2) }}</td>
            </tr>
        </table>
    @else
        <table border="1" style="border-collapse:collapse; margin-bottom: 20px;">
            <thead style="background:#f0f0f0;">
                <tr>
                    <th colspan="7" style="font-size:16px;text-align:left;padding:8px;">{{ $category }}</th>
                </tr>
                <tr>
                    <th>Particular</th>
                    <th>Unit</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Cost</th>
                    <th>Comment</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>{{ $item->particular }}</td>
                    <td>{{ $item->unit }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>KES {{ number_format($item->unit_price, 2) }}</td>
                    <td>KES {{ number_format($item->budgeted_cost, 2) }}</td>
                    <td>{{ $item->comment }}</td>
                </tr>
                @endforeach
                <tr style="font-weight:bold;background:#e0f7fa;">
                    <td colspan="4" align="right">Subtotal for {{ $category }}</td>
                    <td colspan="2">KES {{ number_format($items->sum('budgeted_cost'), 2) }}</td>
                </tr>
            </tbody>
        </table>
    @endif
@endforeach
<br>
<table>
    <tr>
        <th align="left">Total Budget:</th>
        <td style="font-size:16px;font-weight:bold;color:green;">KES {{ number_format($budget->budget_total, 2) }}</td>
    </tr>
</table>
<br>
<table>
    <tr>
        <th align="left">Approved By:</th>
        <td>{{ $budget->approved_by ?? '-' }}</td>
        <th align="left">Departments:</th>
        <td>{{ $budget->approved_departments ?? '-' }}</td>
        <th align="left">Approved At:</th>
        <td>{{ $budget->approved_at ? $budget->approved_at->format('d M Y H:i') : '-' }}</td>
    </tr>
</table> 