<?php

namespace App\Imports;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\ToModel;

class InventoryImport implements ToModel
{
    public function model(array $row)
    {
        return new Inventory([
            'sku' => $row[0],
            'item_name' => $row[1],
            'category' => $row[2],
            'unit_of_measure' => $row[3],
            'stock_on_hand' => is_numeric($row[4]) ? (int)$row[4] : 0, // Parse integers, default to 0 if invalid
            'quantity_checked_in' => is_numeric($row[5]) ? (int)$row[5] : 0,
            'quantity_checked_out' => is_numeric($row[6]) ? (int)$row[6] : 0,
            'returns' => is_numeric($row[7]) ? (int)$row[7] : 0,
            'supplier' => $row[8],
            'unit_price' => is_numeric($row[9]) ? (float)$row[9] : 0.0,
            'total_value' => is_numeric($row[10]) ? (float)$row[10] : 0.0,
            'order_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[11] ?? null) ?? now(), // Convert Excel date
        ]);
    }
}