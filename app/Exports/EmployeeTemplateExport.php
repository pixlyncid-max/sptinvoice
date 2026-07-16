<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                'Budi Santoso', 
                '1234567890123456', 
                '1', 
                'A', 
                '2023-01-15', 
                'BCA', 
                '1234567890', 
                'Budi Santoso', 
                '5000000'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'NIK',
            'Position ID',
            'Grade',
            'Tanggal Masuk (YYYY-MM-DD)',
            'Bank',
            'No Rekening',
            'Nama Rekening',
            'Gaji Pokok',
        ];
    }
}
