<?php

use App\Models\Setting;
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

Setting::truncate();

Setting::insert([
    ['key' => 'bpjs_kesehatan_persen', 'value' => '1.00', 'group' => 'Pajak & BPJS', 'created_at' => now(), 'updated_at' => now()],
    ['key' => 'bpjs_tk_persen', 'value' => '2.00', 'group' => 'Pajak & BPJS', 'created_at' => now(), 'updated_at' => now()],
    ['key' => 'pph21_persen', 'value' => '5.00', 'group' => 'Pajak & BPJS', 'created_at' => now(), 'updated_at' => now()],
    ['key' => 'batas_minimal_gaji_kena_pajak_bpjs', 'value' => '3000000', 'group' => 'Pajak & BPJS', 'created_at' => now(), 'updated_at' => now()],
    
    ['key' => 'potongan_sakit', 'value' => '50000', 'group' => 'Potongan Kehadiran', 'created_at' => now(), 'updated_at' => now()],
    ['key' => 'potongan_sakit_surat', 'value' => '0', 'group' => 'Potongan Kehadiran', 'created_at' => now(), 'updated_at' => now()],
    ['key' => 'potongan_telat_dibawah_1_jam', 'value' => '25000', 'group' => 'Potongan Kehadiran', 'created_at' => now(), 'updated_at' => now()],
    ['key' => 'potongan_telat_diatas_1_jam', 'value' => '50000', 'group' => 'Potongan Kehadiran', 'created_at' => now(), 'updated_at' => now()],
    ['key' => 'potongan_ijin', 'value' => '100000', 'group' => 'Potongan Kehadiran', 'created_at' => now(), 'updated_at' => now()],
    
    ['key' => 'lembur_weekday_jam_pertama', 'value' => '30000', 'group' => 'Uang Lembur', 'created_at' => now(), 'updated_at' => now()],
    ['key' => 'lembur_weekday_jam_berikutnya', 'value' => '40000', 'group' => 'Uang Lembur', 'created_at' => now(), 'updated_at' => now()],
    ['key' => 'lembur_weekend_per_jam', 'value' => '50000', 'group' => 'Uang Lembur', 'created_at' => now(), 'updated_at' => now()],
]);
echo "Settings seeded successfully.";
