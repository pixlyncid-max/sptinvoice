<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenawaranTemplate extends Model
{
    protected $fillable = [
        'name', 'code', 'tujuan', 'lingkup', 
        'jenis_pekerjaan_intro', 'prasyarat', 'penutup'
    ];
}
