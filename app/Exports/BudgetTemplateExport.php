<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class BudgetTemplateExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Production Items' => new ProductionItemsTemplate(),
            'Materials for Hire' => new MaterialsHireTemplate(),
            'Labour Items' => new LabourItemsTemplate(),
            'Other Items' => new OtherItemsTemplate(),
            'Instructions' => new InstructionsTemplate(),
        ];
    }
}

class ProductionItemsTemplate implements FromArray, WithTitle, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            ['Booth Setup', 'Aluminum Frame', 'pcs', 10, 150.00, '3x3m booth frame', 'BOOTH_001'],
            ['Booth Setup', 'Fabric Covering', 'sqm', 20, 25.00, 'White fabric covering', 'BOOTH_001'],
            ['Booth Setup', 'Flooring', 'sqm', 9, 35.00, 'Carpet flooring', 'BOOTH_001'],
            ['Stage Setup', 'Platform', 'sqm', 50, 80.00, 'Modular stage platform', 'STAGE_001'],
            ['Stage Setup', 'Backdrop', 'pcs', 1, 500.00, 'Custom backdrop design', 'STAGE_001'],
            ['Lighting Rig', 'LED Spots', 'pcs', 8, 75.00, 'RGB LED spotlights', 'LIGHT_001'],
            ['Lighting Rig', 'Control Console', 'pcs', 1, 300.00, 'DMX lighting console', 'LIGHT_001'],
        ];
    }

    public function headings(): array
    {
        return [
            'Item Name',
            'Particular',
            'Unit',
            'Quantity',
            'Unit Price',
            'Comment',
            'Template ID'
        ];
    }

    public function title(): string
    {
        return 'Production Items';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '4472C4']],
            ],
        ];
    }
}

class MaterialsHireTemplate implements FromArray, WithTitle, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            ['Sound System', 'Wireless Microphones', 'pcs', 4, 50.00, 'Professional wireless mics'],
            ['Sound System', 'Speakers', 'pcs', 2, 200.00, 'PA system speakers'],
            ['Sound System', 'Mixing Console', 'pcs', 1, 150.00, '12-channel mixer'],
            ['Furniture', 'Chairs', 'pcs', 100, 5.00, 'Plastic chairs'],
            ['Furniture', 'Tables', 'pcs', 20, 15.00, 'Round tables'],
            ['Decoration', 'Flowers', 'arrangements', 10, 25.00, 'Table centerpieces'],
        ];
    }

    public function headings(): array
    {
        return [
            'Item Name',
            'Particular',
            'Unit',
            'Quantity',
            'Unit Price',
            'Comment'
        ];
    }

    public function title(): string
    {
        return 'Materials for Hire';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '70AD47']],
            ],
        ];
    }
}

class LabourItemsTemplate implements FromArray, WithTitle, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            ['Workshop Labour', 'Carpentry Work', 'hours', 40, 25.00, 'Skilled carpenter for booth construction'],
            ['Workshop Labour', 'Painting', 'hours', 16, 20.00, 'Professional painting work'],
            ['Site', 'Setup Crew', 'days', 2, 200.00, '4-person setup crew'],
            ['Site', 'Security', 'days', 3, 150.00, 'Event security personnel'],
            ['Set Down', 'Breakdown Crew', 'days', 1, 180.00, '4-person breakdown crew'],
            ['Set Down', 'Cleaning', 'hours', 8, 15.00, 'Post-event cleaning'],
        ];
    }

    public function headings(): array
    {
        return [
            'Category',
            'Particular',
            'Unit',
            'Quantity',
            'Unit Price',
            'Comment'
        ];
    }

    public function title(): string
    {
        return 'Labour Items';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'FFC000']],
            ],
        ];
    }
}

class OtherItemsTemplate implements FromArray, WithTitle, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            ['Logistics', 'Transportation', 'trips', 2, 500.00, 'Truck rental for equipment transport'],
            ['Logistics', 'Fuel', 'liters', 100, 1.50, 'Fuel for transportation'],
            ['Outsourced', 'Catering', 'pax', 100, 15.00, 'Lunch service for attendees'],
            ['Outsourced', 'Photography', 'hours', 6, 80.00, 'Professional event photography'],
            ['Permits', 'Event License', 'pcs', 1, 200.00, 'Municipal event permit'],
            ['Insurance', 'Event Insurance', 'pcs', 1, 300.00, 'Comprehensive event insurance'],
        ];
    }

    public function headings(): array
    {
        return [
            'Category',
            'Particular',
            'Unit',
            'Quantity',
            'Unit Price',
            'Comment'
        ];
    }

    public function title(): string
    {
        return 'Other Items';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E74C3C']],
            ],
        ];
    }
}

class InstructionsTemplate implements FromArray, WithTitle, WithStyles
{
    public function array(): array
    {
        return [
            ['BUDGET IMPORT INSTRUCTIONS'],
            [''],
            ['This Excel file contains templates for importing budget data into the system.'],
            ['Please follow the structure exactly as shown in each sheet.'],
            [''],
            ['SHEET DESCRIPTIONS:'],
            [''],
            ['1. Production Items:'],
            ['   - Use for items that are grouped by main item (booth, stage, etc.)'],
            ['   - Item Name: Main category (e.g., "Booth Setup", "Stage Setup")'],
            ['   - Particular: Specific component (e.g., "Aluminum Frame", "Platform")'],
            ['   - Template ID: Optional reference for reusable templates'],
            [''],
            ['2. Materials for Hire:'],
            ['   - Use for rental items and equipment'],
            ['   - Item Name: Equipment category (e.g., "Sound System", "Furniture")'],
            ['   - Particular: Specific item (e.g., "Microphones", "Chairs")'],
            [''],
            ['3. Labour Items:'],
            ['   - Use for labor costs and services'],
            ['   - Category: Type of work (e.g., "Workshop Labour", "Site", "Set Down")'],
            ['   - Particular: Specific service (e.g., "Carpentry Work", "Setup Crew")'],
            [''],
            ['4. Other Items:'],
            ['   - Use for miscellaneous costs'],
            ['   - Category: Cost type (e.g., "Logistics", "Outsourced", "Permits")'],
            ['   - Particular: Specific cost (e.g., "Transportation", "Catering")'],
            [''],
            ['IMPORTANT NOTES:'],
            ['- All sheets must have the exact column headers as shown'],
            ['- Quantity and Unit Price are required and must be numbers'],
            ['- Unit Price should be the cost per unit (not total cost)'],
            ['- Total cost will be calculated automatically (Quantity × Unit Price)'],
            ['- Comment field is optional but recommended for clarity'],
            ['- Do not modify the sheet names or column headers'],
            ['- Empty rows will be skipped during import'],
            [''],
            ['VALIDATION:'],
            ['- The system will validate all data during import'],
            ['- Invalid rows will be skipped with error messages'],
            ['- You can review import results after upload'],
        ];
    }

    public function title(): string
    {
        return 'Instructions';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '2F4F4F']],
            ],
            'A6:A6' => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => '4472C4']],
            ],
            'A8:A8' => [
                'font' => ['bold' => true, 'color' => ['rgb' => '70AD47']],
            ],
            'A13:A13' => [
                'font' => ['bold' => true, 'color' => ['rgb' => '70AD47']],
            ],
            'A18:A18' => [
                'font' => ['bold' => true, 'color' => ['rgb' => '70AD47']],
            ],
            'A23:A23' => [
                'font' => ['bold' => true, 'color' => ['rgb' => '70AD47']],
            ],
            'A28:A28' => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'E74C3C']],
            ],
            'A37:A37' => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'E74C3C']],
            ],
        ];
    }
}