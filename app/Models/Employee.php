<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'nama', 'nik', 'division_id', 'position_id', 'jabatan', 'grade', 'tgl_masuk', 
        'bank', 'no_rekening', 'nama_rekening', 'gaji_pokok'
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    protected $casts = [
        'tgl_masuk' => 'date',
    ];
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function salaryAdjustments()
    {
        return $this->hasMany(SalaryAdjustment::class);
    }

    public function inventaris()
    {
        return $this->hasMany(Inventaris::class);
    }
}
