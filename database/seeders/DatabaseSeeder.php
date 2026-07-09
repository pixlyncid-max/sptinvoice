<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Penawaran;
use App\Models\PenawaranItem;
use App\Models\RateCard;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // =============================================
        // Admin User
        // =============================================
        User::create([
            'name' => 'Ganesha Arta',
            'email' => 'admin@sptinvoice.com',
            'password' => Hash::make('password'),
        ]);

        // =============================================
        // Clients
        // =============================================
        $client1 = Client::create([
            'nama' => 'Ahmad Fauzan',
            'perusahaan' => 'PT Borneo Energi Mandiri',
            'email' => 'ahmad.fauzan@borneoenergy.co.id',
            'telepon' => '0812-5500-1234',
            'alamat' => "Jl. Mulawarman No. 45\nKelurahan Karang Asam\nKota Samarinda, Kalimantan Timur 75117",
        ]);

        $client2 = Client::create([
            'nama' => 'Siti Rahmawati',
            'perusahaan' => 'CV Mahakam Digital',
            'email' => 'siti.rahma@mahakamdigital.com',
            'telepon' => '0822-3344-5566',
            'alamat' => "Jl. P.Antasari No.12\nBalikpapan, Kalimantan Timur 76122",
        ]);

        $client3 = Client::create([
            'nama' => 'Budi Santoso',
            'perusahaan' => 'PT Kutai Timber Indonesia',
            'email' => 'budi.santoso@ktimber.co.id',
            'telepon' => '0853-2211-8899',
            'alamat' => "Jl. Jend. Sudirman No. 87\nTenggarong, Kutai Kartanegara\nKalimantan Timur 75511",
        ]);

        $client4 = Client::create([
            'nama' => 'Dewi Lestari',
            'perusahaan' => 'PT Indo Konstruksi Utama',
            'email' => 'dewi.lestari@indokonstruksi.co.id',
            'telepon' => '0811-5678-9012',
            'alamat' => "Komplek Ruko Sungai Kunjang\nBlok A No. 5, Samarinda\nKalimantan Timur 75243",
        ]);

        // =============================================
        // Rate Cards — Divisi Digital Marketing & IT Solution
        // =============================================
        // Content Production
        $dmItems = [
            ['sub' => 'Content Production', 'nama' => 'Instagram Feed Design', 'desk' => 'Desain konten feed single post', 'harga' => 100000, 'satuan' => 'Post'],
            ['sub' => 'Content Production', 'nama' => 'Carousel Content Design', 'desk' => 'Desain carousel hingga 5 slide', 'harga' => 300000, 'satuan' => 'Set'],
            ['sub' => 'Content Production', 'nama' => 'Reels / Short Video Editing', 'desk' => 'Editing video hingga 60 detik', 'harga' => 350000, 'satuan' => 'Video'],
            ['sub' => 'Content Production', 'nama' => 'Content Script Writing', 'desk' => 'Penulisan script konten video', 'harga' => 200000, 'satuan' => 'Script'],
            ['sub' => 'Content Production', 'nama' => 'Content Shooting (Half Day)', 'desk' => 'Produksi foto/video hingga 4 jam', 'harga' => 2500000, 'satuan' => 'Sesi'],
            ['sub' => 'Content Production', 'nama' => 'Content Shooting (Full Day)', 'desk' => 'Produksi foto/video hingga 8 jam', 'harga' => 4500000, 'satuan' => 'Sesi'],
            // Digital Advertising
            ['sub' => 'Digital Advertising', 'nama' => 'Meta Ads Setup', 'desk' => 'Campaign setup, pixel integration, audience targeting', 'harga' => 1500000, 'satuan' => 'Campaign'],
            ['sub' => 'Digital Advertising', 'nama' => 'Meta Ads Management', 'desk' => 'Campaign monitoring, optimization, A/B testing, reporting', 'harga' => 2500000, 'satuan' => 'Bulan'],
            ['sub' => 'Digital Advertising', 'nama' => 'Google Ads Setup', 'desk' => 'Campaign setup, keyword research', 'harga' => 2000000, 'satuan' => 'Campaign'],
            ['sub' => 'Digital Advertising', 'nama' => 'Google Ads Management', 'desk' => 'Campaign monitoring & optimization', 'harga' => 3000000, 'satuan' => 'Bulan'],
            // Website Development
            ['sub' => 'Website Development', 'nama' => 'Landing Page Website', 'desk' => '1 page responsive website', 'harga' => 4000000, 'satuan' => 'Paket'],
            ['sub' => 'Website Development', 'nama' => 'Company Profile Website', 'desk' => '5-7 halaman website', 'harga' => 8500000, 'satuan' => 'Paket'],
            ['sub' => 'Website Development', 'nama' => 'Corporate Website', 'desk' => '10+ halaman website', 'harga' => 15000000, 'satuan' => 'Paket'],
            ['sub' => 'Website Development', 'nama' => 'E-Commerce Website', 'desk' => 'Website dengan sistem transaksi online', 'harga' => 60000000, 'satuan' => 'Paket'],
            // Application Development
            ['sub' => 'Application Development', 'nama' => 'Mobile App UI/UX Design', 'desk' => 'Wireframe + design', 'harga' => 8000000, 'satuan' => 'Paket'],
            ['sub' => 'Application Development', 'nama' => 'Android App Development', 'desk' => 'Development aplikasi Android', 'harga' => 40000000, 'satuan' => 'Paket'],
            ['sub' => 'Application Development', 'nama' => 'iOS App Development', 'desk' => 'Development aplikasi iOS', 'harga' => 50000000, 'satuan' => 'Paket'],
            ['sub' => 'Application Development', 'nama' => 'Maintenance App', 'desk' => 'Update & bug fixing', 'harga' => 3000000, 'satuan' => 'Bulan'],
            // SEO Services
            ['sub' => 'SEO Services', 'nama' => 'SEO Audit', 'desk' => 'Website audit + report', 'harga' => 2500000, 'satuan' => 'Paket'],
            ['sub' => 'SEO Services', 'nama' => 'On-Page SEO Optimization', 'desk' => 'Keyword & content optimization', 'harga' => 3500000, 'satuan' => 'Paket'],
            ['sub' => 'SEO Services', 'nama' => 'Monthly SEO Service', 'desk' => 'SEO optimization & report', 'harga' => 6000000, 'satuan' => 'Bulan'],
            // Branding Services
            ['sub' => 'Branding Services', 'nama' => 'Logo Design', 'desk' => '3 konsep desain', 'harga' => 3000000, 'satuan' => 'Paket'],
            ['sub' => 'Branding Services', 'nama' => 'Brand Identity Design', 'desk' => 'Logo + brand guideline', 'harga' => 7000000, 'satuan' => 'Paket'],
            ['sub' => 'Branding Services', 'nama' => 'Company Profile Design', 'desk' => 'Desain company profile perusahaan', 'harga' => 3500000, 'satuan' => 'Paket'],
        ];

        foreach ($dmItems as $item) {
            RateCard::create([
                'divisi' => 'digital_marketing',
                'sub_kategori' => $item['sub'],
                'nama_paket' => $item['nama'],
                'deskripsi' => $item['desk'],
                'harga' => $item['harga'],
                'satuan' => $item['satuan'],
                'status' => 'aktif',
            ]);
        }

        // =============================================
        // Rate Cards — Divisi Keuangan & Perpajakan
        // =============================================
        $kpItems = [
            // Finance - Monthly
            ['sub' => 'Laporan Keuangan Bulanan', 'nama' => 'Financial Report – Nil (<IDR 50M)', 'desk' => 'Preparation of basic financial report with no or minimal transactions', 'harga' => 500000, 'satuan' => 'Bulan'],
            ['sub' => 'Laporan Keuangan Bulanan', 'nama' => 'Financial Report – Revenue IDR 50M – 500M', 'desk' => 'Preparation of PNL with basic transaction recording', 'harga' => 800000, 'satuan' => 'Bulan'],
            ['sub' => 'Laporan Keuangan Bulanan', 'nama' => 'Financial Report – Revenue IDR 500M – 2B', 'desk' => 'Preparation of PNL with transaction reconciliation', 'harga' => 1500000, 'satuan' => 'Bulan'],
            ['sub' => 'Laporan Keuangan Bulanan', 'nama' => 'Financial Report – Revenue IDR 2B – 4.8B', 'desk' => 'Preparation of PNL, balance sheet, and transaction reconciliation', 'harga' => 2500000, 'satuan' => 'Bulan'],
            ['sub' => 'Laporan Keuangan Bulanan', 'nama' => 'Financial Report – Revenue IDR 4.8B – 10B', 'desk' => 'Full financial statements with detailed reconciliation', 'harga' => 5000000, 'satuan' => 'Bulan'],
            // Finance - Quarterly
            ['sub' => 'Quarterly Financial Reporting', 'nama' => 'Quarterly Report – Nil (< IDR 50M)', 'desk' => 'Compilation of 3-month transactions and PNL statement', 'harga' => 1500000, 'satuan' => 'Kuartal'],
            ['sub' => 'Quarterly Financial Reporting', 'nama' => 'Quarterly Report – Revenue IDR 50M – 500M', 'desk' => 'Compilation of quarterly transactions, bank reconciliation, PNL and balance sheet', 'harga' => 2500000, 'satuan' => 'Kuartal'],
            ['sub' => 'Quarterly Financial Reporting', 'nama' => 'Quarterly Report – Revenue IDR 500M – 2B', 'desk' => 'Transaction reconciliation and complete financial statements', 'harga' => 4500000, 'satuan' => 'Kuartal'],
            ['sub' => 'Quarterly Financial Reporting', 'nama' => 'Quarterly Report – Revenue IDR 2B – 4.8B', 'desk' => 'Financial analysis and preparation of full financial statements', 'harga' => 7500000, 'satuan' => 'Kuartal'],
            ['sub' => 'Quarterly Financial Reporting', 'nama' => 'Quarterly Report – Revenue IDR 4.8B – 10B', 'desk' => 'Full financial statements with detailed reconciliation and review', 'harga' => 15000000, 'satuan' => 'Kuartal'],
            // Finance - Annual
            ['sub' => 'Annual Financial Reporting', 'nama' => 'Annual Report – Nil (< IDR 50M)', 'desk' => 'Preparation of basic annual financial statements', 'harga' => 2500000, 'satuan' => 'Tahun'],
            ['sub' => 'Annual Financial Reporting', 'nama' => 'Annual Report – Revenue IDR 50M – 500M', 'desk' => 'Preparation of full financial statements with reconciliation', 'harga' => 5000000, 'satuan' => 'Tahun'],
            ['sub' => 'Annual Financial Reporting', 'nama' => 'Annual Report – Revenue IDR 500M – 2B', 'desk' => 'Preparation of financial statements with financial analysis', 'harga' => 10000000, 'satuan' => 'Tahun'],
            ['sub' => 'Annual Financial Reporting', 'nama' => 'Annual Report – Revenue IDR 2B – 4.8B', 'desk' => 'Full financial statements with business performance analysis', 'harga' => 15000000, 'satuan' => 'Tahun'],
            ['sub' => 'Annual Financial Reporting', 'nama' => 'Annual Report – Revenue IDR 4.8B – 10B', 'desk' => 'Comprehensive financial statements, detailed analysis, and review', 'harga' => 20000000, 'satuan' => 'Tahun'],
            // Perpajakan - Administrasi
            ['sub' => 'Administrasi Perpajakan', 'nama' => 'Pengukuhan PKP', 'desk' => 'Konsultasi syarat PKP, persiapan dokumen, pengajuan PKP ke DJP, monitoring proses', 'harga' => 2500000, 'satuan' => 'Paket'],
            ['sub' => 'Administrasi Perpajakan', 'nama' => 'Pencabutan PKP', 'desk' => 'Analisa kelayakan pencabutan, penyusunan dokumen, pengajuan ke KPP', 'harga' => 2500000, 'satuan' => 'Paket'],
            ['sub' => 'Administrasi Perpajakan', 'nama' => 'Penerbitan E-Billing', 'desk' => 'Pembuatan ID billing pajak sesuai jenis pajak', 'harga' => 50000, 'satuan' => 'Billing'],
            ['sub' => 'Administrasi Perpajakan', 'nama' => 'Penerbitan Faktur Keluaran', 'desk' => 'Input dan pembuatan e-faktur pajak keluaran', 'harga' => 50000, 'satuan' => 'Faktur'],
            ['sub' => 'Administrasi Perpajakan', 'nama' => 'Penerbitan Surat Administrasi Perpajakan', 'desk' => 'Pembuatan surat klarifikasi, surat tanggapan DJP, surat permohonan administrasi', 'harga' => 500000, 'satuan' => 'Surat'],
            // NPWP
            ['sub' => 'NPWP', 'nama' => 'Pendaftaran NPWP OP', 'desk' => 'Pendaftaran NPWP orang pribadi hingga terbit', 'harga' => 150000, 'satuan' => 'Paket'],
            ['sub' => 'NPWP', 'nama' => 'Pendaftaran NPWP Badan', 'desk' => 'Pendaftaran NPWP perusahaan', 'harga' => 250000, 'satuan' => 'Paket'],
            ['sub' => 'NPWP', 'nama' => 'Perubahan Data OP', 'desk' => 'Perubahan alamat / KLU / data WP', 'harga' => 150000, 'satuan' => 'Paket'],
            ['sub' => 'NPWP', 'nama' => 'Perubahan Data Badan', 'desk' => 'Perubahan data perusahaan', 'harga' => 300000, 'satuan' => 'Paket'],
            ['sub' => 'NPWP', 'nama' => 'Non Efektif NPWP OP', 'desk' => 'Pengajuan status NE WP pribadi', 'harga' => 300000, 'satuan' => 'Paket'],
            ['sub' => 'NPWP', 'nama' => 'Non Efektif NPWP Badan', 'desk' => 'Pengajuan NE WP perusahaan', 'harga' => 600000, 'satuan' => 'Paket'],
            ['sub' => 'NPWP', 'nama' => 'Pengaktifan NE OP', 'desk' => 'Aktivasi NPWP OP', 'harga' => 300000, 'satuan' => 'Paket'],
            ['sub' => 'NPWP', 'nama' => 'Pengaktifan NE Badan', 'desk' => 'Aktivasi NPWP perusahaan', 'harga' => 550000, 'satuan' => 'Paket'],
            ['sub' => 'NPWP', 'nama' => 'Penghapusan NPWP OP', 'desk' => 'Pengajuan penghapusan NPWP', 'harga' => 400000, 'satuan' => 'Paket'],
            ['sub' => 'NPWP', 'nama' => 'Penghapusan NPWP Badan', 'desk' => 'Penghapusan NPWP perusahaan', 'harga' => 700000, 'satuan' => 'Paket'],
            // SPT Tahunan Badan
            ['sub' => 'SPT Tahunan Badan', 'nama' => 'SPT Tahunan Nihil <50jt', 'desk' => 'Penyusunan dan pelaporan SPT Tahunan tanpa aktivitas', 'harga' => 1000000, 'satuan' => 'Tahun'],
            ['sub' => 'SPT Tahunan Badan', 'nama' => 'SPT Tahunan omzet <50jt - 500jt', 'desk' => 'Penyusunan ringkasan laporan keuangan sederhana dan pelaporan SPT', 'harga' => 2000000, 'satuan' => 'Tahun'],
            ['sub' => 'SPT Tahunan Badan', 'nama' => 'SPT Tahunan omzet 500jt - 2M', 'desk' => 'Penyusunan ringkasan laporan keuangan dan pelaporan SPT', 'harga' => 4000000, 'satuan' => 'Tahun'],
            ['sub' => 'SPT Tahunan Badan', 'nama' => 'SPT Tahunan omzet 2M - 4.8M', 'desk' => 'Rekonsiliasi pajak, penyusunan laporan keuangan, dan pelaporan SPT', 'harga' => 6000000, 'satuan' => 'Tahun'],
            ['sub' => 'SPT Tahunan Badan', 'nama' => 'SPT Tahunan omzet 4.8M - 10M', 'desk' => 'Analisis pajak, laporan keuangan lengkap dan pelaporan SPT', 'harga' => 7500000, 'satuan' => 'Tahun'],
            // SPT Tahunan OP
            ['sub' => 'SPT Tahunan Orang Pribadi', 'nama' => 'SPT 1770SS (<60jt/th)', 'desk' => 'Pelaporan SPT karyawan', 'harga' => 300000, 'satuan' => 'Tahun'],
            ['sub' => 'SPT Tahunan Orang Pribadi', 'nama' => 'SPT 1770S (>60jt/th)', 'desk' => 'Pelaporan SPT karyawan', 'harga' => 1000000, 'satuan' => 'Tahun'],
            // Tax Package
            ['sub' => 'Tax Package', 'nama' => 'Tax Package - Starter', 'desk' => 'Lapor SPT Masa, pembuatan e-Billing, pembuatan faktur pajak (maks. 20 faktur/bulan)', 'harga' => 2500000, 'satuan' => 'Bulan'],
            ['sub' => 'Tax Package', 'nama' => 'Tax Package - Business', 'desk' => 'Lapor SPT Masa, rekonsiliasi pajak, pembuatan e-Billing, faktur pajak (maks. 50 faktur/bulan)', 'harga' => 4500000, 'satuan' => 'Bulan'],
            ['sub' => 'Tax Package', 'nama' => 'Tax Package - Professional', 'desk' => 'SPT Masa lengkap (PPh 21, 22, 23), laporan pajak bulanan, rekonsiliasi, e-billing, faktur pajak (maks. 100 faktur/bulan)', 'harga' => 6000000, 'satuan' => 'Bulan'],
            ['sub' => 'Tax Package', 'nama' => 'Tax Package - Corporate', 'desk' => 'Lapor SPT Masa, laporan pajak bulanan, rekonsiliasi, e-billing, faktur pajak unlimited, konsultasi pajak rutin', 'harga' => 8000000, 'satuan' => 'Bulan'],
            ['sub' => 'Tax Package', 'nama' => 'Tax Package - Full Tax', 'desk' => 'Pengelolaan pajak perusahaan, laporan pajak bulanan, SPT Masa, SPT Tahunan, konsultasi pajak unlimited, laporan keuangan per triwulan', 'harga' => 12000000, 'satuan' => 'Bulan'],
        ];

        foreach ($kpItems as $item) {
            RateCard::create([
                'divisi' => 'keuangan_perpajakan',
                'sub_kategori' => $item['sub'],
                'nama_paket' => $item['nama'],
                'deskripsi' => $item['desk'],
                'harga' => $item['harga'],
                'satuan' => $item['satuan'],
                'status' => 'aktif',
            ]);
        }

        // =============================================
        // Rate Cards — Divisi Perizinan & Legal
        // =============================================
        $pzItems = [
            // Layanan OSS
            ['sub' => 'Layanan OSS dan Perizinan Dasar', 'nama' => 'Registrasi OSS RBA UMK (<5M)', 'desk' => 'Pembuatan akun OSS, input data usaha, penentuan KBLI, penerbitan NIB, konsultasi legalitas dasar', 'harga' => 2000000, 'satuan' => 'Paket'],
            ['sub' => 'Layanan OSS dan Perizinan Dasar', 'nama' => 'Registrasi OSS RBA NON UMK (>5M)', 'desk' => 'Setup akun OSS, penyesuaian KBLI, input data badan usaha, penerbitan NIB + Sertifikat Standar', 'harga' => 4000000, 'satuan' => 'Paket'],
            ['sub' => 'Layanan OSS dan Perizinan Dasar', 'nama' => 'Perubahan Permodalan Dasar', 'desk' => 'Perubahan akta notaris, update data di AHU, update data OSS', 'harga' => 6000000, 'satuan' => 'Paket'],
            ['sub' => 'Layanan OSS dan Perizinan Dasar', 'nama' => 'Registrasi E-Catalog LKPP', 'desk' => 'Registrasi vendor, upload dokumen legalitas, setup produk katalog', 'harga' => 2000000, 'satuan' => 'Paket'],
            // HKI
            ['sub' => 'Hak Kekayaan Intelektual (HKI)', 'nama' => 'Registrasi Hak Cipta', 'desk' => 'Konsultasi jenis karya, pengajuan ke DJKI, monitoring proses', 'harga' => 3000000, 'satuan' => 'Paket'],
            ['sub' => 'Hak Kekayaan Intelektual (HKI)', 'nama' => 'Registrasi Merek', 'desk' => 'Pengecekan merek, pengajuan DJKI, monitoring hingga sertifikat', 'harga' => 4500000, 'satuan' => 'Paket'],
            ['sub' => 'Hak Kekayaan Intelektual (HKI)', 'nama' => 'Pengalihan Merek', 'desk' => 'Penyusunan dokumen pengalihan, pengajuan perubahan di DJKI', 'harga' => 4500000, 'satuan' => 'Paket'],
            ['sub' => 'Hak Kekayaan Intelektual (HKI)', 'nama' => 'Perpanjangan Merek', 'desk' => 'Pengajuan renewal, update dokumen DJKI', 'harga' => 4000000, 'satuan' => 'Paket'],
            // Pendirian Badan Usaha
            ['sub' => 'Layanan Pendirian Badan Usaha', 'nama' => 'Pendirian PT', 'desk' => 'Pengecekan nama PT, Akta Notaris, SK Kemenkumham, NPWP Perusahaan, NIB OSS, Rekening Perusahaan', 'harga' => 5000000, 'satuan' => 'Paket'],
            ['sub' => 'Layanan Pendirian Badan Usaha', 'nama' => 'Pendirian CV', 'desk' => 'Akta Notaris, Pendaftaran AHU, NPWP Badan, NIB OSS, Rekening Perusahaan', 'harga' => 3000000, 'satuan' => 'Paket'],
            ['sub' => 'Layanan Pendirian Badan Usaha', 'nama' => 'Pendirian Yayasan', 'desk' => 'Akta Notaris, SK Kemenkumham, NPWP Yayasan, NIB OSS', 'harga' => 7000000, 'satuan' => 'Paket'],
            ['sub' => 'Layanan Pendirian Badan Usaha', 'nama' => 'Pendirian PT Perorangan', 'desk' => 'Pendaftaran AHU, Sertifikat PT Perorangan, NPWP, NIB OSS', 'harga' => 1000000, 'satuan' => 'Paket'],
            ['sub' => 'Layanan Pendirian Badan Usaha', 'nama' => 'Pendirian Koperasi', 'desk' => 'Akta Notaris, Pengesahan Kemenkumham, NPWP, NIB OSS', 'harga' => 8000000, 'satuan' => 'Paket'],
            ['sub' => 'Layanan Pendirian Badan Usaha', 'nama' => 'Pendirian Perkumpulan Lokal', 'desk' => 'Pendirian perkumpulan tingkat lokal', 'harga' => 3000000, 'satuan' => 'Paket'],
            ['sub' => 'Layanan Pendirian Badan Usaha', 'nama' => 'Pendirian Perkumpulan Nasional (2-3 Bulan)', 'desk' => 'Pendirian perkumpulan tingkat nasional proses 2-3 bulan', 'harga' => 8000000, 'satuan' => 'Paket'],
            ['sub' => 'Layanan Pendirian Badan Usaha', 'nama' => 'Pendirian Perkumpulan Nasional (1 Bulan)', 'desk' => 'Pendirian perkumpulan tingkat nasional proses 1 bulan', 'harga' => 10000000, 'satuan' => 'Paket'],
        ];

        foreach ($pzItems as $item) {
            RateCard::create([
                'divisi' => 'perizinan',
                'sub_kategori' => $item['sub'],
                'nama_paket' => $item['nama'],
                'deskripsi' => $item['desk'],
                'harga' => $item['harga'],
                'satuan' => $item['satuan'],
                'status' => 'aktif',
            ]);
        }


        // =============================================
        // Invoices
        // =============================================
        $inv1 = Invoice::create([
            'nomor_invoice' => 'INV/2026/001',
            'client_id' => $client1->id,
            'tanggal_invoice' => '2026-04-01',
            'tanggal_jatuh_tempo' => '2026-05-01',
            'status' => 'dibayar',
            'catatan' => 'Pembayaran via transfer BNI.',
            'subtotal' => 23500000,
            'pajak_persen' => 11,
            'total' => 26085000,
        ]);

        InvoiceItem::create([
            'invoice_id' => $inv1->id,
            'deskripsi' => 'Paket Dokumen K3 Lengkap',
            'qty' => 1,
            'harga_satuan' => 15000000,
            'subtotal' => 15000000,
        ]);
        InvoiceItem::create([
            'invoice_id' => $inv1->id,
            'deskripsi' => 'Audit SMK3 Internal',
            'qty' => 1,
            'harga_satuan' => 8500000,
            'subtotal' => 8500000,
        ]);

        $inv2 = Invoice::create([
            'nomor_invoice' => 'INV/2026/002',
            'client_id' => $client2->id,
            'tanggal_invoice' => '2026-04-10',
            'tanggal_jatuh_tempo' => '2026-05-10',
            'status' => 'dikirim',
            'catatan' => null,
            'subtotal' => 27000000,
            'pajak_persen' => 11,
            'total' => 29970000,
        ]);

        InvoiceItem::create([
            'invoice_id' => $inv2->id,
            'deskripsi' => 'Social Media Management (3 Bulan)',
            'qty' => 3,
            'harga_satuan' => 5000000,
            'subtotal' => 15000000,
        ]);
        InvoiceItem::create([
            'invoice_id' => $inv2->id,
            'deskripsi' => 'Website Company Profile',
            'qty' => 1,
            'harga_satuan' => 12000000,
            'subtotal' => 12000000,
        ]);

        $inv3 = Invoice::create([
            'nomor_invoice' => 'INV/2026/003',
            'client_id' => $client4->id,
            'tanggal_invoice' => '2026-04-15',
            'tanggal_jatuh_tempo' => '2026-05-15',
            'status' => 'draft',
            'catatan' => 'Invoice supervisi K3 untuk proyek pembangunan gudang.',
            'subtotal' => 36000000,
            'pajak_persen' => 11,
            'total' => 39960000,
        ]);

        InvoiceItem::create([
            'invoice_id' => $inv3->id,
            'deskripsi' => 'Jasa Supervisi K3 Konstruksi (3 Bulan)',
            'qty' => 3,
            'harga_satuan' => 12000000,
            'subtotal' => 36000000,
        ]);

        // =============================================
        // Penawaran (Quotation) — Digital Marketing
        // =============================================
        $quo1 = Penawaran::create([
            'nomor_penawaran' => '001/SP-GAA/04/2026',
            'perihal' => 'Surat Penawaran Jasa Digital dan Digital Marketing',
            'client_id' => $client2->id,
            'tanggal' => '2026-04-05',
            'berlaku_hingga' => '2026-05-05',
            'status' => 'dikirim',
            'catatan' => null,
            'subtotal' => 25000000,
            'pajak_persen' => 0,
            'total' => 25000000,
        ]);

        // Jasa Pembuatan Aset Digital
        PenawaranItem::create([
            'penawaran_id' => $quo1->id,
            'kategori_layanan' => 'Jasa Pembuatan Aset Digital',
            'deskripsi' => 'Pembuatan Website Profil Perusahaan',
            'keterangan' => 'Landing page / company profile, responsif & SEO-ready',
            'qty' => 1, 'harga_satuan' => 0, 'subtotal' => 0,
        ]);
        PenawaranItem::create([
            'penawaran_id' => $quo1->id,
            'kategori_layanan' => 'Jasa Pembuatan Aset Digital',
            'deskripsi' => 'Desain Identitas Brand (Branding Kit)',
            'keterangan' => 'Logo, warna, tipografi, dan panduan merek',
            'qty' => 1, 'harga_satuan' => 0, 'subtotal' => 0,
        ]);
        PenawaranItem::create([
            'penawaran_id' => $quo1->id,
            'kategori_layanan' => 'Jasa Pembuatan Aset Digital',
            'deskripsi' => 'Pembuatan Konten Visual (Grafis & Video)',
            'keterangan' => 'Konten untuk media sosial, iklan, dan promosi',
            'qty' => 1, 'harga_satuan' => 0, 'subtotal' => 0,
        ]);
        PenawaranItem::create([
            'penawaran_id' => $quo1->id,
            'kategori_layanan' => 'Jasa Pembuatan Aset Digital',
            'deskripsi' => 'Setup & Optimasi Profil Media Sosial',
            'keterangan' => 'Instagram, Facebook, TikTok, LinkedIn, YouTube',
            'qty' => 1, 'harga_satuan' => 0, 'subtotal' => 0,
        ]);

        // Jasa Digital Marketing
        PenawaranItem::create([
            'penawaran_id' => $quo1->id,
            'kategori_layanan' => 'Jasa Digital Marketing',
            'deskripsi' => 'Manajemen Media Sosial (Social Media Management)',
            'keterangan' => 'Pembuatan & penjadwalan konten, engagement, monitoring',
            'qty' => 1, 'harga_satuan' => 0, 'subtotal' => 0,
        ]);
        PenawaranItem::create([
            'penawaran_id' => $quo1->id,
            'kategori_layanan' => 'Jasa Digital Marketing',
            'deskripsi' => 'Iklan Berbayar (Meta Ads / Google Ads)',
            'keterangan' => 'Setup, targeting, optimasi & laporan performa iklan',
            'qty' => 1, 'harga_satuan' => 0, 'subtotal' => 0,
        ]);
        PenawaranItem::create([
            'penawaran_id' => $quo1->id,
            'kategori_layanan' => 'Jasa Digital Marketing',
            'deskripsi' => 'Search Engine Optimization (SEO)',
            'keterangan' => 'Optimasi on-page, off-page, dan teknikal SEO website',
            'qty' => 1, 'harga_satuan' => 0, 'subtotal' => 0,
        ]);
        PenawaranItem::create([
            'penawaran_id' => $quo1->id,
            'kategori_layanan' => 'Jasa Digital Marketing',
            'deskripsi' => 'Email Marketing',
            'keterangan' => 'Pembuatan kampanye email, segmentasi, dan analisis',
            'qty' => 1, 'harga_satuan' => 0, 'subtotal' => 0,
        ]);
        PenawaranItem::create([
            'penawaran_id' => $quo1->id,
            'kategori_layanan' => 'Jasa Digital Marketing',
            'deskripsi' => 'Pembuatan Laporan & Analitik Digital',
            'keterangan' => 'Laporan bulanan performa seluruh kanal digital',
            'qty' => 1, 'harga_satuan' => 0, 'subtotal' => 0,
        ]);
        PenawaranItem::create([
            'penawaran_id' => $quo1->id,
            'kategori_layanan' => 'Jasa Digital Marketing',
            'deskripsi' => 'Strategi Konten & Perencanaan Kampanye',
            'keterangan' => 'Content plan, kalender editorial, dan strategi kampanye',
            'qty' => 1, 'harga_satuan' => 0, 'subtotal' => 0,
        ]);

        // Fee Pekerjaan
        PenawaranItem::create([
            'penawaran_id' => $quo1->id,
            'kategori_layanan' => 'Fee Pekerjaan',
            'deskripsi' => 'Pembuatan Website Profile Perusahaan',
            'keterangan' => null,
            'qty' => 1, 'harga_satuan' => 5000000, 'subtotal' => 5000000,
        ]);
        PenawaranItem::create([
            'penawaran_id' => $quo1->id,
            'kategori_layanan' => 'Fee Pekerjaan',
            'deskripsi' => 'Manajemen Media Sosial (per bulan)',
            'keterangan' => null,
            'qty' => 1, 'harga_satuan' => 5000000, 'subtotal' => 5000000,
        ]);
        PenawaranItem::create([
            'penawaran_id' => $quo1->id,
            'kategori_layanan' => 'Fee Pekerjaan',
            'deskripsi' => 'Ads Management (per bulan)',
            'keterangan' => null,
            'qty' => 1, 'harga_satuan' => 3000000, 'subtotal' => 3000000,
        ]);
        PenawaranItem::create([
            'penawaran_id' => $quo1->id,
            'kategori_layanan' => 'Fee Pekerjaan',
            'deskripsi' => 'Search Engine Optimization / SEO (per bulan)',
            'keterangan' => null,
            'qty' => 1, 'harga_satuan' => 4000000, 'subtotal' => 4000000,
        ]);
        PenawaranItem::create([
            'penawaran_id' => $quo1->id,
            'kategori_layanan' => 'Fee Pekerjaan',
            'deskripsi' => 'Pembuatan Konten Visual (per bulan)',
            'keterangan' => null,
            'qty' => 1, 'harga_satuan' => 5000000, 'subtotal' => 5000000,
        ]);
        PenawaranItem::create([
            'penawaran_id' => $quo1->id,
            'kategori_layanan' => 'Fee Pekerjaan',
            'deskripsi' => 'Laporan dan Analitik Digital (per bulan)',
            'keterangan' => null,
            'qty' => 1, 'harga_satuan' => 3000000, 'subtotal' => 3000000,
        ]);

        $quo2 = Penawaran::create([
            'nomor_penawaran' => '002/SP-GAA/04/2026',
            'perihal' => 'Surat Penawaran Jasa Konsultan HSE',
            'client_id' => $client1->id,
            'tanggal' => '2026-04-20',
            'berlaku_hingga' => '2026-05-04',
            'status' => 'disetujui',
            'catatan' => null,
            'subtotal' => 15000000,
            'pajak_persen' => 0,
            'total' => 15000000,
        ]);

        PenawaranItem::create([
            'penawaran_id' => $quo2->id,
            'kategori_layanan' => 'Fee Pekerjaan',
            'deskripsi' => 'Paket Dokumen K3 Lengkap (Revisi Tahunan)',
            'keterangan' => null,
            'qty' => 1,
            'harga_satuan' => 15000000,
            'subtotal' => 15000000,
        ]);
    }
}
