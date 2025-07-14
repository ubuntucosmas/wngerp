<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MaterialListExport implements FromView
{
    protected $materialList;
    protected $enquiry;
    protected $project;

    public function __construct($materialList, $enquiry = null, $project = null)
    {
        $this->materialList = $materialList;
        $this->enquiry = $enquiry;
        $this->project = $project;
    }

    public function view(): View
    {
        return view('exports.material-list', [
            'materialList' => $this->materialList,
            'enquiry' => $this->enquiry,
            'project' => $this->project,
        ]);
    }
} 