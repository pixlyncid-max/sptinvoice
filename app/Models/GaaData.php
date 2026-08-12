<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GaaData extends Model
{
    use HasFactory;

    protected $table = 'gaa_data';

    protected $fillable = [
        'nama_perusahaan',
        'npwp',
        'kpp',
        'email',
        'password_email',
        'djp_user',
        'djp_password',
        'user_npwp_16',
        'pic_nik',
        'pic_nama',
        'coretax_password',
        'keterangan',
        'checklist_coretax',
    ];
}
