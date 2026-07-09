<?php

namespace Database\Seeders;

use App\Models\PenawaranTemplate;
use Illuminate\Database\Seeder;

class PerizinanTemplateSeeder extends Seeder
{
    public function run(): void
    {
        PenawaranTemplate::updateOrCreate(
            ['code' => 'perizinan'],
            [
                'name' => 'Template Perizinan & Perpajakan',
                'tujuan' => 'Sehubungan dengan rencana pengurusan legalitas dan kewajiban perpajakan perusahaan {client_name} ("PERUSAHAAN"), bersama surat ini kami mengajukan penawaran jasa perizinan dan perpajakan untuk membantu proses administrasi perusahaan agar sesuai dengan ketentuan peraturan perundang-undangan yang berlaku di Indonesia.',
                
                'lingkup' => 'Tujuan dari penugasan ini adalah untuk membantu dalam memastikan seluruh perizinan serta perpajakan yang dibutuhkan untuk operasional perusahaan dapat diperoleh dengan cara yang efektif, efisien, dan sesuai dengan ketentuan yang berlaku.

Kami berkomitmen untuk mendukung {client_name} dalam memenuhi persyaratan administratif dan regulasi yang diberlakukan oleh instansi pemerintah terkait. Dengan demikian, perusahaan dapat menjalankan aktivitas usahanya dengan lancar tanpa kendala hukum, serta meminimalkan risiko sanksi atau denda yang mungkin timbul akibat ketidaksesuaian atau keterlambatan dalam memperoleh izin.

Adapun ruang lingkup pekerjaan yang kami tawarkan meliputi pembuatan akun Coretax hingga terbitnya NPWP Badan, Akun OSS RBA hingga terbitnya Nomor Induk Berusaha (NIB) sebagai identitas resmi usaha, serta pengurusan proses pengukuhan sebagai Pengusaha Kena Pajak (PKP), termasuk pendampingan administrasi hingga diterbitkannya Surat Pengukuhan PKP dari Kantor Pelayanan Pajak terkait.',

                'jenis_pekerjaan_intro' => 'Dalam rangka mendukung operasional kegiatan usaha {client_name}, kami menyediakan berbagai jenis layanan perizinan yang sesuai dengan kebutuhan perusahaan. Jenis pekerjaan yang kami tawarkan mencakup izin-izin dasar yang diperlukan untuk memulai dan menjalankan kegiatan usaha sesuai dengan peraturan perundang-undangan yang berlaku di Indonesia. Berikut ini adalah daftar jenis administrasi dan perizinan yang dapat kami bantu dalam proses pengurusannya.',
                
                'prasyarat' => 'Agar pekerjaan di atas dapat berjalan efektif, maka diperlukan prasyarat sebagai berikut:
1. Perusahaan perlu menunjuk Staf yang kompeten untuk menjadi counterpart yang akan membantu tim kami mengakses data, informasi dan dokumen yang diperlukan selama penugasan. Pekerjaan lapangan dimulai setelah semua data dan informasi secara lengkap.
2. Di dalam melakukan pekerjaan, kami akan menggunakan media komunikasi, baik lisan, tertulis, dan secara elektronik seperti surat, memo dan email yang merupakan komunikasi yang diterima.
3. Perusahaan menyatakan dan menjamin bahwa tidak melakukan perikatan lain dengan konsultan pajak lainnya untuk melakukan pekerjaan yang sesuai dengan ruang lingkup pekerjaan sebagaimana tertuang dalam surat ini.
4. Manajemen membolehkan Bank Kreditor untuk berkomunikasi dan meminta informasi kepada kami.',

                'penutup' => 'Demikian surat penawaran ini kami sampaikan. Apabila terdapat hal-hal yang perlu diklarifikasi atau didiskusikan lebih lanjut, kami terbuka untuk melakukan pembahasan bersama. Apabila Perusahaan menyetujui penawaran ini, kami siap melanjutkan ke tahap kontrak penugasan sesuai dengan kesepakatan bersama.

Atas kepercayaan dan kesempatan yang diberikan, kami sampaikan terima kasih.',
            ]
        );
    }
}
