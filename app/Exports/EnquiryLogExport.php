<?php

namespace App\Exports;

use App\Models\Enquiry;
use App\Models\EnquiryLog;
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

class EnquiryLogExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithEvents
{
    protected $enquiry;
    protected $enquiryLog;

    public function __construct(Enquiry $enquiry, EnquiryLog $enquiryLog)
    {
        $this->enquiry = $enquiry;
        $this->enquiryLog = $enquiryLog;
    }

    public function collection()
    {
        $data = [];
        
        // Add header rows
        $data[] = ['ENQUIRY LOG DOCUMENT', '', '', '', '', '', ''];
        $data[] = ['', '', '', '', '', '', ''];
        $data[] = ['Enquiry ID:', $this->enquiry->id, 'Date Received:', $this->enquiryLog->date_received->format('M d, Y'), 'Status:', $this->enquiryLog->status, ''];
        $data[] = ['Client Name:', $this->enquiryLog->client_name, 'Contact Person:', $this->enquiryLog->contact_person ?? 'N/A', 'Venue:', $this->enquiryLog->venue, ''];
        $data[] = ['Project Name:', $this->enquiry->project_name ?? 'N/A', 'Assigned To:', $this->enquiryLog->assigned_to ?? 'N/A', 'Enquiry Type:', 'Enquiry', ''];
        $data[] = ['', '', '', '', '', '', ''];
        
        // Add project scope summary
        $scopeSummary = json_decode($this->enquiryLog->project_scope_summary, true);
        if ($scopeSummary && is_array($scopeSummary)) {
            $data[] = ['Project Scope Summary:', '', '', '', '', '', ''];
            foreach ($scopeSummary as $index => $scope) {
                $data[] = [($index + 1) . '.', $scope, '', '', '', '', ''];
            }
            $data[] = ['', '', '', '', '', '', ''];
        }
        
        // Add follow up notes
        if ($this->enquiryLog->follow_up_notes) {
            $data[] = ['Follow Up Notes:', '', '', '', '', '', ''];
            $data[] = [$this->enquiryLog->follow_up_notes, '', '', '', '', '', ''];
            $data[] = ['', '', '', '', '', '', ''];
        }
        
        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Field',
            'Value',
            'Field 2',
            'Value 2',
            'Field 3',
            'Value 3',
            'Empty'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 25,
            'B' => 30,
            'C' => 20,
            'D' => 30,
            'E' => 15,
            'F' => 20,
            'G' => 10,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 16],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0BADD3']],
            ],
            3 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E3F2FD']],
            ],
            4 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E3F2FD']],
            ],
            5 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E3F2FD']],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:G' . $event->sheet->getHighestRow())->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
} 