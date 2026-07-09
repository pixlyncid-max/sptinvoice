<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'nomor_invoice', 'client_id', 'periode', 'tanggal_invoice',
        'tanggal_jatuh_tempo', 'status', 'catatan',
        'subtotal', 'pajak_persen', 'pajak_label', 'total', 'dengan_ttd',
        'bank_id'
    ];

    protected $casts = [
        'tanggal_invoice' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'subtotal' => 'decimal:2',
        'pajak_persen' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public static function generateNomor(): string
    {
        $tahun = now()->year;
        $count = self::whereYear('created_at', $tahun)->count() + 1;
        return 'INV/' . $tahun . '/' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'dikirim' => 'Dikirim',
            'dibayar' => 'Dibayar',
            'batal' => 'Batal',
            default => $this->status,
        };
    }
}
