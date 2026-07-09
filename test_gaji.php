<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$e = App\Models\Employee::where('nama', 'like', '%Akhmad Difa%')->first();
$s_min_gaji = \App\Models\Setting::get('batas_minimal_gaji_kena_pajak_bpjs', 3000000);
$gaji_pokok = $e->gaji_pokok;

echo "Gaji pokok: " . $gaji_pokok . " (type: " . gettype($gaji_pokok) . ")\n";
echo "Min gaji: " . $s_min_gaji . " (type: " . gettype($s_min_gaji) . ")\n";

$eligible = ($gaji_pokok >= $s_min_gaji);
echo "Eligible? " . ($eligible ? 'YES' : 'NO') . "\n";

$gaji_float = (float) $gaji_pokok;
$min_float = (float) $s_min_gaji;

$eligible_float = ($gaji_float >= $min_float);
echo "Eligible float? " . ($eligible_float ? 'YES' : 'NO') . "\n";
