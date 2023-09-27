<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;

class InvoicesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Invoice::get(
            ['id',
            'invoice_number',
            'invoice_date',
            'due_date',
            'product',
            'amount_collection',
            'amount_commission',
            'discount',
            'rate_vat',
            'value_vat',
            'total',
            'status',
            'note'
            ]);
    }
}
