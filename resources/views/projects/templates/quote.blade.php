<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Quote - {{ isset($project) ? $project->name : ($enquiry ? $enquiry->project_name : 'Quote') }}</title>
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

    .terms {
      font-size: 9px;
      line-height: 1.3;
      margin-top: 10px;
    }

    .terms ol {
      margin: 4px 0 0 15px;
    }

    .signature-section {
      display: flex;
      justify-content: space-between;
      margin-top: 12px;
    }

    .signature-box {
      width: 48%;
      border-top: 1px solid #ccc;
      padding-top: 3px;
      font-size: 9px;
      text-align: left;
    }

    .footer {
      text-align: center;
      font-size: 8px;
      margin-top: 10px;
      color: #777;
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

    .template-badge {
      background: #17a2b8;
      color: white;
      padding: 1px 4px;
      font-size: 8px;
      border-radius: 2px;
      margin-left: 4px;
    }

    .profit-info {
      font-size: 9px;
      color: #28a745;
    }

    .cost-info {
      font-size: 9px;
      color: #6c757d;
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Header -->
    <div class="company-header">
    <div class="logo">
        <img src="{{ public_path('images/wng-logo.png') }}" alt="Company Logo">
    </div>
      <h1>WOODNORKGREEN</h1>
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

    <!-- Client & Project Info (Redesigned) -->
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

    <!-- Detailed Items with Grouping -->
    <div class="section">
      <div class="section-title">DETAILED ITEM BREAKDOWN</div>
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
          $firstItem = $items->first();
        @endphp
        
        <div class="item-group">
          <div class="item-group-title">
            {{ $loop->iteration }}. {{ $itemName }}
            @if($firstItem->template)
              <span class="template-badge">Template: {{ $firstItem->template->name }}</span>
            @endif
            @if($items->count() > 1)
              <span style="font-size:8px; color:#6c757d;">({{ $items->count() }} items)</span>
            @endif
          </div>
          
          <table>
            <thead>
              <tr>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total Cost</th>
                <th>Profit Margin</th>
                <th>Quote Price</th>
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
                  <td class="profit-info">+{{ number_format($profitAmount, 2) }} ({{ number_format($profitMargin, 2) }}%)</td>
                  <td><strong>{{ number_format($item->quote_price, 2) }}</strong></td>
                </tr>
              @endforeach
            </tbody>
            <tfoot>
              <tr style="background:#f8f9fa;">
                <td colspan="3"><strong>Subtotal for {{ $itemName }}:</strong></td>
                <td class="cost-info"><strong>{{ number_format($itemTotalCost, 2) }}</strong></td>
                <td class="profit-info"><strong>+{{ number_format($itemTotalProfit, 2) }} ({{ $itemTotalCost > 0 ? number_format(($itemTotalProfit / $itemTotalCost) * 100, 2) : '0.00' }}%)</strong></td>
                <td><strong>{{ number_format($itemTotalQuotePrice, 2) }}</strong></td>
              </tr>
            </tfoot>
          </table>
        </div>
      @endforeach
    </div>

    <!-- Summary Totals -->
    <div class="section">
      <div class="section-title">PRICE SUMMARY</div>
      <table class="totals" style="width: 100%;">
        <tr>
          <td class="label">Total Cost:</td>
          <td class="cost-info">{{ number_format($totalCost, 2) }} KES</td>
        </tr>
        <tr>
          <td class="label">Total Profit:</td>
          <td class="profit-info">+{{ number_format($totalProfit, 2) }} KES ({{ $totalCost > 0 ? number_format(($totalProfit / $totalCost) * 100, 2) : '0.00' }}%)</td>
        </tr>
        <tr>
          <td class="label">Subtotal:</td>
          <td><strong>{{ number_format($subtotal, 2) }} KES</strong></td>
        </tr>
        <tr>
          <td class="label">VAT (16%):</td>
          <td>{{ number_format($subtotal * 0.16, 2) }} KES</td>
        </tr>
        <tr style="background:#e8f5e8;">
          <td class="label"><strong>Grand Total:</strong></td>
          <td><strong>{{ number_format($subtotal * 1.16, 2) }} KES</strong></td>
        </tr>
      </table>
    </div>

    <!-- Terms -->
    <div class="terms">
      <strong>TERMS AND CONDITIONS - Quotation is Valid for 15 Days</strong>
      <!-- Payment, Obligations, Approval -->
      <div class="terms">
        <h4>PAYMENT TERMS</h4>
        <ul>
          <li>Deposit Payment: 70% deposit to commence works</li>
          <li>Balance Payment: Provide PD cheque for 3 weeks upon complete delivery</li>
          <li>Late Payment Penalty: 2% Monthly for Late Payments</li>
          <li>Production begins after receipt of LPO and deposit</li>
          <li>The total quote amount is inclusive of 16% VAT</li>
        </ul>
  
        <h4>CLIENT OBLIGATIONS</h4>
        <ul>
          <li>Setup & Branding Time: Client must provide ample time for setup</li>
          <li>Pre-Production Approvals: Client must approve pre-production on time</li>
        </ul>
  
        <h4>APPROVAL & EXECUTION</h4>
        <ul>
          <li>Approval Required Before Work: Client must approve before work starts</li>
          <li>Change Requests: Any changes will be billed separately</li>
        </ul>
      </div>

    <!-- Signatures -->
    <div class="signature-section">
      <div class="signature-box">
        Authorized By:<br><br>
        ________________________<br>
        Woodnork Green
      </div>
      <div class="signature-box">
        Client Approval:<br><br>
        ________________________<br>
        @if(isset($project) && $project && $project->client)
          {{ $project->client->FullName }}
        @else
          {{ $quote->customer_name }}
        @endif
      </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer">
            <p>Woodnork Green Ltd | Tel: +254 780 397 798 or Email: admin@woodnorkgreen.co.ke  or
                Physical Address: Karen Village, Ngong Road, Nairobi, Kenya | Wesbsite: www.woodnorkgreen.co.ke</p>
          <p>Document generated on {{ now()->format('M d, Y \a\t H:i') }}</p>
          <p>&copy; {{ now()->year }} WOODNORKGREEN. All rights reserved.</p>
        </div>
    </div>
  </div>
</body>
</html>
