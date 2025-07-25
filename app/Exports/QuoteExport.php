<?php

namespace App\Exports;

use App\Models\Quote;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class QuoteExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithEvents
{
    protected $quote;

    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
    }

    public function collection()
    {
        $data = [];
        
        // Add header rows
        $data[] = ['QUOTE DOCUMENT', '', '', '', '', '', ''];
        $data[] = ['', '', '', '', '', '', ''];
        $data[] = ['Quote #:', $this->quote->id, 'Date:', $this->quote->quote_date->format('M d, Y'), 'Reference:', $this->quote->reference ?? 'N/A', ''];
        $data[] = ['Customer:', $this->quote->customer_name, 'Location:', $this->quote->location ?? 'N/A', 'Project:', $this->quote->project->name ?? 'N/A', ''];
        $data[] = ['Attention:', $this->quote->attention ?? 'N/A', 'Start Date:', $this->quote->project_start_date ? $this->quote->project_start_date->format('M d, Y') : 'N/A', 'Project ID:', $this->quote->project->project_id ?? 'N/A', ''];
        $data[] = ['', '', '', '', '', '', ''];
        
        // Add table headers
        $data[] = ['#', 'Item Name', 'Description', 'Qty', 'Quote Unit Price', 'Quote Price'];
        
        // Group line items by production items
        $groupedItems = [];
        $currentItemName = null;
        $itemCounter = 1;

        foreach ($this->quote->lineItems as $item) {
            $itemName = null;
            if (str_contains($item->comment ?? '', 'Item Name:')) {
                preg_match('/Item Name:\s*(.+?)(?:\s*\||$)/', $item->comment, $matches);
                $itemName = $matches[1] ?? 'Production Item';
            }
            $quoteUnitPrice = $item->quote_price / ($item->quantity ?: 1);
            $groupedItems[] = [
                'item_name' => $itemName,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'quote_unit_price' => $quoteUnitPrice,
                'quote_price' => $item->quote_price,
            ];
        }

        $rowCounter = 1;
        $totalCost = 0;
        $totalProfit = 0;
        $subtotal = 0;
        foreach ($groupedItems as $item) {
            $data[] = [
                $rowCounter++,
                $item['item_name'] ?? '-',
                $item['description'],
                number_format($item['quantity'], 2),
                number_format($item['quote_unit_price'], 2),
                number_format($item['quote_price'], 2)
            ];
            $itemCost = $item['quote_unit_price'] * $item['quantity'];
            $totalCost += $itemCost;
            $subtotal += $item['quote_price'];
            $totalProfit += $item['quote_price'] - $itemCost;
        }

        // Add totals row
        $data[] = ['', '', '', 'Totals:', number_format($totalCost, 2), number_format($totalCost > 0 ? ($totalProfit / $totalCost) * 100 : 0, 2) . '%', number_format($subtotal, 2)];
        
        // Add VAT and final total
        $vatRate = 0.16;
        $vatAmount = $subtotal * $vatRate;
        $total = $subtotal + $vatAmount;
        
        $data[] = ['', '', '', '', '', '', ''];
        $data[] = ['', '', '', 'Subtotal:', '', '', number_format($subtotal, 2)];
        $data[] = ['', '', '', 'VAT (16%):', '', '', number_format($vatAmount, 2)];
        $data[] = ['', '', '', 'Total:', '', '', number_format($total, 2)];

        return collect($data);
    }

    public function headings(): array
    {
        return ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // #
            'B' => 40,  // Description
            'C' => 12,  // Qty
            'D' => 15,  // Unit Price
            'E' => 15,  // Total Cost
            'F' => 15,  // Profit
            'G' => 15,  // Quote Price
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header styling
            1 => [
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '0BADD3']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            
            // Table headers
            7 => [
                'font' => ['bold' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0BADD3']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                // Merge cells for header
                $sheet->mergeCells('A1:G1');
                $sheet->mergeCells('A2:G2');
                
                // Merge cells for info rows
                $sheet->mergeCells('A3:B3');
                $sheet->mergeCells('C3:D3');
                $sheet->mergeCells('E3:F3');
                $sheet->mergeCells('A4:B4');
                $sheet->mergeCells('C4:D4');
                $sheet->mergeCells('E4:F4');
                $sheet->mergeCells('A5:B5');
                $sheet->mergeCells('C5:D5');
                $sheet->mergeCells('E5:F5');
                
                // Merge cells for item headers
                $lastRow = $sheet->getHighestRow();
                for ($row = 8; $row <= $lastRow; $row++) {
                    $cellValue = $sheet->getCell('A' . $row)->getValue();
                    if (is_string($cellValue) && !is_numeric($cellValue) && $cellValue !== '#' && !str_contains($cellValue, ':')) {
                        $sheet->mergeCells('A' . $row . ':G' . $row);
                        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('0BADD3'));
                        $sheet->getStyle('A' . $row)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8F9FA');
                    }
                }
                
                // Set specific alignments
                $sheet->getStyle('A7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('C7:G7')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Set row heights
                $sheet->getRowDimension(1)->setRowHeight(25);
                $sheet->getRowDimension(2)->setRowHeight(20);
                $sheet->getRowDimension(7)->setRowHeight(20);
            },
        ];
    }
} 