<?php

namespace App\Exports;

use App\Models\Checkouts;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CheckoutExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Checkouts::with('inventory')->get()->map(function ($checkout) {
            return [
                'Batch ID'       => $checkout->check_out_id,
                'Item Name'      => $checkout->inventory->item_name,
                'Quantity'       => $checkout->quantity,
                'Checked Out By' => $checkout->checked_out_by,
                'Received By'    => $checkout->received_by,
                'Destination'    => $checkout->destination,
                'Date Checked'   => $checkout->created_at->format('Y-m-d H:i'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Checkout Batch ID',
            'Item Name',
            'Quantity',
            'Checked Out By',
            'Received By',
            'Destination',
            'Date Checked Out',
        ];
    }
}

