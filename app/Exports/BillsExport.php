<?php

namespace App\Exports;

use App\Bill;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;

class BillsExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Bill::all();
    }

    public function view(): view{
        return view('exports.bills',[
            'bills' => Bill::get()
        ]);
    }
}
