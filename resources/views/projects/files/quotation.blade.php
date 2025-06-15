@extends('layouts.master')

@section('title', 'Quotation')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
        <div>
            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Back to Projects
            </a>
            <a href="{{ route('projects.files.index', $project) }}" class="btn btn-info me-2">
                <i class="bi bi-folder"></i> Project Files
            </a>
        </div>
    </div>


    <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
        <div>
            <h4 class="fw-semibold mb-3 text-dark">Quotation for <span class="text-primary">{{ $project->name }}</span></h4>
        </div>
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
        <form action="{{ route('projects.files.quotation.upload', ['project' => $project->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="file" class="form-label">Upload Quotation</label>
                <input type="file" class="form-control" id="file" name="file" required>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
            </form>
        </div>
    </div>

    <!-- File Preview Area -->
    <div class="mt-4">
        <h5>File Preview:</h5>
        <iframe id="filePreview" style="width: 100%; height: 500px; border: 1px solid #ddd;"></iframe>
        <p id="previewMessage" class="text-muted mt-2">Select a file to see its preview here.</p>
    </div>

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


</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('file');
        const filePreview = document.getElementById('filePreview');
        const previewMessage = document.getElementById('previewMessage');

        // Check for existing quotation on page load
        const existingQuotationPath = "{{ $project->quotation_path ?? '' }}";
        if (existingQuotationPath) {
            // We need to ensure Storage::url() is accessible here or pass the full URL from the controller.
            // For simplicity, assuming Storage::url() can be used if the path is known.
            // A safer way would be to pass the full URL via the controller if complex logic is needed for URL generation.
            const quotationUrl = `{{ $project->quotation_path ? Storage::url($project->quotation_path) : '' }}`;
            if (quotationUrl) {
                filePreview.src = quotationUrl;
                filePreview.style.display = 'block';
                previewMessage.textContent = 'Showing existing quotation. Select a new file to replace it.';
                previewMessage.style.display = 'block'; // Or 'none' if you prefer no message when preview is shown
            } else {
                 filePreview.style.display = 'none';
                 previewMessage.textContent = 'Could not load existing quotation. Select a file to upload.';
                 previewMessage.style.display = 'block';
            }
        } else {
            filePreview.style.display = 'none';
            previewMessage.textContent = 'No quotation uploaded yet. Select a file to upload.';
            previewMessage.style.display = 'block';
        }

        fileInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                
                // Check if the browser can display the file type in an iframe
                // Common types like PDF, TXT, and images are generally supported.
                // For others, it might download or show a blank page.
                if (['application/pdf', 'text/plain', 'image/jpeg', 'image/png', 'image/gif'].includes(file.type)) {
                    const objectUrl = URL.createObjectURL(file);
                    filePreview.src = objectUrl;
                    filePreview.style.display = 'block';
                    previewMessage.style.display = 'none';
                    
                    // Clean up the object URL when it's no longer needed
                    filePreview.onload = () => {
                        URL.revokeObjectURL(objectUrl);
                    }
                } else {
                    filePreview.style.display = 'none';
                    previewMessage.textContent = 'Preview is not available for this file type. Please upload to view.';
                    previewMessage.style.display = 'block';
                }
            } else {
                filePreview.src = '';
                filePreview.style.display = 'none';
                previewMessage.textContent = 'Select a file to see its preview here.';
                previewMessage.style.display = 'block';
            }
        });
    });
</script>
@endpush

@endsection

