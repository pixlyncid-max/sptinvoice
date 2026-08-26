<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Absensi Karyawan - {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}</title>
    <style>
        body { font-family: sans-serif; font-size: 8px; margin: 10px; color: #1e293b; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 0.5px solid #64748b; padding: 3px 1px; text-align: center; font-size: 7.5px; }
        th { background-color: #2c3e6b; color: white; font-weight: bold; text-transform: uppercase; font-size: 7.5px; }
        .name-col { text-align: left; padding-left: 4px; width: 90px; }
        .id-col { width: 22px; }
        .pos-col { text-align: left; padding-left: 4px; width: 70px; }
        .weekend { background-color: #fce4e4; color: #dc2626; font-weight: bold; }
        .summary-col { background-color: #f1f5f9; font-weight: bold; width: 50px; }
        .lembur-col { background-color: #ffeff2; color: #e11d48; font-weight: bold; width: 30px; }
        h2 { text-align: center; margin: 0; font-size: 14px; color: #0f172a; }
        p { text-align: center; margin: 3px 0 0 0; color: #64748b; font-size: 9px; }
    </style>
</head>
<body>
    <h2>LAPORAN ABSENSI KARYAWAN</h2>
    <p>Periode Cutoff: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }} ({{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }})</p>

    <table>
        <thead>
            <tr>
                <th class="id-col">ID</th>
                <th class="name-col">Nama</th>
                <th class="pos-col">Jabatan</th>
                @php
                    $indonesianDays = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                @endphp
                @foreach($period as $carbonDate)
                @php
                    $isWeekend = $carbonDate->isWeekend();
                @endphp
                <th class="{{ $isWeekend ? 'weekend' : '' }}">
                    <div style="font-size: 5.5px; opacity: 0.85;">{{ $indonesianDays[$carbonDate->dayOfWeek] }}</div>
                    <div>{{ $carbonDate->day }}</div>
                </th>
                @endforeach
                <th class="summary-col">Kehadiran</th>
                <th class="lembur-col">Lembur</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td class="id-col">{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td class="name-col">{{ $employee->nama }}</td>
                <td class="pos-col">{{ $employee->jabatan }}</td>
                @php
                    $empAttendances = $employee->attendances->keyBy(fn($a) => $a->tanggal->format('Y-m-d'));
                @endphp
                @foreach($period as $carbonDate)
                @php
                    $dateStr = $carbonDate->format('Y-m-d');
                    $isWeekend = $carbonDate->isWeekend();
                    $att = $empAttendances->get($dateStr);
                    $status = $att ? $att->status : ($isWeekend ? 'libur' : '');
                    $sakit_surat = $att ? $att->sakit_dengan_surat : false;
                @endphp
                <td class="{{ $isWeekend ? 'weekend' : '' }}">
                    @if($status === 'hadir')
                        <span style="color: #16a34a; font-weight: bold;">H</span>
                    @elseif($status === 'sakit')
                        <span style="color: #dc2626; font-weight: bold;">{{ $sakit_surat ? 'S+' : 'S' }}</span>
                    @elseif($status === 'ijin')
                        <span style="color: #d97706; font-weight: bold;">I</span>
                    @elseif($status === 'telat_1')
                        <span style="color: #d97706; font-weight: bold;">T1</span>
                    @elseif($status === 'telat_2')
                        <span style="color: #d97706; font-weight: bold;">T2</span>
                    @elseif($status === 'libur')
                        <span style="color: #94a3b8;">L</span>
                    @else
                        -
                    @endif
                </td>
                @endforeach
                <td class="summary-col">
                    H:{{ $employee->attendances->whereIn('status', ['hadir', 'telat_1', 'telat_2'])->count() }}
                    S:{{ $employee->attendances->where('status', 'sakit')->count() }}
                    I:{{ $employee->attendances->where('status', 'ijin')->count() }}
                </td>
                <td class="lembur-col">
                    {{ $employee->attendances->sum('lembur_jam') }}j
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 10px; font-size: 7.5px; color: #64748b;">
        <strong>Keterangan:</strong> H = Hadir, S = Sakit, S+ = Sakit Surat, I = Ijin, T1 = Telat &lt; 1 Jam, T2 = Telat &gt; 1 Jam, L = Libur
    </div>
</body>
</html>
