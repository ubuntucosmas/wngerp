<?php

namespace App\Exports;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InventoryExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Inventory::with('category')->get()->map(function ($item) {
            return [
                'SKU'               => $item->sku,
                'Item Name'         => $item->item_name,
                'Category'          => $item->category->category_name ?? 'No Category',
                'Unit'              => $item->unit_of_measure,
                'Stock On Hand'     => $item->stock_on_hand,
                'Checked In'        => $item->quantity_checked_in,
                'Checked Out'       => $item->quantity_checked_out,
                'Returns'           => $item->returns,
                'Supplier'          => $item->supplier,
                'Unit Price'        => $item->unit_price,
                'Total Value'       => $item->total_value,
                'Order Date'        => $item->order_date,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Item Name',
            'Category',
            'Unit',
            'Stock On Hand',
            'Checked In',
            'Checked Out',
            'Returns',
            'Supplier',
            'Unit Price',
            'Total Value',
            'Order Date',
        ];
    }
}

