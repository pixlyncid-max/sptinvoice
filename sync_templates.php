<?php

use App\Models\PenawaranTemplate;
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Truncate to clean up the old TPL-PERIZINAN etc that I created earlier
PenawaranTemplate::truncate();

// 1. SURAT PENAWARAN JASA PERIZINAN DAN PERPAJAKAN
PenawaranTemplate::create([
    'code' => 'perizinan',
    'name' => 'SURAT PENAWARAN JASA PERIZINAN DAN PERPAJAKAN',
    'tujuan' => 'Sehubungan dengan rencana pengurusan legalitas dan kewajiban perpajakan perusahaan {client_name} ("PERUSAHAAN"), bersama surat ini kami mengajukan penawaran jasa perizinan dan perpajakan untuk membantu proses administrasi perusahaan agar sesuai dengan ketentuan peraturan perundang-undangan yang berlaku di Indonesia.',
    'lingkup' => "Tujuan dari penugasan ini adalah untuk membantu dalam memastikan seluruh perizinan serta perpajakan yang dibutuhkan for operasional perusahaan dapat diperoleh dengan cara yang efektif, efisien, dan sesuai dengan ketentuan yang berlaku.\n\nKami berkomitmen untuk mendukung {client_name} dalam memenuhi persyaratan administratif dan regulasi yang diberlakukan by instansi pemerintah terkait. Dengan demikian, perusahaan dapat menjalankan aktivitas usahanya dengan lancar tanpa kendala hukum, serta meminimalkan risiko sanksi atau denda yang mungkin timbul akibat ketidaksesuaian atau keterlambatan dalam memperoleh izin.\n\nAdapun ruang lingkup pekerjaan yang kami tawarkan meliputi pembuatan akun Coretax hingga terbitnya NPWP Badan, Akun OSS RBA hingga terbitnya Nomor Induk Berusaha (NIB) sebagai identitas resmi usaha, serta pengurusan proses pengukuhan sebagai Pengusaha Kena Pajak (PKP), termasuk pendampingan administrasi hingga diterbitkannya Surat Pengukuhan PKP dari Kantor Pelayanan Pajak terkait.",
    'jenis_pekerjaan_intro' => 'Dalam rangka mendukung operasional kegiatan usaha {client_name}, kami menyediakan berbagai jenis layanan perizinan yang sesuai dengan kebutuhan perusahaan. Jenis pekerjaan yang kami tawarkan mencakup izin-izin dasar yang diperlukan untuk memulai dan menjalankan kegiatan usaha sesuai dengan peraturan perundang-undangan yang berlaku di Indonesia. Berikut ini adalah daftar jenis administrasi dan perizinan yang dapat kami bantu dalam proses pengurusannya.',
    'prasyarat' => "<ol class=\"prasyarat\">\n<li>Perusahaan perlu menunjuk Staf yang kompeten untuk menjadi <i>counterpart</i> yang akan membantu tim kami mengakses data, informasi dan dokumen yang diperlukan selama penugasan. Pekerjaan lapangan dimulai setelah semua data and informasi secara lengkap.</li>\n<li>Di dalam melakukan pekerjaan, kami akan menggunakan media komunikasi, baik lisan, tertulis, dan secara elektronik seperti surat, memo dan email yang merupakan komunikasi yang diterima.</li>\n<li>Perusahaan menyatakan dan menjamin bahwa tidak melakukan perikatan lain dengan konsultan pajak lainnya untuk melakukan pekerjaan yang sesuai dengan ruang lingkup pekerjaan sebagaimana tertuang dalam surat ini.</li>\n<li>Manajemen membolehkan Bank Kreditor untuk berkomunikasi dan meminta informasi kepada kami.</li>\n</ol>",
    'penutup' => "Demikian surat penawaran ini kami sampaikan. Apabila terdapat hal-hal yang perlu diklarifikasi atau didiskusikan lebih lanjut, kami terbuka untuk melakukan pembahasan bersama. Apabila Perusahaan menyetujui penawaran ini, kami siap melanjutkan ke tahap kontrak penugasan sesuai dengan kesepakatan bersama.\n\nAtas kepercayaan dan kesempatan yang diberikan, kami sampaikan terima kasih."
]);

