<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['employee_id', 'tanggal', 'status', 'lembur_jam', 'sakit_dengan_surat'];

    protected $casts = [
        'tanggal' => 'date',
        'lembur_jam' => 'float',
        'sakit_dengan_surat' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
