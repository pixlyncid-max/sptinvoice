<?php

namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Abaikan baris jika nama kosong
        if (empty($row['nama'])) {
            return null;
        }

        return new Client([
            'perusahaan' => $row['perusahaan'] ?? null,
            'nama'       => $row['nama'],
            'email'      => $row['email'] ?? null,
            'telepon'    => $row['telepon'] ?? null,
            'alamat'     => $row['alamat'] ?? null,
        ]);
    }
}
