<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class EquipmentExport implements FromView
{
    protected $equipments;

    public function __construct($equipments)
    {
        $this->equipments = $equipments;
    }

    public function view(): View
    {
        return view('backend.pages.project.export-equipment', [
            'equipments' => $this->equipments
        ]);
    }
}
