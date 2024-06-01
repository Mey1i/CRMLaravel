<?php

namespace App\Exports;

use App\Models\Planner;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PlannerExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Planner::all();
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
            'Task',
            'Date',
            'Time',
            'Accept',
            'Created At',
            'Updated At',
        ];
    }
}

