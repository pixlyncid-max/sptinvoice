<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    protected $table = 'inventaris';

    protected $fillable = [
        'kode_barang',
        'inventaris_category_id',
        'nama_barang',
        'nama_merk',
        'tanggal_beli',
        'kondisi',
        'employee_id',
    ];

    protected $casts = [
        'tanggal_beli' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(InventarisCategory::class, 'inventaris_category_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Generate kode barang berikutnya (GAA-[kategori_char]-[nomor]-[tahun])
     */
    public static function generateKode($categoryId, $tanggal_beli = null): string
    {
        $category = InventarisCategory::find($categoryId);
        
        // 1. Get prefix from category
        $char = $category ? ($category->prefix ?? strtoupper(substr($category->name, 0, 1))) : 'U';

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
        return $this->category ? $this->category->name : '-';
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
