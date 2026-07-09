<table>
    <thead>
        <tr>
            <th colspan="{{ $daysInMonth + 4 }}" style="font-size: 14pt; font-weight: bold; text-align: center;">LAPORAN ABSENSI KARYAWAN</th>
        </tr>
        <tr>
            <th colspan="{{ $daysInMonth + 4 }}" style="text-align: center;">Periode: {{ $date->format('F Y') }}</th>
        </tr>
        <tr></tr>
        <tr>
            <th style="background-color: #5b397d; color: #ffffff; border: 1pt solid #000000; font-weight: bold;">ID</th>
            <th style="background-color: #5b397d; color: #ffffff; border: 1pt solid #000000; font-weight: bold;">Name</th>
            <th style="background-color: #5b397d; color: #ffffff; border: 1pt solid #000000; font-weight: bold;">Position</th>
            @php
                $indonesianDays = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            @endphp
            @for($d=1; $d<=$daysInMonth; $d++)
            <th style="background-color: #5b397d; color: #ffffff; border: 1pt solid #000000; font-weight: bold; text-align: center;">
                {{ $indonesianDays[\Carbon\Carbon::createFromDate($year, $month, $d)->dayOfWeek] }} {{ $d }}
            </th>
            @endfor
            <th style="background-color: #5b397d; color: #ffffff; border: 1pt solid #000000; font-weight: bold;">Attendance</th>
            <th style="background-color: #ff6b8a; color: #ffffff; border: 1pt solid #000000; font-weight: bold;">Lembur</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employees as $employee)
        <tr>
            <td style="border: 1pt solid #000000;">{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</td>
            <td style="border: 1pt solid #000000;">{{ $employee->nama }}</td>
            <td style="border: 1pt solid #000000;">{{ $employee->jabatan }}</td>
            @php
                $empAttendances = $employee->attendances->keyBy(fn($a) => $a->tanggal->day);
            @endphp
            @for($d=1; $d<=$daysInMonth; $d++)
            @php
                $carbonDate = \Carbon\Carbon::createFromDate($year, $month, $d);
                $isWeekend = $carbonDate->isWeekend();
                $status = isset($empAttendances[$d]) ? $empAttendances[$d]->status : ($isWeekend ? 'libur' : '');
                $bgColor = $isWeekend ? '#fce4e4' : '#ffffff';
            @endphp
            <td style="border: 1pt solid #000000; text-align: center; background-color: {{ $bgColor }};">
                @switch($status)
                    @case('hadir') H @break
                    @case('sakit') S @break
                    @case('ijin') I @break
                    @case('telat_1') T1 @break
                    @case('telat_2') T2 @break
                    @case('libur') L @break
                @endswitch
            </td>
            @endfor
            <td style="border: 1pt solid #000000; font-weight: bold;">
                H:{{ $employee->attendances->whereIn('status', ['hadir', 'telat_1', 'telat_2'])->count() }}, 
                S:{{ $employee->attendances->where('status', 'sakit')->count() }},
                I:{{ $employee->attendances->where('status', 'ijin')->count() }}
            </td>
            <td style="border: 1pt solid #000000; font-weight: bold; text-align: center; color: #ff6b8a;">
                {{ $employee->attendances->sum('lembur_jam') }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
