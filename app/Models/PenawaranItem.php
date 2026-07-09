<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenawaranItem extends Model
{
    protected $fillable = ['penawaran_id', 'deskripsi', 'keterangan', 'kategori_layanan', 'qty', 'harga_satuan', 'subtotal'];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function penawaran()
    {
        return $this->belongsTo(Penawaran::class);
    }
}
