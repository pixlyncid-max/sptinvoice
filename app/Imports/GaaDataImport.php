<?php

namespace App\Imports;

use App\Models\GaaData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GaaDataImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Cari nama perusahaan dari berbagai variasi heading
        $namaPerusahaan = $row['nama_perusahaan'] ?? $row['perusahaan'] ?? $row['nama'] ?? null;

        if (empty($namaPerusahaan)) {
            return null;
        }

        $checklist = $row['checklist_coretax'] ?? $row['status_coretax'] ?? $row['checklist'] ?? 'Belum';
        $checklistFormatted = (strcasecmp(trim($checklist), 'Sudah') === 0) ? 'Sudah' : 'Belum';

        return new GaaData([
            'nama_perusahaan'  => trim($namaPerusahaan),
            'npwp'             => $row['nomor_npwp'] ?? $row['npwp'] ?? null,
            'kpp'              => $row['kpp'] ?? null,
            'email'            => $row['email'] ?? null,
            'password_email'   => $row['password_email'] ?? $row['pass_email'] ?? null,
            'djp_user'         => $row['djp_user'] ?? $row['user_djp'] ?? $row['djp_online_user'] ?? null,
            'djp_password'     => $row['djp_password'] ?? $row['pass_djp'] ?? $row['djp_online_password'] ?? null,
            'user_npwp_16'     => $row['user_npwp_16'] ?? $row['npwp_16'] ?? null,
            'pic_nik'          => $row['pic_nik'] ?? $row['nik_pic'] ?? $row['nik'] ?? null,
            'pic_nama'         => $row['pic_nama'] ?? $row['nama_pic'] ?? null,
            'coretax_password' => $row['coretax_password'] ?? $row['pass_coretax'] ?? null,
            'keterangan'       => $row['keterangan'] ?? null,
            'checklist_coretax'=> $checklistFormatted,
        ]);
    }
}
