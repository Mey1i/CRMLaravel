<?php

namespace App\Exports;

use App\Models\Clients; // Make sure the model name is correctly capitalized
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Clients::all(); // Make sure the model name is correctly capitalized
    }

    public function headings(): array
    {
        // Adjusted headings to match the actual column names in the clients table
        return [
            'ID',
            'User ID',
            'Image',
            'Name',
            'Surname',
            'Email',
            'Telephone',
            'Company',
            'Created At',
            'Updated At',
            // Add more headings as needed
        ];
    }
}
