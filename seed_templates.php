<?php

use App\Models\PenawaranTemplate;
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// 1. SURAT PENAWARAN JASA PERIZINAN DAN PERPAJAKAN
PenawaranTemplate::updateOrCreate(
    ['code' => 'TPL-PERIZINAN'],
    [
        'name' => 'SURAT PENAWARAN JASA PERIZINAN DAN PERPAJAKAN',
        'tujuan' => 'Membantu perusahaan Bapak/Ibu dalam mengurus legalitas, perizinan usaha, dan administrasi perpajakan yang sesuai dengan ketentuan peraturan perundang-undangan yang berlaku.',
        'lingkup' => '1. Pengurusan perizinan dasar (NIB, NPWP, SKT). 2. Pengurusan izin komersial/operasional. 3. Konsultasi dan pelaporan pajak bulanan/tahunan (SPT Masa, SPT Tahunan Badan).',
        'jenis_pekerjaan_intro' => 'Berdasarkan diskusi dan analisa kebutuhan, berikut adalah jenis layanan yang kami tawarkan:',
        'prasyarat' => 'Klien wajib menyediakan dokumen dasar perusahaan (Akta Pendirian, SK Kemenkumham, Domisili, dll) serta akses ke akun pelaporan pajak perusahaan.',
        'penutup' => 'Demikian penawaran ini kami sampaikan. Kami berharap dapat menjalin kerja sama yang baik dengan perusahaan Bapak/Ibu. Atas perhatiannya, kami ucapkan terima kasih.',
    ]
);

// 2. SURAT PENAWARAN JASA KEUANGAN DAN PERPAJAKAN
PenawaranTemplate::updateOrCreate(
    ['code' => 'TPL-KEUANGAN'],
    [
        'name' => 'SURAT PENAWARAN JASA KEUANGAN DAN PERPAJAKAN',
        'tujuan' => 'Memberikan solusi komprehensif terkait manajemen laporan keuangan dan perencanaan perpajakan (tax planning) untuk meningkatkan efisiensi dan kepatuhan perusahaan.',
        'lingkup' => '1. Penyusunan Laporan Keuangan (Neraca, Laba Rugi, Arus Kas). 2. Review pembukuan. 3. Perencanaan Pajak dan Pelaporan SPT Badan/Pribadi. 4. Pendampingan pemeriksaan pajak.',
        'jenis_pekerjaan_intro' => 'Kami menawarkan rincian pekerjaan di bidang keuangan dan perpajakan sebagai berikut:',
        'prasyarat' => 'Klien wajib memberikan akses ke seluruh data transaksi keuangan, rekening koran bulanan, serta dokumen faktur pajak dan kuitansi.',
        'penutup' => 'Besar harapan kami penawaran ini dapat menjadi langkah awal dari kemitraan kita. Kami siap berdiskusi lebih lanjut untuk menyesuaikan dengan kebutuhan perusahaan Anda.',
    ]
);

// 3. SURAT PENAWARAN JASA DIGITAL DAN DIGITAL MARKETING
PenawaranTemplate::updateOrCreate(
    ['code' => 'TPL-DIGITAL'],
    [
        'name' => 'SURAT PENAWARAN JASA DIGITAL DAN DIGITAL MARKETING',
        'tujuan' => 'Meningkatkan eksposur merek (brand awareness), memperluas jangkauan pasar, dan mengoptimalkan konversi penjualan melalui strategi digital marketing terintegrasi.',
        'lingkup' => '1. Manajemen Media Sosial (Instagram, TikTok, Facebook). 2. Optimasi Mesin Pencari (SEO & SEM). 3. Pembuatan aset digital (Desain & Copywriting). 4. Pelaksanaan dan evaluasi kampanye iklan (Ads).',
        'jenis_pekerjaan_intro' => 'Untuk mencapai target digitalisasi pemasaran, kami merancang paket pekerjaan berikut:',
        'prasyarat' => 'Klien menyetujui anggaran iklan (Ads Budget) secara terpisah, serta menyediakan brand guidelines, logo, dan akses ke platform media sosial terkait.',
        'penutup' => 'Kami sangat antusias untuk membantu perkembangan bisnis Bapak/Ibu di era digital ini. Terima kasih atas waktu dan kesempatan yang diberikan.',
    ]
);

echo "Templates seeded successfully.\n";
