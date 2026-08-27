<table>
    <thead>
        <tr>
            <th colspan="{{ count($period) + 5 }}" style="font-size: 14pt; font-weight: bold; text-align: center;">LAPORAN ABSENSI KARYAWAN</th>
        </tr>
        <tr>
            <th colspan="{{ count($period) + 5 }}" style="text-align: center;">Periode Cutoff: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }} ({{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }})</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="background-color: #2c3e6b; color: #ffffff; border: 1pt solid #000000; font-weight: bold; text-align: center;">ID</th>
            <th style="background-color: #2c3e6b; color: #ffffff; border: 1pt solid #000000; font-weight: bold; text-align: left;">Nama</th>
            <th style="background-color: #2c3e6b; color: #ffffff; border: 1pt solid #000000; font-weight: bold; text-align: left;">Jabatan</th>
            @php
                $indonesianDays = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            @endphp
            @foreach($period as $carbonDate)
            @php
                $isWeekend = $carbonDate->isWeekend();
                $bgHeader = $isWeekend ? '#dc2626' : '#2c3e6b';
            @endphp
            <th style="background-color: {{ $bgHeader }}; color: #ffffff; border: 1pt solid #000000; font-weight: bold; text-align: center;">
                {{ $indonesianDays[$carbonDate->dayOfWeek] }} {{ $carbonDate->day }}
            </th>
            @endforeach
            <th style="background-color: #2c3e6b; color: #ffffff; border: 1pt solid #000000; font-weight: bold; text-align: center;">Kehadiran</th>
            <th style="background-color: #e11d48; color: #ffffff; border: 1pt solid #000000; font-weight: bold; text-align: center;">Lembur</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employees as $employee)
        <tr>
            <td style="border: 1pt solid #000000; text-align: center;">{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</td>
            <td style="border: 1pt solid #000000; font-weight: bold;">{{ $employee->nama }}</td>
            <td style="border: 1pt solid #000000;">{{ $employee->jabatan }}</td>
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
                $bgColor = $isWeekend ? '#fce4e4' : '#ffffff';
            @endphp
            <td style="border: 1pt solid #000000; text-align: center; background-color: {{ $bgColor }};">
                @if($status === 'hadir')
                    H
                @elseif($status === 'sakit')
                    {{ $sakit_surat ? 'S+' : 'S' }}
                @elseif($status === 'ijin')
                    I
                @elseif($status === 'telat_1')
                    T1
                @elseif($status === 'telat_2')
                    T2
                @elseif($status === 'libur')
                    L
                @else
                    -
                @endif
            </td>
            @endforeach
            <td style="border: 1pt solid #000000; font-weight: bold; background-color: #f1f5f9; text-align: center;">
                H:{{ $employee->attendances->whereIn('status', ['hadir', 'telat_1', 'telat_2'])->count() }} | 
                S:{{ $employee->attendances->where('status', 'sakit')->count() }} | 
                I:{{ $employee->attendances->where('status', 'ijin')->count() }}
            </td>
            <td style="border: 1pt solid #000000; font-weight: bold; text-align: center; color: #e11d48; background-color: #ffeff2;">
                {{ (float) $employee->attendances->sum('lembur_jam') }}j
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
