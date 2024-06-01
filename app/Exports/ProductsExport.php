<?php

namespace App\Exports;

use App\Models\Products;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Products::all();
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
            'Image',
            'Supplier ID',
            'Brand ID',
            'Name',
            'Purchase',
            'Sale',
            'Quantity',
            'Created At',
            'Updated At',
        ];
    }
}

