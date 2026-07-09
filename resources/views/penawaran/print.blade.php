<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $penawaran->perihal ?? 'Surat Penawaran' }} - {{ $penawaran->nomor_penawaran }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
            /* Set to 0 to hide browser's default URL/Date header and footer */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #1a1a2e;
            background: #f3f4f6;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* Document meta */
        .meta-line {
            font-size: 11pt;
            margin-bottom: 2px;
        }

        .meta-label {
            display: inline-block;
            width: 60px;
        }

        /* Title */
        .doc-title {
            text-align: center;
            margin: 0 0 15px 0;
        }

        .doc-title h2 {
            font-size: 14pt;
            font-weight: bold;
            color: #1a1a2e;
            margin: 0;
        }

        .doc-title h3 {
            font-size: 14pt;
            font-weight: bold;
            color: #1a1a2e;
            margin: 0;
        }

        /* Content */
        .section-title {
            font-weight: bold;
            margin: 15px 0 8px 0;
            font-size: 11pt;
            border-bottom: 1.5px solid black;
            padding-bottom: 4px;
            page-break-after: avoid;
        }

        .section-letter {
            margin-left: 0;
        }

        .indent {
            margin-left: 0;
        }

        .indent2 {
            margin-left: 14px;
        }

        p {
            margin-bottom: 8px;
            text-align: justify;
        }

        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 15px 0;
            font-size: 10pt;
            page-break-inside: auto;
        }

        .data-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        .data-table th {
            background: linear-gradient(90deg, #1a3c7e, #2563eb);
            color: white;
            font-weight: bold;
            padding: 5px 6px;
            border: 1px solid #1e3a5f;
            text-align: center;
            font-size: 10pt;
        }

        .data-table td {
            padding: 4px 6px;
            border: 1px solid #94a3b8;
            vertical-align: top;
        }

        .data-table .total-row {
            background: linear-gradient(90deg, #1a3c7e, #2563eb);
            color: white;
            font-weight: bold;
        }

        /* Signature */
        .signature-block {
            text-align: center;
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .signature-block img {
            width: 150px;
            height: auto;
            display: block;
            margin: 4px auto;
        }

        /* Print */
        .no-print {
            padding: 12px 20px;
            background: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 16px;
        }

        /* Screen preview container */
        .preview-container {
            max-width: 210mm;
            margin: 0 auto;
            background: white;
            padding: 0 20mm 15mm 20mm;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        @media print {
            body {
                background: white;
            }

            /* Apply left and right margins for print, top/bottom are handled by thead/tfoot padding */
            .preview-container {
                box-shadow: none;
                max-width: none;
                margin: 0;
                padding: 0 20mm;
            }

            .no-print {
                display: none;
            }

            .print-footer {
                display: block !important;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                padding: 0 20mm 10mm 20mm;
                /* left/right 20mm matches container, bottom 10mm spacing */
                background: white;
            }

            .screen-footer {
                display: none !important;
            }
        }

        .print-footer {
            display: none;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            font-size: 10pt;
            color: #64748b;
            border-top: 1px solid #cbd5e1;
            padding-top: 4px;
        }

        ol.prasyarat {
            margin-left: 14px;
            padding-left: 0;
        }

        ol.prasyarat li {
            margin-bottom: 2px;
            text-align: justify;
        }

        .template-prasyarat ol {
            margin-left: 14px;
            padding-left: 0;
        }

        .template-prasyarat li {
            margin-bottom: 2px;
            text-align: justify;
        }
    </style>
</head>

<body>
    <div class="no-print">
        <div><a href="{{ url()->previous() }}" style="color: #1a3c7e; text-decoration: none;">← Kembali</a></div>
        <button onclick="window.print()"
            style="padding: 8px 16px; background: #1a3c7e; color: white; border: none; border-radius: 4px; cursor: pointer;">🖨️
            Cetak Dokumen</button>
    </div>

    @php
        // Check quotation type and get template
        $isPerizinan = str_contains(strtolower($penawaran->perihal), 'perizinan');
        $isKeuangan = !$isPerizinan && (str_contains(strtolower($penawaran->perihal), 'keuangan') || str_contains(strtolower($penawaran->perihal), 'perpajakan'));

        $templateCode = $isPerizinan ? 'perizinan' : ($isKeuangan ? 'keuangan' : 'digital');
        $template = \App\Models\PenawaranTemplate::where('code', $templateCode)->first();

        // Group items by kategori_layanan
        $jenisPekerjaanManual = $penawaran->items->where('kategori_layanan', 'Jenis Pekerjaan')->values();
        $feePekerjaan = $penawaran->items->where('kategori_layanan', 'Fee Pekerjaan')->values();
        $dataDiperlukanManual = $penawaran->items->where('kategori_layanan', 'Data Yang Diperlukan')->values();

        // Fallback grouping (legacy support)
        $asetDigital = $penawaran->items->where('kategori_layanan', 'Jasa Pembuatan Aset Digital')->values();
        $digitalMarketing = $penawaran->items->where('kategori_layanan', 'Jasa Digital Marketing')->values();
        $jasaPerpajakan = $penawaran->items->where('kategori_layanan', 'Jasa Perpajakan')->values();
        $jasaPerizinan = $penawaran->items->where('kategori_layanan', 'Jasa Perizinan')->values();

        // Indonesian month names
        $bulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tglFormatted = $penawaran->tanggal->day . ' ' . $bulanIndo[$penawaran->tanggal->month] . ' ' . $penawaran->tanggal->year;
        $footerTitle = 'Surat Penawaran ' . ($penawaran->client->perusahaan ?? $penawaran->client->nama) . ' ' . $penawaran->tanggal->year;
    @endphp

    <div class="preview-container">

        <!-- Pinned Footer for Print -->
        <div class="print-footer">
            <div class="footer-content">
                <span>{{ $footerTitle }}</span>
            </div>
        </div>

        <table style="width: 100%; border: none;">
            <thead style="display: table-header-group;">
                <tr>
                    <td style="border: none; padding-top: 0; padding-bottom: 15px;">
                        <div style="text-align: center; margin: 0 -20mm;">
                            <img src="{{ asset('storage/image.png') }}" alt="Ganesha Arta Adiwangsa"
                                style="width: 100%; max-width: none; display: block; margin: 0 auto;">
                        </div>
                    </td>
                </tr>
            </thead>
            <tfoot style="display: table-footer-group;">
                <tr>
                    <!-- Spacer in tfoot to ensure content doesn't overlap the fixed footer -->
                    <td style="border: none; padding-top: 15px; height: 15mm;"></td>
                </tr>
            </tfoot>
            <tbody>
                <tr>
                    <td style="border: none; padding: 0;">
                        <div style="text-align: right; margin-bottom: 20px; font-size: 11pt;">
                            Samarinda, {{ $tglFormatted }}
                        </div>

                        <table style="width: 100%; font-size: 11pt; margin-bottom: 20px; border: none;">
                            <tr>
                                <td style="width: 70px; vertical-align: top; padding: 0; border: none;">Nomor</td>
                                <td style="width: 15px; vertical-align: top; padding: 0; border: none;">:</td>
                                <td style="vertical-align: top; padding: 0; border: none;">
                                    {{ $penawaran->nomor_penawaran }}</td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding: 0; border: none;">Perihal</td>
                                <td style="vertical-align: top; padding: 0; border: none;">:</td>
                                <td style="vertical-align: top; padding: 0; border: none;">Surat Penawaran</td>
                            </tr>
                        </table>

                        <div style="font-size: 11pt; margin-bottom: 35px;">
                            Kepada,<br>
                            @if($penawaran->client->perusahaan)
                                <strong>{{ strtoupper($penawaran->client->perusahaan) }}</strong>
                            @else
                                <strong>{{ strtoupper($penawaran->client->nama) }}</strong>
                            @endif
                        </div>

                        <div class="doc-title">
                            <h2>SURAT PENAWARAN JASA</h2>
                            @if($isPerizinan)
                                <h3>PERIZINAN DAN PERPAJAKAN</h3>
                            @elseif($isKeuangan)
                                <h3>KEUANGAN DAN PERPAJAKAN</h3>
                            @else
                                <h3>DIGITAL DAN DIGITAL MARKETING</h3>
                            @endif
                        </div>

                        @php
                            $clientName = $penawaran->client->perusahaan ?? $penawaran->client->nama;
                            $clientNameBold = '<strong>' . e($clientName) . '</strong>';
                        @endphp

                        <p>Dengan Hormat,</p>
                        @if($template && $template->tujuan)
                            <p>{!! nl2br(str_replace('{client_name}', $clientNameBold, $template->tujuan)) !!}</p>
                        @else
                            @if($isPerizinan)
                                <p>Sehubungan dengan rencana pengurusan legalitas dan kewajiban perpajakan perusahaan
                                    {!! $clientNameBold !!} ("PERUSAHAAN"), bersama surat ini kami mengajukan penawaran jasa
                                    perizinan dan perpajakan untuk membantu proses administrasi perusahaan agar sesuai dengan
                                    ketentuan peraturan perundang-undangan yang berlaku di Indonesia.</p>
                            @elseif($isKeuangan)
                                <p>Sehubungan dengan rencana pengurusan legalitas dan kewajiban perpajakan perusahaan
                                    {!! $clientNameBold !!}, bersama surat ini kami mengajukan penawaran jasa keuangan dan
                                    perpajakan untuk membantu proses administrasi dan operasional usaha Bapak/Ibu.
                                    Menindaklanjuti permohonan tersebut, dengan ini kami sampaikan penawaran dengan pokok-pokok
                                    sebagai berikut:</p>
                            @else
                                <p>Sehubungan dengan kebutuhan pengembangan bisnis secara digital untuk {!! $clientNameBold !!},
                                    bersama surat ini kami mengajukan penawaran jasa digital dan digital marketing guna membantu
                                    meningkatkan kehadiran online, jangkauan pasar, dan pertumbuhan bisnis Bapak/Ibu.
                                    Menindaklanjuti kebutuhan tersebut, dengan ini kami sampaikan penawaran dengan pokok-pokok
                                    sebagai berikut:</p>
                            @endif
                        @endif

                        <div class="section-title section-letter">A. &nbsp; TUJUAN DAN LINGKUP PENUGASAN</div>
                        <div class="indent">
                            @if($template && $template->lingkup)
                                <p>{!! nl2br(str_replace('{client_name}', $clientNameBold, $template->lingkup)) !!}</p>
                            @else
                                @if($isPerizinan)
                                    <p>Tujuan dari penugasan ini adalah untuk membantu dalam memastikan seluruh perizinan serta
                                        perpajakan yang dibutuhkan for operasional perusahaan dapat diperoleh dengan cara yang
                                        efektif, efisien, dan sesuai dengan ketentuan yang berlaku.</p>
                                    <p>Kami berkomitmen untuk mendukung {!! $clientNameBold !!} dalam memenuhi persyaratan
                                        administratif dan regulasi yang diberlakukan by instansi pemerintah terkait. Dengan
                                        demikian, perusahaan dapat menjalankan aktivitas usahanya dengan lancar tanpa kendala
                                        hukum, serta meminimalkan risiko sanksi atau denda yang mungkin timbul akibat
                                        ketidaksesuaian atau keterlambatan dalam memperoleh izin.</p>
                                    <p>Adapun ruang lingkup pekerjaan yang kami tawarkan meliputi pembuatan akun Coretax hingga
                                        terbitnya NPWP Badan, Akun OSS RBA hingga terbitnya Nomor Induk Berusaha (NIB) sebagai
                                        identitas resmi usaha, serta pengurusan proses pengukuhan sebagai Pengusaha Kena Pajak
                                        (PKP), termasuk pendampingan administrasi hingga diterbitkannya Surat Pengukuhan PKP
                                        dari Kantor Pelayanan Pajak terkait.</p>
                                @elseif($isKeuangan)
                                    <p>Tujuan dari penugasan ini adalah untuk membantu dalam memastikan seluruh keperluan
                                        keuangan serta perpajakan yang dibutuhkan for operasional perusahaan dapat diperoleh
                                        dengan cara yang efektif, efisien, dan sesuai dengan ketentuan yang berlaku.</p>
                                    <p>Dalam penugasan ini, kami membantu Perusahaan mengelola aspek keuangan and perpajakan
                                        secara terpadu, mulai dari pencatatan and laporan keuangan, perhitungan serta pelaporan
                                        pajak sesuai ketentuan.</p>
                                @else
                                    <p>Tujuan dari penugasan ini adalah untuk membantu Perusahaan membangun and mengoptimalkan
                                        kehadiran digital yang kuat, meningkatkan brand awareness, memperluas jangkauan audiens,
                                        serta mengkonversi prospek menjadi pelanggan melalui strategi pemasaran digital yang
                                        terukur and berbasis data.</p>
                                    <p>Dalam penugasan ini, kami membantu Perusahaan mengelola ekosistem digital secara
                                        menyeluruh, mulai dari pembuatan and pengelolaan konten, periklanan digital berbayar,
                                        optimasi mesin pencari (SEO), hingga analisis performa kampanye secara berkala.</p>
                                @endif
                            @endif
                        </div>

                        <div class="section-title section-letter">B. &nbsp; JENIS PEKERJAAN</div>
                        <div class="indent">
                            @if($template && $template->jenis_pekerjaan_intro)
                                <p>{!! nl2br(str_replace('{client_name}', $clientNameBold, $template->jenis_pekerjaan_intro)) !!}
                                </p>
                            @else
                                @if($isPerizinan)
                                    <p>Dalam rangka mendukung operasional kegiatan usaha {!! $clientNameBold !!}, kami
                                        menyediakan berbagai jenis layanan perizinan yang sesuai dengan kebutuhan perusahaan.
                                        Jenis pekerjaan yang kami tawarkan mencakup izin-izin dasar yang diperlukan untuk
                                        memulai dan menjalankan kegiatan usaha sesuai dengan peraturan perundang-undangan yang
                                        berlaku di Indonesia. Berikut ini adalah daftar jenis administrasi dan perizinan yang
                                        dapat kami bantu dalam proses pengurusannya.</p>
                                @elseif($isKeuangan)
                                    <p>Dalam rangka mendukung operasional kegiatan usaha, kami menyediakan berbagai jenis
                                        layanan perpajakan dan keuangan yang sesuai dengan kebutuhan. Perusahaan kami
                                        menyediakan solusi terintegrasi yang mencakup:</p>
                                @else
                                    <p>Dalam rangka mendukung pertumbuhan bisnis digital Perusahaan, kami menyediakan berbagai
                                        layanan yang dapat disesuaikan dengan kebutuhan. Perusahaan kami menyediakan solusi
                                        terintegrasi yang mencakup:</p>
                                @endif
                            @endif
                        </div>

                        @if($jenisPekerjaanManual->count() > 0)
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;">NO</th>
                                        <th>JENIS PEKERJAAN</th>
                                        <th>KETERANGAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jenisPekerjaanManual as $i => $item)
                                        <tr>
                                            <td style="text-align: center;">{{ $i + 1 }}.</td>
                                            <td>{{ $item->deskripsi }}</td>
                                            <td>{{ $item->keterangan }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        @if($jasaPerizinan->count() > 0)
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;">NO</th>
                                        <th>JENIS PEKERJAAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jasaPerizinan as $i => $item)
                                        <tr>
                                            <td style="text-align: center;">{{ $i + 1 }}.</td>
                                            <td>{{ $item->deskripsi }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        @if($jasaPerpajakan->count() > 0)
                            <div class="indent"><strong>JASA PERPAJAKAN</strong></div>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;">NO</th>
                                        <th>JENIS PEKERJAAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jasaPerpajakan as $i => $item)
                                        <tr>
                                            <td style="text-align: center;">{{ $i + 1 }}.</td>
                                            <td>{{ $item->deskripsi }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        @if($asetDigital->count() > 0)
                            <div class="indent"><strong>1. JASA PEMBUATAN ASET DIGITAL</strong></div>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;">NO</th>
                                        <th>JENIS PEKERJAAN</th>
                                        <th>KETERANGAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($asetDigital as $i => $item)
                                        <tr>
                                            <td style="text-align: center;">{{ $i + 1 }}.</td>
                                            <td>{{ $item->deskripsi }}</td>
                                            <td>{{ $item->keterangan }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        @if($digitalMarketing->count() > 0)
                            <div class="indent"><strong>{{ $asetDigital->count() > 0 ? '2.' : '' }} JASA DIGITAL
                                    MARKETING</strong></div>
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;">NO</th>
                                        <th>JENIS PEKERJAAN</th>
                                        <th>KETERANGAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($digitalMarketing as $i => $item)
                                        <tr>
                                            <td style="text-align: center;">{{ $i + 1 }}.</td>
                                            <td>{{ $item->deskripsi }}</td>
                                            <td>{{ $item->keterangan }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        <div class="section-title section-letter">C. &nbsp; FEE PEKERJAAN</div>
                        <div class="indent">
                            <p>Berdasarkan pemahaman kami tentang
                                {{ $isPerizinan ? 'operasi' : ($isKeuangan ? 'kebutuhan operasional' : 'kebutuhan digital') }}
                                perusahaan dan perencanaan yang kami susun atas pekerjaan ini adalah sebagai berikut:
                            </p>
                        </div>

                        @if($feePekerjaan->count() > 0)
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;">NO</th>
                                        <th>KETERANGAN</th>
                                        <th style="width: 160px;">HARGA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($feePekerjaan as $i => $item)
                                        <tr>
                                            <td style="text-align: center;">{{ $i + 1 }}.</td>
                                            <td>{{ $item->deskripsi }}</td>
                                            <td style="text-align: right;">Rp
                                                {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach

                                    @if($penawaran->diskon > 0)
                                        <tr class="total-row">
                                            <td colspan="2" style="text-align: right; border-color: #1e3a5f;">TOTAL</td>
                                            <td style="text-align: right; border-color: #1e3a5f;">Rp
                                                {{ number_format($penawaran->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr class="total-row">
                                            <td colspan="2" style="text-align: right; border-color: #1e3a5f;">DISKON</td>
                                            <td style="text-align: right; border-color: #1e3a5f;">Rp
                                                {{ number_format($penawaran->diskon, 0, ',', '.') }}</td>
                                        </tr>
                                    @endif
                                    <tr class="total-row">
                                        <td colspan="2" style="text-align: right; border-color: #1e3a5f;">TOTAL PENAWARAN
                                        </td>
                                        <td style="text-align: right; border-color: #1e3a5f;">Rp
                                            {{ number_format($penawaran->total, 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @endif

                        <div class="indent">
                            <p>Untuk total biaya jasa adalah sebesar <strong>Rp.
                                    {{ number_format($penawaran->total, 0, ',', '.') }},-</strong>
                                (<strong><em>{{ terbilang($penawaran->total) }}</em></strong>)</p>
                            @if($isPerizinan)
                                <p>Biaya di atas ditetapkan dengan asumsi bahwa seluruh data dan dokumen yang diperlukan
                                    telah tersedia dan dapat diberikan secara lengkap oleh pihak Perusahaan. Pembayaran atas
                                    fee pekerjaan akan kami ajukan melalui invoice secara bertahap sesuai dengan progres
                                    pekerjaan.</p>
                            @elseif($isKeuangan)
                                <p>Fee tersebut ditentukan dengan dasar bahwa seluruh data yang dibutuhkan untuk keperluan
                                    jasa kami telah siap dan tersedia untuk dijalankan. Standar rate jasa untuk penugasan
                                    ini bervariasi sesuai dengan tingkat tanggung jawab dan pengalaman.</p>
                                <p>Pembayaran atas fee pekerjaan yang kami lakukan akan kami ajukan dengan invoice secara
                                    bertahap.</p>
                            @else
                                <p>Fee tersebut ditentukan berdasarkan lingkup pekerjaan yang telah disepakati. Biaya setup
                                    awal bersifat one-time payment, sedangkan biaya pengelolaan bulanan (retainer)
                                    ditagihkan setiap bulan di awal periode. Standar rate jasa untuk penugasan ini
                                    bervariasi sesuai dengan kompleksitas pekerjaan dan platform yang digunakan.</p>
                                <p>Pembayaran atas fee pekerjaan akan kami ajukan melalui invoice. Khusus untuk layanan
                                    berbayar (Meta Ads, Google Ads), biaya iklan (ad spend) dibayarkan langsung ke platform
                                    terkait dan tidak termasuk dalam fee jasa di atas.</p>
                            @endif
                        </div>

                        <div class="section-title section-letter">D. &nbsp; PRASYARAT</div>
                        <div class="indent">
                            <p>Agar pekerjaan di atas dapat berjalan efektif, maka diperlukan prasyarat sebagai berikut
                            </p>
                            @if($template && $template->prasyarat)
                                <div class="template-prasyarat">
                                    {!! str_replace('{client_name}', $clientNameBold, $template->prasyarat) !!}</div>
                            @else
                                @if($isPerizinan || $isKeuangan)
                                    <ol class="prasyarat">
                                        <li>Perusahaan perlu menunjuk Staf yang kompeten untuk menjadi <i>counterpart</i> yang
                                            akan membantu tim kami mengakses data, informasi dan dokumen yang diperlukan selama
                                            penugasan. Pekerjaan lapangan dimulai setelah semua data and informasi secara
                                            lengkap.</li>
                                        <li>Di dalam melakukan pekerjaan, kami akan menggunakan media komunikasi, baik lisan,
                                            tertulis, dan secara elektronik seperti surat, memo dan email yang merupakan
                                            komunikasi yang diterima.</li>
                                        <li>Perusahaan menyatakan dan menjamin bahwa tidak melakukan perikatan lain dengan
                                            konsultan pajak lainnya untuk melakukan pekerjaan yang sesuai dengan ruang lingkup
                                            pekerjaan sebagaimana tertuang dalam surat ini.</li>
                                        <li>Manajemen membolehkan Bank Kreditor untuk berkomunikasi dan meminta informasi kepada
                                            kami.</li>
                                    </ol>
                                @else
                                    <ol class="prasyarat">
                                        <li>Perusahaan perlu menunjuk Person in Charge (PIC) yang kompeten untuk berkoordinasi
                                            dengan tim kami, memberikan akses akun, informasi, dan dokumen yang diperlukan
                                            selama penugasan.</li>
                                        <li>Dalam melakukan pekerjaan, kami akan menggunakan media komunikasi secara lisan,
                                            tertulis, and elektronik seperti WhatsApp, email, and platform manajemen proyek yang
                                            disepakati bersama.</li>
                                        <li>Perusahaan menyatakan dan menjamin bahwa konten, aset visual, and informasi yang
                                            diberikan kepada kami adalah sah, akurat, and tidak melanggar hak pihak ketiga.</li>
                                        <li>Perusahaan memberikan akses yang diperlukan kepada kami, termasuk ke akun media
                                            sosial, platform iklan, Google Analytics, Google Search Console, and aset digital
                                            lainnya yang relevan.</li>
                                        <li>Persetujuan konten oleh Perusahaan wajib diberikan paling lambat 2 (dua) hari kerja
                                            sebelum jadwal publikasi agar tidak menghambat proses penayangan.</li>
                                    </ol>
                                @endif
                            @endif
                        </div>

                        <div class="indent" style="margin-top: 10px;">
                            <p>Adapun persyaratan dokumen yang diperlukan dalam pengerjaan jasa adalah sebagai berikut:
                            </p>
                        </div>

                        @if($dataDiperlukanManual->count() > 0)
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;">NO.</th>
                                        <th style="width: 33%;">DATA YANG DIPERLUKAN</th>
                                        <th>KETERANGAN / DESKRIPSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dataDiperlukanManual as $i => $item)
                                        <tr>
                                            <td style="text-align: center;">{{ $i + 1 }}.</td>
                                            <td>{{ $item->deskripsi }}</td>
                                            <td>{{ $item->keterangan }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @elseif($isPerizinan)
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;">NO.</th>
                                        <th>DATA YANG DIPERLUKAN</th>
                                        <th>KETERANGAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align:center;">1.</td>
                                        <td>Data Legal Perusahaan</td>
                                        <td>Akta Perusahaan, SK</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">2.</td>
                                        <td>Dokumen Perpajakan</td>
                                        <td>NPWP dan MoU dengan Pihak Lain</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">3.</td>
                                        <td>Laporan Keuangan Tahunan</td>
                                        <td>Sesuai dengan Tahun Pajak yang dilaporkan</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">4.</td>
                                        <td>Akun email aktif</td>
                                        <td>Email dan Password</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">5.</td>
                                        <td>Nomor Handphone Aktif</td>
                                        <td>Nomor Pribadi dan Perusahaan</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">6.</td>
                                        <td>Stempel Perusahaan</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">7.</td>
                                        <td>Rekening Koran Perusahaan</td>
                                        <td>1 Tahun Terakhir</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">8.</td>
                                        <td>Data Transaksi Perpajakan</td>
                                        <td>Bukti potong, Invoice, dan Faktur Keluaran-Masukan</td>
                                    </tr>
                                </tbody>
                            </table>
                        @elseif($isKeuangan)
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;">NO.</th>
                                        <th>DATA YANG DIPERLUKAN</th>
                                        <th>KETERANGAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align:center;">1.</td>
                                        <td>Data Legal Perusahaan</td>
                                        <td>Akta Perusahaan, SK</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">2.</td>
                                        <td>Dokumen Perpajakan</td>
                                        <td>NPWP dan MoU dengan Pihak Lain</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">3.</td>
                                        <td>Laporan Keuangan Tahunan</td>
                                        <td>Sesuai dengan Tahun Pajak yang dilaporkan</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">4.</td>
                                        <td>Akun email aktif</td>
                                        <td>Email dan Password</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">5.</td>
                                        <td>Nomor Handphone Aktif</td>
                                        <td>Nomor Pribadi dan Perusahaan</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">6.</td>
                                        <td>Stempel Perusahaan</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">7.</td>
                                        <td>Rekening Koran Perusahaan</td>
                                        <td>1 Tahun Terakhir</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">8.</td>
                                        <td>Data Transaksi Perpajakan</td>
                                        <td>Bukti potong, Invoice, dan Faktur Keluaran-Masukan</td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th style="width: 30px;">NO.</th>
                                        <th>DATA YANG DIPERLUKAN</th>
                                        <th>KETERANGAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="text-align:center;">1.</td>
                                        <td>Data Legal Perusahaan</td>
                                        <td>Nama, alamat, bidang usaha, logo resmi</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">2.</td>
                                        <td>Akun Sosial Media</td>
                                        <td>Username, password, atau akses admin/editor</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">3.</td>
                                        <td>Akun Iklan (Meta Business/Google)</td>
                                        <td>Akses sebagai admin atau pengiklan</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">4.</td>
                                        <td>Akun Google Analytics</td>
                                        <td>Akses view/edit untuk tracking performa</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">5.</td>
                                        <td>Akun Hosting dan Domain (jika ada)</td>
                                        <td>cPanel / panel hosting dan informasi domain</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">6.</td>
                                        <td>Aset Visual & Konten Brand</td>
                                        <td>Foto produk, video, logo dalam format digital</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">7.</td>
                                        <td>Email Aktif Perusahaan</td>
                                        <td>Email bisnis beserta password yang bisa diakses</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;">8.</td>
                                        <td>Nomor WhatsApp/ Handphone Aktif</td>
                                        <td>Untuk koordinasi dan verifikasi OTP</td>
                                    </tr>
                                </tbody>
                            </table>
                        @endif

                        <div class="section-title section-letter">E. &nbsp; PENUTUP</div>
                        <div class="indent">
                            @if($template && $template->penutup)
                                <p>{!! nl2br(str_replace('{client_name}', $clientNameBold, $template->penutup)) !!}</p>
                            @else
                                @if($isPerizinan || $isKeuangan)
                                    <p>Demikian surat penawaran ini kami sampaikan. Apabila terdapat hal-hal yang perlu
                                        diklarifikasi atau didiskusikan lebih lanjut, kami terbuka untuk melakukan pembahasan
                                        bersama. Apabila Perusahaan menyetujui penawaran ini, kami siap melanjutkan ke tahap
                                        kontrak penugasan sesuai dengan kesepakatan bersama.</p>
                                @else
                                    <p>Demikian surat penawaran ini kami sampaikan. Apabila terdapat hal-hal yang perlu
                                        diklarifikasi atau didiskusikan lebih lanjut terkait strategi digital, ruang lingkup
                                        pekerjaan, maupun skema pembayaran, kami terbuka untuk melakukan pembahasan bersama.
                                        Apabila Perusahaan menyetujui penawaran ini, kami siap melanjutkan ke tahap kontrak
                                        penugasan sesuai dengan kesepakatan bersama.</p>
                                @endif
                                <p>Atas kepercayaan dan kesempatan yang diberikan, kami sampaikan terima kasih.</p>
                            @endif
                        </div>

                        <div
                            style="display: flex; justify-content: flex-end; margin-top: 30px; margin-right: 40px; page-break-inside: avoid;">
                            <div style="text-align: center;">
                                <div style="font-size: 11pt;">Hormat Kami,</div>
                                <div style="font-weight: bold; font-size: 11pt;">PT. GANESHA ARTA ADIWANGSA</div>
                                <div style="margin: 4px auto;">
                                    @if($penawaran->dengan_ttd)
                                        <img src="{{ asset('storage/ttdas.png') }}" alt="Tanda Tangan"
                                            style="width: 180px; height: auto; display: block; margin: 0 auto;">
                                    @else
                                        <div style="height: 80px;"></div>
                                    @endif
                                </div>
                                <div
                                    style="font-weight: bold; text-decoration: underline; color: #1a3c7e; font-size: 11pt;">
                                    MOHAMMAD DENDY ANANTABUDI</div>
                                <div style="font-size: 11pt; color: #1a1a2e;">Direktur</div>
                            </div>
                        </div>

                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Footer for Screen Preview -->
        <div class="screen-footer" style="margin-top: 20px;">
            <div class="footer-content">
                <span>{{ $footerTitle }}</span>
            </div>
        </div>

    </div>
</body>

</html>