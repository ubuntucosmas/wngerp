<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Quote - {{ $project->name ?? $enquiry->project_name ?? 'Quote' }}</title>
  <style>
    :root {
      --bs-primary: #0d6efd;
      --bs-success: #198754;
      --bs-info: #0dcaf0;
      --bs-warning: #ffc107;
      --bs-danger: #dc3545;
      --bs-light: #f8f9fa;
      --bs-secondary: #6c757d;
      --bs-dark: #212529;
      --bs-border-color: #dee2e6;
      --bs-table-hover: rgba(13, 110, 253, 0.05);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      font-size: 11px;
      color: var(--bs-dark);
      margin: 8mm;
      background: white;
      line-height: 1.4;
    }

    .container {
      max-width: 100%;
      margin: 0 auto;
      background: white;
    }

    /* Header */
    .header-section {
      display: grid;
      grid-template-columns: 120px 1fr 120px;
      align-items: center;
      margin-bottom: 15px;
      padding-bottom: 10px;
      border-bottom: 2px solid var(--bs-primary);
    }

    .logo img {
      height: 50px;
      width: auto;
    }

    .company-info {
      text-align: center;
    }

    .company-info h1 {
      font-size: 16px;
      color: var(--bs-primary);
      font-weight: 700;
      margin-bottom: 3px;
    }

    .company-info p {
      font-size: 9px;
      color: var(--bs-secondary);
      margin: 1px 0;
    }

    .quote-badge {
      text-align: right;
      font-weight: bold;
      color: var(--bs-primary);
      font-size: 12px;
    }

    /* Info Grid */
    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
      margin-bottom: 15px;
    }

    .info-section {
      border: 1px solid var(--bs-border-color);
      border-radius: 4px;
    }

    .info-header {
      background: var(--bs-light);
      padding: 6px 10px;
      font-weight: 600;
      font-size: 10px;
      text-transform: uppercase;
      color: var(--bs-primary);
      border-bottom: 1px solid var(--bs-border-color);
    }

    .info-content {
      padding: 8px 10px;
    }

    .info-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 4px;
      font-size: 10px;
    }

    .info-label {
      font-weight: 600;
      color: var(--bs-secondary);
      min-width: 60px;
    }

    .info-value {
      color: var(--bs-dark);
      text-align: right;
    }

    /* Items Table */
    .items-section {
      margin-bottom: 15px;
    }

    .section-header {
    background: #0dcaf0;
      color: white;
      padding: 8px 12px;
      font-weight: 600;
      font-size: 11px;
      text-transform: uppercase;
      margin-bottom: 0;
    }

    .items-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 10px;
      border: 1px solid var(--bs-border-color);
    }

    .items-table th,
    .items-table td {
      border: 1px solid var(--bs-border-color);
      padding: 6px;
    }

    .items-table th {
      background: var(--bs-light);
      font-weight: 600;
      font-size: 9px;
      text-transform: uppercase;
    }

    .items-table td {
      vertical-align: top;
    }

    .items-table tbody tr:nth-child(even) {
      background: var(--bs-light);
    }

    .items-table tbody tr:hover {
      background: var(--bs-table-hover);
    }

    .text-center { text-align: center; }
    .text-right { text-align: right; }
    .font-mono { font-family: 'Courier New', monospace; }

    /* Summary */
    .summary-section {
      margin-top: 15px;
      display: grid;
      grid-template-columns: 1fr 300px;
      gap: 20px;
    }

    .summary-table {
      width: 100%;
      border-collapse: collapse;
      font-size: 11px;
    }

    .summary-table td {
      padding: 6px 12px;
      border-bottom: 1px solid var(--bs-border-color);
    }

    .summary-label {
      text-align: right;
      font-weight: 600;
      color: var(--bs-secondary);
      width: 70%;
    }

    .summary-value {
      text-align: right;
      font-family: 'Courier New', monospace;
      font-weight: 600;
      color: var(--bs-success);
    }

    .total-row {
      background: var(--bs-success);
      color: white;
    }

    .total-row td {
      padding: 10px 12px;
      font-size: 12px;
      font-weight: 700;
    }

    /* Terms */
    .terms-section {
      font-size: 9px;
      line-height: 1.3;
      color: var(--bs-secondary);
      margin-top: 15px;
      padding: 10px;
      background: var(--bs-light);
      border-left: 3px solid var(--bs-primary);
    }

    .terms-title {
      font-weight: 600;
      color: var(--bs-primary);
      margin-bottom: 5px;
      font-size: 10px;
    }

    .terms-flex {
      display: flex;
      justify-content: space-between;
      gap: 20px;
    }

    .terms-left {
      flex: 2;
    }

    .terms-right {
      flex: 1;
    }

    .terms-left h5 {
      font-size: 9px;
      font-weight: 600;
      color: var(--bs-primary);
      margin: 10px 0 3px;
    }

    .terms-left ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .terms-left li {
      margin-bottom: 2px;
      padding-left: 8px;
      position: relative;
    }

    .terms-left li:before {
      content: "•";
      color: var(--bs-primary);
      position: absolute;
      left: 0;
    }

    .banking-info {
      background: #f0f8ff;
      padding: 8px;
      border-radius: 4px;
      border-left: 3px solid #0066cc;
    }

    .banking-info h6 {
      font-size: 9px;
      font-weight: 600;
      color: #0066cc;
      margin-bottom: 4px;
    }

    .banking-info .bank-detail {
      font-size: 8px;
      margin-bottom: 1px;
      font-family: 'Courier New', monospace;
    }

    .highlight-red {
      color: #dc3545;
      font-weight: 600;
    }

    /* Footer */
    .footer {
      text-align: center;
      font-size: 8px;
      color: var(--bs-secondary);
      margin-top: 20px;
      padding-top: 10px;
      border-top: 1px solid var(--bs-border-color);
    }

    /* Print */
    @media print {
      body { margin: 5mm; font-size: 10px; }
      .items-table tbody tr:hover { background: transparent; }
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Header -->
    <div class="header-section">
      <div class="logo">
        <img src="{{ public_path('images/wng-logo.png') }}" alt="Company Logo">
      </div>
      <div class="company-info">
        <h1>WOODNORKGREEN</h1>
        <p>Karen Village Art Centre, Ngong Rd Nairobi</p>
        <p>www.woodnorkgreen.co.ke | admin@woodnorkgreen.co.ke | +254780 397798</p>
      </div>
      <div class="quote-badge">
        QUOTE
      </div>
    </div>

    <!-- Info -->
    <div class="info-grid">
      <div class="info-section">
        <div class="info-header">Quote Details</div>
        <div class="info-content">
          <div class="info-row">
            <span class="info-label">Date:</span>
            <span class="info-value">{{ $quote->quote_date?->format('M d, Y') ?? 'N/A' }}</span>
          </div>
          <div class="info-row">
            <span class="info-label">Valid Until:</span>
            <span class="info-value">{{ $quote->quote_date?->addDays(15)->format('M d, Y') ?? 'N/A' }}</span>
          </div>
        </div>
      </div>

      <div class="info-section">
        <div class="info-header">Client Information</div>
        <div class="info-content">
          <div class="info-row">
            <span class="info-label">Client:</span>
            <span class="info-value bold">{{ $quote->customer_name }}</span>
          </div>
          @if($quote->customer_location)
          <div class="info-row">
            <span class="info-label">Location:</span>
            <span class="info-value">{{ $quote->customer_location }}</span>
          </div>
          @endif
          @if($quote->attention)
          <div class="info-row">
            <span class="info-label">Attention:</span>
            <span class="info-value">{{ $quote->attention }}</span>
          </div>
          @endif
          @if(isset($project) && $project)
          <div class="info-row">
            <span class="info-label">Project:</span>
            <span class="info-value">{{ $project->name }}</span>
          </div>
          @elseif(isset($enquiry) && $enquiry)
          <div class="info-row">
            <span class="info-label">Enquiry:</span>
            <span class="info-value">{{ $enquiry->project_name }}</span>
          </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Items -->
    <div class="items-section">
      <div class="section-header">Quote Items</div>
      @php 
        $subtotal = 0; 
        $itemNumber = 1;
      @endphp
      <table class="items-table">
        <thead>
          <tr>
            <th style="width: 5%;">#</th>
            <th style="width: 50%;">Description</th>
            <th style="width: 10%;">Qty</th>
            <th style="width: 15%;">Unit Price</th>
            <th style="width: 15%;">Amount</th>
          </tr>
        </thead>
        <tbody>
          @foreach($quote->lineItems as $item)
            @php $subtotal += $item->quote_price; @endphp
            <tr>
              <td class="text-center">{{ $itemNumber++ }}</td>
              <td>{{ $item->description }}</td>
              <td class="text-center">{{ number_format($item->quantity, 0) }}</td>
              <td class="text-right font-mono">{{ number_format($item->quote_price / $item->quantity, 2) }}</td>
              <td class="text-right font-mono bold">{{ number_format($item->quote_price, 2) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Summary -->
    <div class="summary-section">
      <div></div>
      <div>
        <table class="summary-table">
          <tr>
            <td class="summary-label">Subtotal:</td>
            <td class="summary-value">KES {{ number_format($subtotal, 2) }}</td>
          </tr>
          <tr>
            <td class="summary-label">VAT (16%):</td>
            <td class="summary-value">KES {{ number_format($subtotal * 0.16, 2) }}</td>
          </tr>
          <tr class="total-row">
            <td class="summary-label">Total Amount:</td>
            <td class="summary-value">KES {{ number_format($subtotal * 1.16, 2) }}</td>
          </tr>
        </table>
      </div>
    </div>

    <!-- Terms + Banking -->
    <div class="terms-section">
      <div class="terms-title">Terms & Conditions - Quote Valid for 15 Days</div>
      <div class="terms-flex">
        <div class="terms-left">
          <h5>Payment Terms</h5>
          <ul>
            <li>Deposit Payment: Within agreed timelines (per email)</li>
            <li>Balance Payment: Upon complete delivery</li>
            <li>Late Payment Penalty: 2% Monthly for late payments</li>
            <li>Production begins after receipt of LPO and payment of 70% deposit</li>
            <li>Total Quote amount is inclusive of 16% VAT</li>
          </ul>
          <h5>Client Obligations</h5>
          <ul>
            <li>Provide ample time for setup & branding</li>
            <li>Approve pre-production on time</li>
          </ul>
          <h5>Approval & Execution</h5>
          <ul>
            <li>Approval required before work starts</li>
            <li>Change requests will be billed separately</li>
          </ul>
        </div>
        <div class="terms-right">
          <h5>Banking Information</h5>
          <div class="banking-info">
            <h6>Cheques payable to: Woodnork Green Limited</h6>
            <div class="bank-detail"><strong>Account Name:</strong> Woodnork Green Ltd</div>
            <div class="bank-detail"><strong>Bank Name:</strong> <span class="highlight-red">NCBA Bank</span></div>
            <div class="bank-detail"><strong>Bank Code:</strong> 07000</div>
            <div class="bank-detail"><strong>Branch:</strong> Kenyatta Avenue</div>
            <div class="bank-detail"><strong>Branch Code:</strong> 125</div>
            <div class="bank-detail"><strong>Account Number:</strong> <span class="highlight-red">1002970089</span></div>
            <div class="bank-detail"><strong>SWIFT Code:</strong> CBAFKENX</div>
            <div class="bank-detail"><strong>Paybill:</strong> <span class="highlight-red">880100</span></div>
            <div class="bank-detail"><strong>A/C:</strong> <span class="highlight-red">1002970089</span></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="footer">
      <p><strong>Woodnork Green Ltd</strong> | Tel: +254 780 397 798 | Email: admin@woodnorkgreen.co.ke</p>
      <p>Karen Village, Ngong Road, Nairobi, Kenya | www.woodnorkgreen.co.ke</p>
      <p>Generated: {{ now()->format('M d, Y \a\t H:i') }} | &copy; {{ now()->year }} All rights reserved</p>
    </div>
  </div>
</body>
</html>
