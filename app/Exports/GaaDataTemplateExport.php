<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GaaDataTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                'PT Contoh Solusi Indonesia',
                '01.234.567.8-901.000',
                'KPP Pratama Jakarta Kebayoran Baru',
                'pajak@contoh.com',
                'PassEmail123!',
                '012345678901000',
                'DjpPass123!',
                '0123456789010001',
                '3171012345670001',
                'Budi Santoso',
                'CoretaxPass123!',
                'Laporan pajak periode 2026',
                'Sudah'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Perusahaan',
            'Nomor NPWP',
            'KPP',
            'Email',
            'Password Email',
            'DJP User',
            'DJP Password',
            'User NPWP 16',
            'PIC NIK',
            'PIC Nama',
            'Coretax Password',
            'Keterangan',
            'Checklist Coretax',
        ];
    }
}
