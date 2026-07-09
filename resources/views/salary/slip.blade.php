<!DOCTYPE html>
<html>
<head>
    <title>Slip Gaji - {{ $employee->nama }}</title>
    <style>
        @page { margin: 0; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 13px; color: #000; margin: 0; padding: 0; line-height: 1.4; }
        
        .slip-container { width: 98%; max-width: 850px; margin: 0 auto; padding: 0 40px 40px 40px; box-sizing: border-box; }
        
        .employee-info { margin-top: 10px; width: 100%; }
        .employee-info table { width: 100%; border-collapse: collapse; }
        .employee-info td { padding: 2px 0; vertical-align: top; }
        .label-col { width: 90px; }
        .separator-col { width: 10px; }
        
        .salary-details { margin-top: 25px; width: 100%; }
        .salary-table { width: 100%; border-collapse: collapse; }
        .salary-table th { text-align: left; font-weight: bold; padding: 10px 0; }
        .salary-table td { padding: 3px 0; vertical-align: top; }
        
        .summary-section { margin-top: 10px; border-top: 1.5px solid #000; padding-top: 10px; width: 100%; }
        .summary-table { width: 100%; border-collapse: collapse; }
        .summary-table td { padding: 4px 0; vertical-align: top; }
        .bold { font-weight: bold; }
        
        .terbilang-section { margin-top: 20px; }
        .terbilang-value { font-weight: bold; font-style: italic; }
        
        .amount-col { text-align: right; width: 100px; }
        .currency-col { width: 30px; text-align: center; }
    </style>
</head>
<body>
    <style>
        .slip-header { margin: 0; margin-bottom: 20px; text-align: center; }
    </style>
    <div class="slip-header">
        <img src="{{ asset('storage/image.png') }}" alt="Ganesha Arta Adiwangsa" style="width: 100%; max-width: 850px; display: block; margin: 0 auto;">
    </div>

    <div class="slip-container">
        <div class="employee-info">
            <table style="width: 100%;">
                <tr>
                    <td class="label-col">Nama</td>
                    <td class="separator-col">:</td>
                    <td>{{ $employee->nama }}</td>
                    <td class="label-col">Tgl Masuk</td>
                    <td class="separator-col">:</td>
                    <td>{{ $employee->tgl_masuk ? $employee->tgl_masuk->format('d F Y') : '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">NIK</td>
                    <td class="separator-col">:</td>
                    <td>{{ $employee->nik ?? '-' }}</td>
                    <td class="label-col">Bank</td>
                    <td class="separator-col">:</td>
                    <td>{{ $employee->bank ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">Position</td>
                    <td class="separator-col">:</td>
                    <td>{{ $employee->jabatan }}</td>
                    <td class="label-col">No Rekening</td>
                    <td class="separator-col">:</td>
                    <td>{{ $employee->no_rekening ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label-col">Grade</td>
                    <td class="separator-col">:</td>
                    <td>{{ $employee->grade ?? '-' }}</td>
                    <td class="label-col">Nama Pemilik</td>
                    <td class="separator-col">:</td>
                    <td>{{ $employee->nama_rekening ?? $employee->nama }}</td>
                </tr>
            </table>
        </div>

        <div class="salary-details">
            <table class="salary-table">
                <thead>
                    <tr>
                        <th colspan="3" width="48%">PENDAPATAN</th>
                        <th width="4%"></th>
                        <th colspan="3" width="48%">POTONGAN</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="15%">Gaji Pokok</td>
                        <td class="currency-col">Rp</td>
                        <td class="amount-col">{{ number_format($stats['gaji_pokok'], 0, ',', '.') }}</td>
                        <td></td>
                        <td width="15%">BPJS Kesehatan</td>
                        <td class="currency-col">{{ $stats['bpjs_kesehatan'] > 0 ? 'Rp' : '-' }}</td>
                        <td class="amount-col">{{ $stats['bpjs_kesehatan'] > 0 ? number_format($stats['bpjs_kesehatan'], 0, ',', '.') : '' }}</td>
                    </tr>
                    <tr>
                        <td>Reimburse</td>
                        <td class="currency-col">{{ $stats['reimburse'] > 0 ? 'Rp' : '-' }}</td>
                        <td class="amount-col">{{ $stats['reimburse'] > 0 ? number_format($stats['reimburse'], 0, ',', '.') : '' }}</td>
                        <td></td>
                        <td>BPJS TK</td>
                        <td class="currency-col">{{ $stats['bpjs_tk'] > 0 ? 'Rp' : '-' }}</td>
                        <td class="amount-col">{{ $stats['bpjs_tk'] > 0 ? number_format($stats['bpjs_tk'], 0, ',', '.') : '' }}</td>
                    </tr>
                    <tr>
                        <td>Uang Kehadiran</td>
                        <td class="currency-col">{{ $stats['uang_kehadiran'] > 0 ? 'Rp' : '-' }}</td>
                        <td class="amount-col">{{ $stats['uang_kehadiran'] > 0 ? number_format($stats['uang_kehadiran'], 0, ',', '.') : '' }}</td>
                        <td></td>
                        <td>PPH 21</td>
                        <td class="currency-col">{{ $stats['pph21'] > 0 ? 'Rp' : '-' }}</td>
                        <td class="amount-col">{{ $stats['pph21'] > 0 ? number_format($stats['pph21'], 0, ',', '.') : '' }}</td>
                    </tr>
                    <tr>
                        <td>Lembur</td>
                        <td class="currency-col">{{ $stats['lembur'] > 0 ? 'Rp' : '-' }}</td>
                        <td class="amount-col">{{ $stats['lembur'] > 0 ? number_format($stats['lembur'], 0, ',', '.') : '' }}</td>
                        <td></td>
                        <td>Pinjaman</td>
                        <td class="currency-col">{{ $stats['pinjaman'] > 0 ? 'Rp' : '-' }}</td>
                        <td class="amount-col">{{ $stats['pinjaman'] > 0 ? number_format($stats['pinjaman'], 0, ',', '.') : '' }}</td>
                    </tr>
                    <tr>
                        <td>Transport</td>
                        <td class="currency-col">{{ $stats['transport'] > 0 ? 'Rp' : '-' }}</td>
                        <td class="amount-col">{{ $stats['transport'] > 0 ? number_format($stats['transport'], 0, ',', '.') : '' }}</td>
                        <td></td>
                        <td>Potongan Keterlambatan</td>
                        <td class="currency-col">Rp</td>
                        <td class="amount-col">{{ number_format($stats['potongan_telat_1'] + $stats['potongan_telat_2'], 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Bonus</td>
                        <td class="currency-col">{{ $stats['bonus'] > 0 ? 'Rp' : '-' }}</td>
                        <td class="amount-col">{{ $stats['bonus'] > 0 ? number_format($stats['bonus'], 0, ',', '.') : '' }}</td>
                        <td></td>
                        <td>Potongan Ijin/Sakit</td>
                        <td class="currency-col">{{ ($stats['potongan_ijin'] + $stats['potongan_sakit_ts']) > 0 ? 'Rp' : '-' }}</td>
                        <td class="amount-col">{{ ($stats['potongan_ijin'] + $stats['potongan_sakit_ts']) > 0 ? number_format($stats['potongan_ijin'] + $stats['potongan_sakit_ts'], 0, ',', '.') : '' }}</td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td></td>
                        <td>Lain-lain</td>
                        <td class="currency-col">{{ $stats['lain_lain'] > 0 ? 'Rp' : '-' }}</td>
                        <td class="amount-col">{{ $stats['lain_lain'] > 0 ? number_format($stats['lain_lain'], 0, ',', '.') : '' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="summary-section">
            <table class="summary-table">
                <tr>
                    <td width="15%" class="bold">Jumlah Penerimaan</td>
                    <td class="currency-col bold">Rp</td>
                    <td class="amount-col bold">{{ number_format($stats['total_penerimaan'], 0, ',', '.') }}</td>
                    <td width="4%"></td>
                    <td width="15%" class="bold">Jumlah Potongan</td>
                    <td class="currency-col bold">Rp</td>
                    <td class="amount-col bold">{{ number_format($stats['total_potongan'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="bold">Take Home Pay (THP)</td>
                    <td class="currency-col bold">Rp</td>
                    <td class="amount-col bold">{{ number_format($stats['gaji_bersih'], 0, ',', '.') }}</td>
                    <td colspan="4"></td>
                </tr>
            </table>
        </div>

        <div class="terbilang-section">
            Terbilang : &nbsp;&nbsp;&nbsp;&nbsp; <span class="terbilang-value">{{ terbilang($stats['gaji_bersih']) }}</span>
        </div>
    </div>
</body>
</html>
