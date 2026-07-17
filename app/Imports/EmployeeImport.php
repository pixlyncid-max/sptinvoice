<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Position;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeeImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['nama']) || empty($row['gaji_pokok'])) {
            return null;
        }

        $position = null;
        if (!empty($row['position_id'])) {
            $position = Position::find($row['position_id']);
        }

        $tglMasuk = null;
        if (!empty($row['tanggal_masuk_yyyy_mm_dd'])) {
            // Check if it's an excel date (numeric) or string
            if (is_numeric($row['tanggal_masuk_yyyy_mm_dd'])) {
                $tglMasuk = Date::excelToDateTimeObject($row['tanggal_masuk_yyyy_mm_dd'])->format('Y-m-d');
            } else {
                $tglMasuk = date('Y-m-d', strtotime($row['tanggal_masuk_yyyy_mm_dd']));
            }
        }

        return new Employee([
            'nama' => $row['nama'],
            'nik' => $row['nik'] ?? null,
            'position_id' => $position ? $position->id : null,
            'jabatan' => $position ? $position->name : null,
            'tgl_masuk' => $tglMasuk,
            'bank' => $row['bank'] ?? null,
            'no_rekening' => $row['no_rekening'] ?? null,
            'nama_rekening' => $row['nama_rekening'] ?? null,
            'gaji_pokok' => $row['gaji_pokok'],
        ]);
    }
}
