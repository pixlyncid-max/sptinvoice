<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penawaran extends Model
{
    protected $table = 'penawaran';

    protected $fillable = [
        'nomor_penawaran', 'perihal', 'client_id', 'tanggal',
        'berlaku_hingga', 'status', 'catatan',
        'subtotal', 'diskon', 'dengan_ttd', 'pajak_persen', 'pajak_label', 'total'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'berlaku_hingga' => 'date',
        'subtotal' => 'decimal:2',
        'diskon' => 'decimal:2',
        'pajak_persen' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(PenawaranItem::class);
    }

    public static function generateNomor(): string
    {
        $tahun = now()->year;
        $count = self::whereYear('created_at', $tahun)->count() + 1;
        return 'QUO/' . $tahun . '/' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'dikirim' => 'Dikirim',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            default => $this->status,
        };
    }
}
