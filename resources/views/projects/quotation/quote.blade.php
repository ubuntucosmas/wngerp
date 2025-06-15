@extends('layouts.master')

@section('title', 'Quotation')


@section('content')

<div class="quote-container">
    <h2 style="text-align: right;">DATE: 5/29/2025</h2>
    <h3 style="text-align: right;">QUOTE #: <span style="font-weight: normal;">[AUTO_GENERATE_OR_STATIC]</span></h3>

    <h2>Customer</h2>
    <p><strong>GamingTech Africa</strong><br>Nairobi, Kenya<br>Attn: Dawn</p>
    <p><strong>Expected Project Start:</strong> 2nd June</p>
    <p><strong>Ref:</strong> GamingTech Summit 3rd - 5th June_set 2nd June_Safari Park</p>

    <h3>DESCRIPTION</h3>
    <table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Description</th>
                <th>Days</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Amount (KES)</th>
            </tr>
        </thead>
        <tbody>
            {{-- Booth 1 --}}
            <tr><td colspan="5"><strong>1. BOOTH 6 by 3m Sharp Visual Booth</strong></td></tr>
            <tr><td>Hire of booth 6×3m by 2.4m high_branding on vinyl sticker</td><td>1</td><td>1</td><td>54,000.00</td><td>54,000.00</td></tr>
            <tr><td>Hire & installation of downlighters</td><td>1</td><td>7</td><td>1,500.00</td><td>10,500.00</td></tr>
            <tr><td>Fabrication & branding of 1×1m table</td><td>1</td><td>1</td><td>13,500.00</td><td>13,500.00</td></tr>
            <tr><td>Hire of brochure stand/day</td><td>3</td><td>1</td><td>2,500.00</td><td>7,500.00</td></tr>
            <tr><td>Hire of pot plants/item/day</td><td>3</td><td>2</td><td>2,500.00</td><td>15,000.00</td></tr>
            <tr><td>Hire of glass cocktail table/item/day</td><td>3</td><td>2</td><td>2,500.00</td><td>15,000.00</td></tr>
            <tr><td>Hire of Eams chairs/item/day</td><td>3</td><td>6</td><td>800.00</td><td>14,400.00</td></tr>
            <tr><td>Hire of cocktail stool/item/day</td><td>3</td><td>1</td><td>3,000.00</td><td>9,000.00</td></tr>

            {{-- Booth 2 --}}
            <tr><td colspan="5"><strong>2. BOOTH 4 by 4m Gamingtech Booth</strong></td></tr>
            <tr><td>Hire of booth 4×2.4m high_branding on vinyl sticker</td><td>1</td><td>1</td><td>24,000.00</td><td>24,000.00</td></tr>
            <tr><td>Hire of downlighters</td><td>1</td><td>3</td><td>1,500.00</td><td>4,500.00</td></tr>
            <tr><td>Fabricated shelf</td><td>1</td><td>1</td><td>22,500.00</td><td>22,500.00</td></tr>
            <tr><td>Fabricated & branded 1×1 table</td><td>1</td><td>1</td><td>13,500.00</td><td>13,500.00</td></tr>
            <tr><td>Fabricated curved table 1.8×1m</td><td>1</td><td>1</td><td>23,600.00</td><td>23,600.00</td></tr>
            <tr><td>Hire of cocktail stool/item/day</td><td>3</td><td>3</td><td>3,000.00</td><td>27,000.00</td></tr>
            <tr><td>Hire of Eams chairs/item/day</td><td>3</td><td>3</td><td>800.00</td><td>7,200.00</td></tr>
            <tr><td>Hire of glass cocktail table/item/day</td><td>3</td><td>1</td><td>2,500.00</td><td>7,500.00</td></tr>
            <tr><td>Hire of brochure stand/day</td><td>3</td><td>1</td><td>2,500.00</td><td>7,500.00</td></tr>
            <tr><td>Hire of pot plant/item/day</td><td>1</td><td>1</td><td>2,500.00</td><td>2,500.00</td></tr>

            {{-- Booth 3 --}}
            <tr><td colspan="5"><strong>3. BOOTHS 3 by 3m Maxima Seamless - Booth A & B</strong></td></tr>
            <tr><td>Booth walling 3×2.4m_branding on vinyl sticker</td><td>1</td><td>2</td><td>18,500.00</td><td>37,000.00</td></tr>
            <tr><td>Hired downlighters</td><td>1</td><td>8</td><td>1,500.00</td><td>12,000.00</td></tr>
            <tr><td>Fabricated 1×1 branded tables</td><td>1</td><td>2</td><td>13,500.00</td><td>27,000.00</td></tr>
            <tr><td>Hire of cocktail stool/item/day</td><td>3</td><td>2</td><td>3,000.00</td><td>18,000.00</td></tr>
            <tr><td>Hire of glass cocktail table/item/day</td><td>3</td><td>2</td><td>2,500.00</td><td>15,000.00</td></tr>
            <tr><td>Hire of Eams chairs/item/day</td><td>3</td><td>6</td><td>800.00</td><td>14,400.00</td></tr>
            <tr><td>Hire of brochure stand/day</td><td>3</td><td>2</td><td>2,500.00</td><td>15,000.00</td></tr>

            {{-- Logistics --}}
            <tr><td colspan="5"><strong>4. Logistics</strong></td></tr>
            <tr><td>Provision of setup & setdown labour/manpower</td><td>1</td><td>1</td><td>20,000.00</td><td>20,000.00</td></tr>
            <tr><td>Transport cost within Nairobi: Setup & setdown</td><td>1</td><td>1</td><td>20,000.00</td><td>20,000.00</td></tr>
        </tbody>
        <tfoot>
            <tr><td colspan="4" style="text-align: right;"><strong>Sub Total (KES)</strong></td><td>457,100.00</td></tr>
            <tr><td colspan="4" style="text-align: right;"><strong>VAT 16%</strong></td><td>73,136.00</td></tr>
            <tr><td colspan="4" style="text-align: right;"><strong>Total</strong></td><td><strong>530,236.00</strong></td></tr>
        </tfoot>
    </table>

    <h4>PAYMENT TERMS</h4>
    <ul>
        <li><strong>Deposit Payment:</strong> Within Agreed Timelines (Per Email)</li>
        <li><strong>Balance Payment:</strong> Upon complete delivery</li>
        <li><strong>Late Payment Penalty:</strong> 2% Monthly for Late Payments</li>
        <li><strong>Quotation is Valid for 15 Days</strong></li>
        <li><strong>Total Quote is Inclusive of 16% VAT</strong></li>
    </ul>

    <h4>CLIENT OBLIGATIONS</h4>
    <ul>
        <li>Setup & Branding Time – Client must provide ample time</li>
        <li>Pre-Production Approvals – Client must approve on time</li>
    </ul>

    <h4>APPROVAL & EXECUTION</h4>
    <p><strong>Approval Required Before Work:</strong> Client must approve before work starts</p>
</div>


@endsection
