<?php

namespace App\Exports;

use App\Models\Positions;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PositionsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Positions::all();
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
            'Department ID',
            'Position ID',
            'Created At',
            'Updated At',
        ];
    }
}

