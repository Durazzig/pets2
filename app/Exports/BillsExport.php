<?php

namespace App\Exports;

use App\Bill;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;

class BillsExport implements FromView
{

    protected $bills;

    function __construct($bills) {
            $this->bills = $bills;
    }

    public function view(): view{
        return view('exports.bills',[
            'bills' => $this->bills
        ]);
    }
}