// 2. SURAT PENAWARAN JASA KEUANGAN DAN PERPAJAKAN
PenawaranTemplate::create([
    'code' => 'keuangan',
    'name' => 'SURAT PENAWARAN JASA KEUANGAN DAN PERPAJAKAN',
    'tujuan' => 'Sehubungan dengan rencana pengurusan legalitas dan kewajiban perpajakan perusahaan {client_name}, bersama surat ini kami mengajukan penawaran jasa keuangan dan perpajakan untuk membantu proses administrasi dan operasional usaha Bapak/Ibu. Menindaklanjuti permohonan tersebut, dengan ini kami sampaikan penawaran dengan pokok-pokok sebagai berikut:',
    'lingkup' => "Tujuan dari penugasan ini adalah untuk membantu dalam memastikan seluruh keperluan keuangan serta perpajakan yang dibutuhkan for operasional perusahaan dapat diperoleh dengan cara yang efektif, efisien, dan sesuai dengan ketentuan yang berlaku.\n\nDalam penugasan ini, kami membantu Perusahaan mengelola aspek keuangan and perpajakan secara terpadu, mulai dari pencatatan and laporan keuangan, perhitungan serta pelaporan pajak sesuai ketentuan.",
    'jenis_pekerjaan_intro' => 'Dalam rangka mendukung operasional kegiatan usaha, kami menyediakan berbagai jenis layanan perpajakan dan keuangan yang sesuai dengan kebutuhan. Perusahaan kami menyediakan solusi terintegrasi yang mencakup:',
    'prasyarat' => "<ol class=\"prasyarat\">\n<li>Perusahaan perlu menunjuk Staf yang kompeten untuk menjadi <i>counterpart</i> yang akan membantu tim kami mengakses data, informasi dan dokumen yang diperlukan selama penugasan. Pekerjaan lapangan dimulai setelah semua data and informasi secara lengkap.</li>\n<li>Di dalam melakukan pekerjaan, kami akan menggunakan media komunikasi, baik lisan, tertulis, dan secara elektronik seperti surat, memo dan email yang merupakan komunikasi yang diterima.</li>\n<li>Perusahaan menyatakan dan menjamin bahwa tidak melakukan perikatan lain dengan konsultan pajak lainnya untuk melakukan pekerjaan yang sesuai dengan ruang lingkup pekerjaan sebagaimana tertuang dalam surat ini.</li>\n<li>Manajemen membolehkan Bank Kreditor untuk berkomunikasi dan meminta informasi kepada kami.</li>\n</ol>",
    'penutup' => "Demikian surat penawaran ini kami sampaikan. Apabila terdapat hal-hal yang perlu diklarifikasi atau didiskusikan lebih lanjut, kami terbuka untuk melakukan pembahasan bersama. Apabila Perusahaan menyetujui penawaran ini, kami siap melanjutkan ke tahap kontrak penugasan sesuai dengan kesepakatan bersama.\n\nAtas kepercayaan dan kesempatan yang diberikan, kami sampaikan terima kasih."
]);

// 3. SURAT PENAWARAN JASA DIGITAL DAN DIGITAL MARKETING
PenawaranTemplate::create([
    'code' => 'digital',
    'name' => 'SURAT PENAWARAN JASA DIGITAL DAN DIGITAL MARKETING',
    'tujuan' => 'Sehubungan dengan kebutuhan pengembangan bisnis secara digital untuk {client_name}, bersama surat ini kami mengajukan penawaran jasa digital dan digital marketing guna membantu meningkatkan kehadiran online, jangkauan pasar, dan pertumbuhan bisnis Bapak/Ibu. Menindaklanjuti kebutuhan tersebut, dengan ini kami sampaikan penawaran dengan pokok-pokok sebagai berikut:',
    'lingkup' => "Tujuan dari penugasan ini adalah untuk membantu Perusahaan membangun and mengoptimalkan kehadiran digital yang kuat, meningkatkan brand awareness, memperluas jangkauan audiens, serta mengkonversi prospek menjadi pelanggan melalui strategi pemasaran digital yang terukur and berbasis data.\n\nDalam penugasan ini, kami membantu Perusahaan mengelola ekosistem digital secara menyeluruh, mulai dari pembuatan and pengelolaan konten, periklanan digital berbayar, optimasi mesin pencari (SEO), hingga analisis performa kampanye secara berkala.",
    'jenis_pekerjaan_intro' => 'Dalam rangka mendukung pertumbuhan bisnis digital Perusahaan, kami menyediakan berbagai layanan yang dapat disesuaikan dengan kebutuhan. Perusahaan kami menyediakan solusi terintegrasi yang mencakup:',
    'prasyarat' => "<ol class=\"prasyarat\">\n<li>Perusahaan perlu menunjuk Person in Charge (PIC) yang kompeten untuk berkoordinasi dengan tim kami, memberikan akses akun, informasi, dan dokumen yang diperlukan selama penugasan.</li>\n<li>Dalam melakukan pekerjaan, kami akan menggunakan media komunikasi secara lisan, tertulis, and elektronik seperti WhatsApp, email, and platform manajemen proyek yang disepakati bersama.</li>\n<li>Perusahaan menyatakan dan menjamin bahwa konten, aset visual, and informasi yang diberikan kepada kami adalah sah, akurat, and tidak melanggar hak pihak ketiga.</li>\n<li>Perusahaan memberikan akses yang diperlukan kepada kami, termasuk ke akun media sosial, platform iklan, Google Analytics, Google Search Console, and aset digital lainnya yang relevan.</li>\n<li>Persetujuan konten oleh Perusahaan wajib diberikan paling lambat 2 (dua) hari kerja sebelum jadwal publikasi agar tidak menghambat proses penayangan.</li>\n</ol>",
    'penutup' => "Demikian surat penawaran ini kami sampaikan. Apabila terdapat hal-hal yang perlu diklarifikasi atau didiskusikan lebih lanjut terkait strategi digital, ruang lingkup pekerjaan, maupun skema pembayaran, kami terbuka untuk melakukan pembahasan bersama. Apabila Perusahaan menyetujui penawaran ini, kami siap melanjutkan ke tahap kontrak penugasan sesuai dengan kesepakatan bersama.\n\nAtas kepercayaan dan kesempatan yang diberikan, kami sampaikan terima kasih."
]);

echo "Templates synced successfully.\n";
