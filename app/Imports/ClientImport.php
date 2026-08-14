<?php

namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $perusahaan = $row['perusahaan'] ?? $row['nama'] ?? null;
        if (empty($perusahaan)) {
            return null;
        }

        return new Client([
            'perusahaan'      => $perusahaan,
            'nama'            => $row['nama'] ?? null,
            'email'           => $row['email'] ?? null,
            'telepon'         => $row['telepon'] ?? null,
            'alamat'          => $row['alamat'] ?? null,
            'jenis_pekerjaan' => isset($row['jenis_pekerjaan']) && in_array($row['jenis_pekerjaan'], ['Satuan', 'Bulanan', 'Tahunan']) ? $row['jenis_pekerjaan'] : 'Satuan',
            'status'          => isset($row['status']) && in_array($row['status'], ['Aktif', 'Non Aktif', 'Pending']) ? $row['status'] : 'Aktif',
        ]);
    }
}
