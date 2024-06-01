<?php

namespace App\Exports;

use App\Models\Orders;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Orders::all();
    }

    /**
     * Заголовки столбцов для экспорта в Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        // Заголовки столбцов, соответствующие вашему Blade-представлению
        return [
            'ID',
            'User ID',
            'Product ID',
            'Client ID',
            'Quantity',
            'Accept',
            'Created At',
            'Updated At',
        ];
    }
}
