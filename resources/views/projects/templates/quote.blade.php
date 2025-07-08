<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Quote - {{ $project->name }}</title>
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
            <div style="color:#888; font-size:10px;">Project ID: {{ $quote->project->project_id }}</div>
            <div style="color:#888; font-size:10px;">Project Name: {{ $quote->project->name }}</div>
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

    <!-- Items -->
    <div class="section">
      <div class="section-title">LINE ITEMS</div>
      <table>
        <thead>
          <tr>
            <th>Item Name</th>
            <th>Description</th>
            <th>Qty</th>
            <th>Quote Unit Price (KES)</th>
            <th>Quote Price (KES)</th>
          </tr>
        </thead>
        <tbody>
          @foreach($quote->lineItems as $item)
            @php
              $itemName = null;
              if (str_contains($item->comment ?? '', 'Item Name:')) {
                preg_match('/Item Name:\s*(.+?)(?:\s*\||$)/', $item->comment, $matches);
                $itemName = $matches[1] ?? 'Production Item';
              }
              $quoteUnitPrice = $item->quote_price / ($item->quantity ?: 1);
            @endphp
          <tr>
            <td>{{ $itemName ?? '-' }}</td>
            <td>{{ $item->description }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ number_format($quoteUnitPrice, 2) }}</td>
            <td>{{ number_format($item->quote_price, 2) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Totals -->
    @php
      $subTotal = $quote->lineItems->sum('total');
      $vat = $subTotal * 0.16;
      $grandTotal = $subTotal + $vat;
    @endphp

    <table class="totals">
      <tr>
        <td class="label">Sub Total:</td>
        <td>{{ number_format($subTotal, 2) }} KES</td>
      </tr>
      <tr>
        <td class="label">VAT (16%):</td>
        <td>{{ number_format($vat, 2) }} KES</td>
      </tr>
      <tr>
        <td class="label">Grand Total:</td>
        <td><strong>{{ number_format($grandTotal, 2) }} KES</strong></td>
      </tr>
    </table>

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
        {{ $quote->project->client_name }}
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
