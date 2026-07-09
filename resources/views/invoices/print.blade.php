@extends('layouts.print')

@section('title', 'Invoice - ' . $invoice->nomor_invoice)

@section('content')
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        .print-container {
            width: 297mm !important;
            max-width: 100% !important;
            min-height: 210mm !important;
            padding: 0 20mm 15mm 20mm !important;
            margin: 0 auto;
            font-size: 8pt !important;
            line-height: 1.2;
        }

        .invoice-header {
            margin-left: 0;
            margin-right: 0;
            margin-bottom: 0;
            text-align: center;
        }

        .invoice-header img {
            width: 100%;
            max-width: 250mm;
            margin: 0 auto;
        }

        table {
            font-size: 8pt !important;
        }

        @media print {
            .print-container {
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 20mm 15mm 20mm !important;
            }
        }
    </style>
    {{-- ==================== HEADER (from image.png) ==================== --}}
    <div class="invoice-header" style="text-align: center;">
        <img src="{{ asset('storage/image.png') }}" alt="Ganesha Arta Adiwangsa">
    </div>

    {{-- ==================== INVOICE TITLE BAR ==================== --}}
    <div
        style="background: linear-gradient(90deg, #1a3c7e, #2563eb); color: white; text-align: center; padding: 4px 0; font-weight: bold; font-size: 12px; letter-spacing: 4px; margin-top: 24px;">
        INVOICE
    </div>

    {{-- ==================== BILLING + META INFO ==================== --}}
    <div style="display: flex; justify-content: space-between; margin-top: 6px; font-size: 8pt;">
        {{-- Left: Bill To --}}
        <div style="width: 50%;">
            <div style="font-weight: bold; margin-bottom: 2px; font-size: 8pt;">TAGIHAN KEPADA :</div>
            <div style="font-weight: bold; font-size: 9pt;">{{ $invoice->client->nama }}</div>
            @if($invoice->client->perusahaan)
                <div>{{ $invoice->client->perusahaan }}</div>
            @endif
            @if($invoice->client->alamat)
                <div style="white-space: pre-line; margin-top: 2px;">{{ $invoice->client->alamat }}</div>
            @endif
            @if($invoice->client->telepon)
                <div>Telp: {{ $invoice->client->telepon }}</div>
            @endif
        </div>

        {{-- Right: Invoice Meta --}}
        <div style="width: 40%;">
            <table style="width: 100%; font-size: 8pt; border-collapse: collapse;">
                <tr>
                    <td style="padding: 3px 6px; font-weight: bold;">TANGGAL</td>
                    <td style="padding: 3px 6px; background: #e2e8f0;">{{ $invoice->tanggal_invoice->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td style="padding: 3px 6px; font-weight: bold;">No. INVOICE</td>
                    <td style="padding: 3px 6px; background: #e2e8f0;">{{ $invoice->nomor_invoice }}</td>
                </tr>
                <tr>
                    <td style="padding: 3px 6px; font-weight: bold;">PERIODE</td>
                    <td style="padding: 3px 6px; background: #e2e8f0;">{{ $invoice->periode ?: '-' }}</td>
                </tr>
                <tr>
                    <td style="padding: 3px 6px; font-weight: bold;">DUE DATE</td>
                    <td style="padding: 3px 6px; background: #e2e8f0;">{{ $invoice->tanggal_jatuh_tempo->format('d/m/Y') }}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    {{-- ==================== ITEMS TABLE + TOTALS (same table) ==================== --}}
    <table style="width: 100%; border-collapse: collapse; margin-top: 24px; font-size: 8pt;" cellpadding="0"
        cellspacing="0">
        <thead>
            <tr
                style="background: linear-gradient(90deg, #1a3c7e, #2563eb); color: white; text-align: center; font-size: 8pt; font-weight: bold;">
                <th style="border: 1px solid #1e3a5f; padding: 4px 4px; width: 35px;">NO</th>
                <th style="border: 1px solid #1e3a5f; padding: 4px 4px;">DESKRIPSI PEKERJAAN</th>
                <th style="border: 1px solid #1e3a5f; padding: 4px 4px; width: 45px;">QTY</th>
                <th style="border: 1px solid #1e3a5f; padding: 4px 4px; width: 110px;">BIAYA SATUAN</th>
                <th style="border: 1px solid #1e3a5f; padding: 4px 4px; width: 110px;">JUMLAH</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
                <tr>
                    <td style="border: 1px solid #94a3b8; padding: 3px 4px; text-align: center;">{{ $index + 1 }}</td>
                    <td style="border: 1px solid #94a3b8; padding: 3px 4px;">{{ $item->deskripsi }}</td>
                    <td style="border: 1px solid #94a3b8; padding: 3px 4px; text-align: center;">{{ $item->qty }}</td>
                    <td style="border: 1px solid #94a3b8; padding: 3px 4px; text-align: right;">
                        {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td style="border: 1px solid #94a3b8; padding: 3px 4px; text-align: right;">
                        {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach

            {{-- Empty rows --}}
            @for($i = count($invoice->items); $i < max(2, count($invoice->items)); $i++)
                <tr>
                    <td style="border: 1px solid #94a3b8; padding: 3px 4px; text-align: center;">{{ $i + 1 }}</td>
                    <td style="border: 1px solid #94a3b8; padding: 3px 4px;">&nbsp;</td>
                    <td style="border: 1px solid #94a3b8; padding: 3px 4px;">&nbsp;</td>
                    <td style="border: 1px solid #94a3b8; padding: 3px 4px;">&nbsp;</td>
                    <td style="border: 1px solid #94a3b8; padding: 3px 4px;">&nbsp;</td>
                </tr>
            @endfor

            {{-- ===== TOTALS (inside same table, colspan=3 for alignment) ===== --}}
            @php
                $isTermin = str_contains(strtolower($invoice->pajak_label), 'termin') || str_contains(strtolower($invoice->pajak_label), 'dp');
                $showPajak = $invoice->pajak_persen > 0 || $isTermin;
                $pajakAmount = $isTermin ? $invoice->total : ($invoice->total - $invoice->subtotal);
                $labelPajak = $invoice->pajak_label ?: 'PAJAK';
            @endphp

            @if($showPajak)
                <tr>
                    <td colspan="2" style="border: none; padding: 0;"></td>
                    <td colspan="2"
                        style="padding: 2px 4px; background: #cbd5e1; font-weight: bold; text-align: right; border: 1px solid #94a3b8; font-style: italic; white-space: nowrap;">
                        SUBTOTAL</td>
                    <td
                        style="padding: 2px 4px; background: #cbd5e1; font-weight: bold; border: 1px solid #94a3b8; text-align: right; white-space: nowrap;">
                        <span style="float: left;">IDR</span> {{ number_format($invoice->subtotal, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="border: none; padding: 0;"></td>
                    <td colspan="2"
                        style="padding: 2px 4px; background: #cbd5e1; font-weight: bold; text-align: right; border: 1px solid #94a3b8; font-style: italic; white-space: nowrap;">
                        {{ strtoupper($labelPajak) }} ({{ floatval($invoice->pajak_persen) }}%)</td>
                    <td
                        style="padding: 2px 4px; background: #cbd5e1; font-weight: bold; border: 1px solid #94a3b8; text-align: right; white-space: nowrap;">
                        <span style="float: left;">IDR</span> {{ number_format($pajakAmount, 0, ',', '.') }}
                    </td>
                </tr>
            @endif
            <tr>
                <td colspan="2" style="border: none; padding: 0;"></td>
                <td colspan="2"
                    style="padding: 2px 4px; background: #cbd5e1; font-weight: bold; text-align: right; border: 1px solid #94a3b8; font-style: italic; white-space: nowrap;">
                    GRAND TOTAL</td>
                <td
                    style="padding: 2px 4px; background: #cbd5e1; font-weight: bold; border: 1px solid #94a3b8; text-align: right; white-space: nowrap;">
                    <span style="float: left;">IDR</span> {{ number_format($invoice->total, 0, ',', '.') }}
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border: none; padding: 0;"></td>
                <td colspan="2"
                    style="padding: 3px 4px; background: linear-gradient(90deg, #1a3c7e, #2563eb); color: white; font-weight: bold; text-align: right; border: 1px solid #1e3a5f; font-style: italic; white-space: nowrap;">
                    JUMLAH DITAGIH</td>
                <td
                    style="padding: 3px 4px; background: linear-gradient(90deg, #1a3c7e, #2563eb); color: white; font-weight: bold; border: 1px solid #1e3a5f; text-align: right; white-space: nowrap;">
                    <span style="float: left;">IDR</span> {{ number_format($invoice->total, 0, ',', '.') }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- ==================== PAYMENT INFO + SIGNATURE ==================== --}}
    <div style="display: flex; justify-content: space-between; margin-top: 24px; font-size: 9pt;">
        {{-- Left: Payment Method --}}
        <div style="width: 50%;">
            <div style="font-weight: bold; margin-bottom: 4px;">METODE PEMBAYARAN</div>
            <table style="font-size: 8pt;">
                @if($invoice->bank)
                    <tr>
                        <td style="padding: 2px 0; width: 130px; white-space: nowrap;">Bank</td>
                        <td style="padding: 2px 0;">: {{ $invoice->bank->nama_bank }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 2px 0; white-space: nowrap;">No. Rek</td>
                        <td style="padding: 2px 0;">: {{ $invoice->bank->nomor_rekening }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 2px 0; white-space: nowrap;">Rek Atas Nama</td>
                        <td style="padding: 2px 0;">: {{ $invoice->bank->atas_nama }}</td>
                    </tr>
                @else
                    <tr>
                        <td style="padding: 2px 0; width: 130px; white-space: nowrap;">Bank</td>
                        <td style="padding: 2px 0;">: BNI</td>
                    </tr>
                    <tr>
                        <td style="padding: 2px 0; white-space: nowrap;">No. Rek</td>
                        <td style="padding: 2px 0;">: 2033298312</td>
                    </tr>
                    <tr>
                        <td style="padding: 2px 0; white-space: nowrap;">Rek Atas Nama</td>
                        <td style="padding: 2px 0;">: Ganesha Arta Adiwangsa</td>
                    </tr>
                @endif
            </table>
        </div>

        {{-- Right: Signature Block --}}
        <div style="width: 40%; text-align: center;">
            <div style="font-weight: bold; font-size: 8pt;">PT. GANESHA ARTA ADIWANGSA</div>

            {{-- Signature image (ttd.png) --}}
            <div style="margin: 4px auto; width: 160px;">
                @if($invoice->dengan_ttd)
                    <img src="{{ asset('storage/ttd.png') }}" alt="Tanda Tangan"
                        style="width: 160px; height: auto; display: block; margin: 0 auto;">
                @else
                    <div style="height: 100px;"></div>
                @endif
            </div>

            <div style="font-weight: bold; text-decoration: underline; color: #1a3c7e; font-size: 8pt;">Aulia Nursabila
            </div>
            <div style="font-size: 8px; color: #64748b;">Staff Finance</div>
        </div>
    </div>

    {{-- ==================== TERBILANG ==================== --}}
    <div style="margin-top: 12px; font-size: 9pt; display: flex; align-items: flex-start;">
        <span style="font-weight: bold; white-space: nowrap; margin-right: 6px;">Terbilang:</span>
        <span style="font-style: italic; border-bottom: 1px solid #94a3b8; padding-bottom: 2px; flex: 1;">
            {{ terbilang($invoice->total) }}
        </span>
    </div>

    {{-- ==================== CATATAN ==================== --}}
    @if($invoice->catatan)
        <div
            style="margin-top: 14px; padding: 8px 12px; background: #fefce8; border: 1px solid #fde68a; border-radius: 4px; font-size: 10px; color: #92400e;">
            <strong>Catatan:</strong> {{ $invoice->catatan }}
        </div>
    @endif
@endsection