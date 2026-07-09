<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RateCard extends Model
{
    protected $fillable = [
        'divisi', 'sub_kategori', 'nama_paket', 'kategori', 'deskripsi', 'harga', 'satuan', 'status'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    /**
     * Divisi labels for display
     */
    public static function divisiLabels(): array
    {
        return [
            'digital_marketing' => 'Digital Marketing & IT Solution',
            'keuangan_perpajakan' => 'Keuangan & Perpajakan',
            'perizinan' => 'Perizinan & Legal',
        ];
    }

    public function getDivisiLabelAttribute(): string
    {
        return self::divisiLabels()[$this->divisi] ?? $this->divisi;
    }
}
