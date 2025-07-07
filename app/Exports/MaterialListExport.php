<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MaterialListExport implements FromView
{
    protected $materialList;

    public function __construct($materialList)
    {
        $this->materialList = $materialList;
    }

    public function view(): View
    {
        return view('exports.material-list', [
            'materialList' => $this->materialList,
        ]);
    }
} 