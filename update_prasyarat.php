<?php

use App\Models\PenawaranTemplate;
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$htmlText = "<ol class=\"prasyarat\">\n<li>Perusahaan perlu menunjuk Staf yang kompeten untuk menjadi <i>counterpart</i> yang akan membantu tim kami mengakses data, informasi dan dokumen yang diperlukan selama penugasan. Pekerjaan lapangan dimulai setelah semua data and informasi secara lengkap.</li>\n<li>Di dalam melakukan pekerjaan, kami akan menggunakan media komunikasi, baik lisan, tertulis, dan secara elektronik seperti surat, memo dan email yang merupakan komunikasi yang diterima.</li>\n<li>Perusahaan menyatakan dan menjamin bahwa tidak melakukan perikatan lain dengan konsultan pajak lainnya untuk melakukan pekerjaan yang sesuai dengan ruang lingkup pekerjaan sebagaimana tertuang dalam surat ini.</li>\n<li>Manajemen membolehkan Bank Kreditor untuk berkomunikasi dan meminta informasi kepada kami.</li>\n</ol>";

$keuangan = PenawaranTemplate::where('code', 'keuangan')->first();
$perizinan = PenawaranTemplate::where('code', 'perizinan')->first();

if ($perizinan) {
    $perizinan->prasyarat = $htmlText;
    $perizinan->save();
}

if ($keuangan) {
    $keuangan->prasyarat = $htmlText;
    $keuangan->save();
}

echo "Updated prasyarat to HTML for both.\n";
