<?php

namespace App\Exports;

use App\Models\Suppliers;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SuppliersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Suppliers::all();
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
            'Firm',
            'Name',
            'Surname',
            'Email',
            'Telephone',
            'Address',
            'Created At',
            'Updated At',
        ];
    }
}
