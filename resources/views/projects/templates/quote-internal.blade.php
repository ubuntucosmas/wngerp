<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Internal Quote Analysis - {{ isset($project) ? $project->name : ($enquiry ? $enquiry->project_name : 'Quote') }}</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 11px;
      color: #0c2d48;
      margin: 10mm;
    }

    .container {
      max-width: 100%;
      margin: auto;
    }
    .logo img {
        text-align: center;
        margin-bottom: 10px;
        height: 60px;
    }

    .company-header {
      text-align: center;
      margin-bottom: 8px;
    }

    .company-header h1 {
      font-size: 16px;
      margin: 0;
      color: #145da0;
    }

    .company-header p {
      margin: 1px 0;
      font-size: 10px;
    }

    hr {
      border: none;
      border-top: 1px solid #b1d4e0;
      margin: 10px 0;
    }

    .section-title {
      font-weight: bold;
      background: #b1d4e0;
      padding: 4px 6px;
      color: #0c2d48;
      font-size: 10px;
      margin-bottom: 2px;
    }

    .internal-warning {
      background: #dc3545;
      color: white;
      padding: 8px;
      text-align: center;
      font-weight: bold;
      margin-bottom: 10px;
      border-radius: 4px;
    }

    .section {
      margin-bottom: 6px;
    }

    .two-col {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
    }

    .two-col div {
      width: 48%;
      margin-bottom: 2px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 6px;
      font-size: 10px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 4px 5px;
      text-align: left;
    }

    th {
      background: #eaf3fa;
      font-weight: bold;
    }

    .totals td {
      border: none;
      padding: 3px 5px;
    }

    .totals td.label {
      text-align: right;
      font-weight: bold;
      width: 85%;
    }

    .item-group {
      margin-bottom: 8px;
    }

    .item-group-title {
      background: #f8f9fa;
      padding: 3px 5px;
      font-weight: bold;
      font-size: 10px;
      border: 1px solid #ccc;
      border-bottom: none;
    }

    .profit-info {
      font-size: 9px;
      color: #28a745;
    }

    .cost-info {
      font-size: 9px;
      color: #6c757d;
    }

    .footer {
      text-align: center;
      font-size: 8px;
      margin-top: 10px;
      color: #777;
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Internal Warning -->
    <div class="internal-warning">
      ⚠️ INTERNAL DOCUMENT - CONFIDENTIAL - DO NOT SHARE WITH CLIENTS ⚠️
    </div>

    <!-- Header -->
    <div class="company-header">
    <div class="logo">
        <img src="{{ public_path('images/wng-logo.png') }}" alt="Company Logo">
    </div>
      <h1>WOODNORKGREEN - INTERNAL QUOTE ANALYSIS</h1>
      <p>Karen Village Art Centre, Ngong Rd Nairobi</p>
      <p>www.woodnorkgreen.co.ke | admin@woodnorkgreen.co.ke | +254780 397798</p>
    </div>

    <!-- Quote Info -->
    <hr>
    <div class="section">
      <div class="section-title">QUOTE INFORMATION</div>
      <div class="two-col">
        <div><strong>Date:</strong> {{ $quote->quote_date ? $quote->quote_date->format('Y-m-d') : 'N/A' }}</div>
        <div><strong>Quote #:</strong> Q-{{ str_pad($quote->id, 4, '0', STR_PAD_LEFT) }}</div>
        <div><strong>Ref:</strong> {{ $quote->reference }}</div>
        <div><strong>Start:</strong> {{ $quote->project_start_date ? $quote->project_start_date->format('Y-m-d') : 'N/A' }}</div>
      </div>
    </div>

    <!-- Client & Project Info -->
    <div class="section">
      <div class="section-title">CLIENT & PROJECT INFORMATION</div>
      <table style="width:100%; background:#f8f9fa; border-radius:6px; margin-bottom:8px;">
        <tr>
          <td style="width:50%; vertical-align:top; padding:8px 12px;">
            <div style="font-weight:bold; color:#198754; font-size:12px;">Customer</div>
            <div style="font-size:11px; font-weight:bold;">{{ $quote->customer_name }}</div>
            @if($quote->customer_location)
              <div style="color:#555; font-size:10px; margin-bottom:2px;">Location: {{ $quote->customer_location }}</div>
            @endif
            @if(isset($project) && $project)
              <div style="color:#888; font-size:10px;">Project ID: {{ $project->project_id }}</div>
              <div style="color:#888; font-size:10px;">Project Name: {{ $project->name }}</div>
            @elseif(isset($enquiry) && $enquiry)
              <div style="color:#888; font-size:10px;">Enquiry ID: {{ $enquiry->id }}</div>
              <div style="color:#888; font-size:10px;">Enquiry Name: {{ $enquiry->project_name }}</div>
            @endif
          </td>
          <td style="width:50%; vertical-align:top; padding:8px 12px;">
            @if($quote->attention)
              <div style="font-size:10px;"><strong>Attn:</strong> {{ $quote->attention }}</div>
            @endif
            @if($quote->reference)
              <div style="font-size:10px;"><strong>Ref:</strong> {{ $quote->reference }}</div>
            @endif
            @if($quote->project_start_date)
              <div style="font-size:10px;"><strong>Project Start:</strong> {{ $quote->project_start_date ? $quote->project_start_date->format('M d, Y') : 'N/A' }}</div>
            @endif
          </td>
        </tr>
      </table>
    </div>

    <!-- Detailed Internal Analysis -->
    <div class="section">
      <div class="section-title">DETAILED COST & PROFIT ANALYSIS</div>
      @php 
        $subtotal = 0; 
        $totalCost = 0;
        $totalProfit = 0;
        
        // Group items by item name (for production items) or description (for other items)
        $groupedItems = $quote->lineItems->groupBy(function($item) {
            if (str_contains($item->comment ?? '', 'Item Name:')) {
                return str_replace('Item Name: ', '', explode(' | ', $item->comment)[0]);
            }
            return $item->description;
        });
      @endphp
      
      @foreach($groupedItems as $itemName => $items)
        @php
          $itemTotalCost = $items->sum('total_cost');
          $itemTotalQuotePrice = $items->sum('quote_price');
          $itemTotalProfit = $itemTotalQuotePrice - $itemTotalCost;
          $subtotal += $itemTotalQuotePrice;
          $totalCost += $itemTotalCost;
          $totalProfit += $itemTotalProfit;
        @endphp
        
        <div class="item-group">
          <div class="item-group-title">
            {{ $loop->iteration }}. {{ $itemName }}
            @if($items->count() > 1)
              <span style="font-size:8px; color:#6c757d;">({{ $items->count() }} items)</span>
            @endif
          </div>
          
          <table>
            <thead>
              <tr>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Cost</th>
                <th>Total Cost</th>
                <th>Profit Margin</th>
                <th>Quote Price</th>
                <th>Profit Amount</th>
              </tr>
            </thead>
            <tbody>
              @foreach($items as $item)
                @php
                  $profitMargin = $item->profit_margin ?? 0;
                  $profitAmount = $item->quote_price - $item->total_cost;
                @endphp
                <tr>
                  <td>{{ $item->description }}</td>
                  <td>{{ number_format($item->quantity, 2) }}</td>
                  <td>{{ number_format($item->unit_price, 2) }}</td>
                  <td class="cost-info">{{ number_format($item->total_cost, 2) }}</td>
                  <td class="profit-info">{{ number_format($profitMargin, 2) }}%</td>
                  <td><strong>{{ number_format($item->quote_price, 2) }}</strong></td>
                  <td class="profit-info">+{{ number_format($profitAmount, 2) }}</td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr style="background:#f8f9fa;">
                <td colspan="3"><strong>Subtotal for {{ $itemName }}:</strong></td>
                <td class="cost-info"><strong>{{ number_format($itemTotalCost, 2) }}</strong></td>
                <td class="profit-info"><strong>{{ $itemTotalCost > 0 ? number_format(($itemTotalProfit / $itemTotalCost) * 100, 2) : '0.00' }}%</strong></td>
                <td><strong>{{ number_format($itemTotalQuotePrice, 2) }}</strong></td>
                <td class="profit-info"><strong>+{{ number_format($itemTotalProfit, 2) }}</strong></td>
              </tr>
            </tfoot>
          </table>
        </div>
      @endforeach
    </div>

    <!-- Internal Summary -->
    <div class="section">
      <div class="section-title">INTERNAL FINANCIAL SUMMARY</div>
      <table class="totals" style="width: 100%;">
        <tr>
          <td class="label">Total Internal Cost:</td>
          <td class="cost-info"><strong>{{ number_format($totalCost, 2) }} KES</strong></td>
        </tr>
        <tr>
          <td class="label">Total Profit:</td>
          <td class="profit-info"><strong>+{{ number_format($totalProfit, 2) }} KES ({{ $totalCost > 0 ? number_format(($totalProfit / $totalCost) * 100, 2) : '0.00' }}%)</strong></td>
        </tr>
        <tr>
          <td class="label">Quote Subtotal:</td>
          <td><strong>{{ number_format($subtotal, 2) }} KES</strong></td>
        </tr>
        <tr>
          <td class="label">VAT (16%):</td>
          <td>{{ number_format($subtotal * 0.16, 2) }} KES</td>
        </tr>
        <tr style="background:#e8f5e8;">
          <td class="label"><strong>Total Quote Amount:</strong></td>
          <td><strong>{{ number_format($subtotal * 1.16, 2) }} KES</strong></td>
        </tr>
        <tr style="background:#fff3cd;">
          <td class="label"><strong>Net Profit After VAT:</strong></td>
          <td class="profit-info"><strong>+{{ number_format($totalProfit, 2) }} KES ({{ $totalCost > 0 ? number_format(($totalProfit / $totalCost) * 100, 2) : '0.00' }}% margin)</strong></td>
        </tr>
      </table>
    </div>

    <!-- Footer -->
    <div class="footer">
      <p><strong>CONFIDENTIAL INTERNAL DOCUMENT</strong></p>
      <p>Generated on {{ now()->format('M d, Y \a\t H:i') }} by {{ auth()->user()->name ?? 'System' }}</p>
      <p>&copy; {{ now()->year }} WOODNORKGREEN. Internal Use Only.</p>
    </div>
  </div>
</body>
</html>