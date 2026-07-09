<?php

use App\Models\PenawaranTemplate;
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$keuangan = PenawaranTemplate::where('code', 'keuangan')->first();
$perizinan = PenawaranTemplate::where('code', 'perizinan')->first();
if ($keuangan && $perizinan) {
    $perizinan->prasyarat = $keuangan->prasyarat;
    $perizinan->save();
    echo "Updated perizinan prasyarat to match keuangan\n";
} else {
    echo "Templates not found\n";
}
