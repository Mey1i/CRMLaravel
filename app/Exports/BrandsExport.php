<?php

namespace App\Exports;

use App\Models\Brands;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BrandsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Brands::all();
    }

    public function headings(): array
    {
        // Updated headings for the Excel export
        return [
            'ID',
            'User ID',
            'Image',
            'Brand',
            'Created At',
            'Updated At',
            // Add more headings as needed
        ];
    }
}
