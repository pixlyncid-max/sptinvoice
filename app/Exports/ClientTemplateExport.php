<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClientTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            ['PT Contoh Perusahaan', 'Budi Santoso', 'budi@contoh.com', '081234567890', 'Jl. Jendral Sudirman No. 1, Jakarta']
        ];
    }

    public function headings(): array
    {
        return [
            'Perusahaan',
            'Nama',
            'Email',
            'Telepon',
            'Alamat',
        ];
    }
}
