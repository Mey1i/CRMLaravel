<?php

namespace App\Exports;

use App\Models\Departments; // Make sure the model name is correctly capitalized
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DepartmentsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Departments::all(); // Make sure the model name is correctly capitalized
    }

    public function headings(): array
    {
        // Adjusted headings to match the actual column names in the clients table
        return [
            'ID',
            'User ID',
            'Department',
            'Created At',
            'Updated At',
            // Add more headings as needed
        ];
    }
}
