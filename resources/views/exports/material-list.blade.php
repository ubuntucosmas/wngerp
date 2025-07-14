<table>
    <tr>
        <td colspan="8" align="center" style="font-size:22px;font-weight:bold;padding:10px 0;">Material List Export</td>
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
        <th align="left">{{ isset($enquiry) ? 'Enquiry' : 'Project' }} Name:</th>
        <td>{{ isset($enquiry) ? ($enquiry->project_name ?? 'N/A') : ($materialList->project->name ?? '') }}</td>
        <th align="left">Client:</th>
        <td>{{ isset($enquiry) ? ($enquiry->client_name ?? 'N/A') : ($materialList->project->client_name ?? '') }}</td>
        <th align="left">Venue:</th>
        <td>{{ isset($enquiry) ? ($enquiry->venue ?? 'N/A') : ($materialList->project->venue ?? '') }}</td>
    </tr>
    <tr>
        <th align="left">Approved By:</th>
        <td>{{ $materialList->approved_by }}</td>
        <th align="left">Departments:</th>
        <td>{{ $materialList->approved_departments }}</td>
        <th align="left">Status:</th>
        <td>{{ $materialList->status }}</td>
    </tr>
    <tr>
        <th align="left">Start Date:</th>
        <td>{{ $materialList->start_date }}</td>
        <th align="left">End Date:</th>
        <td>{{ $materialList->end_date }}</td>
        <th align="left">Duration:</th>
        <td>{{ $materialList->date_range }}</td>
    </tr>
</table>
<br>
@if($materialList->productionItems->count())
<table border="1" style="border-collapse:collapse;">
    <thead style="background:#f0f0f0;">
        <tr>
            <th colspan="7" style="font-size:16px;text-align:left;padding:8px;">Production Items</th>
        </tr>
        <tr>
            <th>#</th>
            <th>Item Name</th>
            <th>Description</th>
            <th>Particular</th>
            <th>Unit</th>
            <th>Quantity</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($materialList->productionItems as $item)
        @foreach ($item->particulars as $index => $particular)
        <tr>
            <td>{{ $loop->parent->iteration }}.{{ $index + 1 }}</td>
            <td>{{ $item->item_name }}</td>
            <td>{{ $item->description ?? '' }}</td>
            <td>{{ $particular->particular }}</td>
            <td>{{ $particular->unit }}</td>
            <td>{{ $particular->quantity }}</td>
        </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
@endif
<br>
@if($materialList->materialsHire->count())
<table border="1" style="border-collapse:collapse;">
    <thead style="background:#f0f0f0;">
        <tr>
            <th colspan="6" style="font-size:16px;text-align:left;padding:8px;">Materials for Hire</th>
        </tr>
        <tr>
            <th>#</th>
            <th>Item Name</th>
            <th>Particular</th>
            <th>Unit</th>
            <th>Quantity</th>
            <th>Comments</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($materialList->materialsHire as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->item_name }}</td>
            <td>{{ $item->particular }}</td>
            <td>{{ $item->unit }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->comment ?? '' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endif
<br>
@if($materialList->labourItems->count())
<table border="1" style="border-collapse:collapse;">
    <thead style="background:#f0f0f0;">
        <tr>
            <th colspan="7" style="font-size:16px;text-align:left;padding:8px;">Labour Items</th>
        </tr>
        <tr>
            <th>#</th>
            <th>Category</th>
            <th>Item Name</th>
            <th>Particular</th>
            <th>Unit</th>
            <th>Quantity</th>
            <th>Comments</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($materialList->labourItems as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->category }}</td>
            <td>{{ $item->item_name }}</td>
            <td>{{ $item->particular }}</td>
            <td>{{ $item->unit }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->comment ?? '' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
@endif 