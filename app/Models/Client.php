<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['nama', 'perusahaan', 'email', 'telepon', 'alamat'];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function penawaran()
    {
        return $this->hasMany(Penawaran::class);
    }

    public function rateCards()
    {
        return $this->hasMany(RateCard::class);
    }
}
