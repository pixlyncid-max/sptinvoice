<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    protected $table = 'inventaris';

    protected $fillable = [
        'kode_barang',
        'kategori',
        'nama_barang',
        'nama_merk',
        'tanggal_beli',
        'kondisi',
        'employee_id',
    ];

    protected $casts = [
        'tanggal_beli' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Generate kode barang berikutnya (GAA-[kategori_char]-[nomor]-[tahun])
     */
    public static function generateKode(string $kategori = 'elektronik', $tanggal_beli = null): string
    {
        // 1. Get first letter of category (uppercase)
        $char = match ($kategori) {
            'elektronik' => 'E',
            'furniture' => 'F',
            'alat_kerja' => 'A',
            'kendaraan' => 'K',
            default => strtoupper(substr($kategori, 0, 1)),
        };

        // 2. Get year
        $year = date('Y');
        if ($tanggal_beli) {
            $year = date('Y', strtotime($tanggal_beli));
        }

        // 3. Find the last number used in any code
        $codes = static::pluck('kode_barang')->toArray();
        $maxNumber = 0;

        foreach ($codes as $code) {
            // Split by '-'
            $parts = explode('-', $code);
            // Expected parts: ['GAA', 'E', '0', '2026']
            if (count($parts) >= 3) {
                // The number part is at index 2 (in format GAA-X-NUM-YEAR)
                $num = (int)$parts[2];
                if ($num > $maxNumber) {
                    $maxNumber = $num;
                }
            }
        }

        $nextNumber = $maxNumber + 1;

        return "GAA-{$char}-{$nextNumber}-{$year}";
    }

    /**
     * Label kategori yang human-readable
     */
    public function getKategoriLabelAttribute(): string
    {
        return match ($this->kategori) {
            'elektronik' => 'Elektronik',
            'furniture' => 'Furniture',
            'alat_kerja' => 'Alat Kerja',
            'kendaraan' => 'Kendaraan',
            default => $this->kategori,
        };
    }

    /**
     * Label kondisi yang human-readable
     */
    public function getKondisiLabelAttribute(): string
    {
        return match ($this->kondisi) {
            'baik' => 'Baik',
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
            default => $this->kondisi,
        };
    }
}
