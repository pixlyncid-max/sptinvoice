<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmailContactTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            ['Budi Santoso', 'budi@example.com', 'PT Maju Bersama', 'Ya'],
            ['Siti Rahma', 'siti@example.com', 'CV Karya Utama', 'Ya'],
        ];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'Perusahaan',
            'Subscribed (Ya/Tidak)',
        ];
    }
}
