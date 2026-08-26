<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $body = <<<HTML
<p>Yth, PT {{company}},</p>

<p>Perkenalkan, kami dari <strong>Konsultan Borneo</strong>, perusahaan yang bergerak di bidang konsultasi perpajakan, keuangan, perizinan, dan digital.</p>

<p>Kami memahami bahwa pengelolaan perpajakan perusahaan sering kali menghadapi berbagai tantangan, mulai dari administrasi dan pelaporan pajak, perubahan regulasi, hingga penanganan kewajiban perpajakan yang membutuhkan perhatian khusus. Apabila tidak ditangani dengan tepat, hal tersebut berpotensi menimbulkan risiko bagi perusahaan di kemudian hari.</p>

<p>Beberapa hal yang dapat menjadi perhatian Bapak/Ibu antara lain:</p>
<ul>
    <li>Perubahan dan pembaruan regulasi perpajakan yang berdampak pada kegiatan usaha.</li>
    <li>Optimalisasi kepatuhan dan administrasi perpajakan perusahaan.</li>
    <li>Peningkatan pengawasan oleh Direktorat Jenderal Pajak.</li>
    <li>Pentingnya <em>tax planning</em> yang tepat untuk mendukung efisiensi dan keberlanjutan bisnis.</li>
</ul>

<p>Melalui email ini, kami ingin membuka kesempatan untuk berdiskusi mengenai kondisi dan kendala perpajakan yang saat ini dihadapi oleh perusahaan Bapak/Ibu.</p>

<p>Tim konsultan kami siap membantu memberikan pendampingan sesuai dengan kebutuhan perusahaan, mulai dari evaluasi permasalahan, konsultasi, hingga penyusunan solusi dan strategi perpajakan yang lebih tepat.</p>

<p>Apabila Bapak/Ibu berkenan untuk berdiskusi lebih lanjut, silakan menghubungi tim kami melalui:<br>
📞 <strong>0857-1153-3331</strong><br>
Instagram: <strong>@konsultanborneo</strong></p>

<p>Kami percaya bahwa setiap perusahaan memiliki kondisi dan kebutuhan yang berbeda. Oleh karena itu, kami terbuka untuk mendengarkan terlebih dahulu kendala yang Bapak/Ibu hadapi sebelum menentukan solusi yang paling sesuai.</p>

<p>Semoga kami dapat berkesempatan untuk berdiskusi dan membantu mendukung kelancaran pengelolaan perpajakan perusahaan Bapak/Ibu.</p>

<p>Salam hangat,<br>
<strong>Konsultan Borneo</strong></p>

<p style="font-size: 12px; color: #64748b; margin-top: 30px; border-top: 1px dashed #cbd5e1; padding-top: 10px;">
Jika Anda tidak ingin menerima informasi ini lagi, silakan <a href="{{unsubscribe_url}}" style="color: #94a3b8; text-decoration: underline;">berhenti berlangganan di sini</a>.
</p>
HTML;

        EmailTemplate::updateOrCreate(
            ['name' => 'Penawaran Konsultasi Perpajakan - Konsultan Borneo'],
            [
                'subject' => 'Penawaran Konsultasi Perpajakan untuk {{company}}',
                'body' => $body,
            ]
        );
    }
}
