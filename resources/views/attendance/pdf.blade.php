<!DOCTYPE html>
<html>
<head>
    <title>Laporan Absensi - {{ $date->format('F Y') }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #444; padding: 4px 2px; text-align: center; }
        th { background-color: #5b397d; color: white; font-weight: bold; text-transform: uppercase; font-size: 9px; }
        .name-col { text-align: left; padding-left: 5px; width: 150px; }
        .id-col { width: 30px; }
        .pos-col { text-align: left; padding-left: 5px; width: 100px; }
        .weekend { background-color: #fce4e4; color: #cc0000; }
        .summary-col { background-color: #f1f1f1; font-weight: bold; width: 60px; }
        h2 { text-align: center; margin-bottom: 0; }
        p { text-align: center; margin-top: 5px; color: #666; }
    </style>
</head>
<body>
    <h2>LAPORAN ABSENSI KARYAWAN</h2>
    <p>Periode: {{ $date->format('F Y') }}</p>

    <table>
        <thead>
            <tr>
                <th class="id-col">ID</th>
                <th class="name-col">Name</th>
                <th class="pos-col">Position</th>
                @php
                    $indonesianDays = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                @endphp
                @for($d=1; $d<=$daysInMonth; $d++)
                @php
                    $carbonDate = \Carbon\Carbon::createFromDate($year, $month, $d);
                    $isWeekend = $carbonDate->isWeekend();
                @endphp
                <th class="{{ $isWeekend ? 'weekend' : '' }}">
                    <div style="font-size: 6px; opacity: 0.7;">{{ $indonesianDays[$carbonDate->dayOfWeek] }}</div>
                    <div>{{ $d }}</div>
                </th>
                @endfor
                <th class="summary-col">Attendance</th>
                <th class="summary-col" style="background-color: #ff6b8a; width: 40px;">Lembur</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td>{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td class="name-col">{{ $employee->nama }}</td>
                <td class="pos-col">{{ $employee->jabatan }}</td>
                @php
                    $empAttendances = $employee->attendances->keyBy(fn($a) => $a->tanggal->day);
                @endphp
                @for($d=1; $d<=$daysInMonth; $d++)
                @php
                    $carbonDate = \Carbon\Carbon::createFromDate($year, $month, $d);
                    $isWeekend = $carbonDate->isWeekend();
                    $status = isset($empAttendances[$d]) ? $empAttendances[$d]->status : ($isWeekend ? 'libur' : '');
                @endphp
                <td class="{{ $isWeekend ? 'weekend' : '' }}">
                    @switch($status)
                        @case('hadir') H @break
                        @case('sakit') S @break
                        @case('ijin') I @break
                        @case('telat_1') T1 @break
                        @case('telat_2') T2 @break
                        @case('libur') <span style="font-weight: bold;">L</span> @break
                    @endswitch
                </td>
                @endfor
                <td class="summary-col">
                    H:{{ $employee->attendances->whereIn('status', ['hadir', 'telat_1', 'telat_2'])->count() }} 
                    S:{{ $employee->attendances->where('status', 'sakit')->count() }}
                    I:{{ $employee->attendances->where('status', 'ijin')->count() }}
                </td>
                <td class="summary-col" style="font-weight: bold; color: #ff6b8a;">
                    {{ $employee->attendances->sum('lembur_jam') }}j
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 20px; font-size: 8px; color: #888;">
        Keterangan: H: Hadir, S: Sakit, I: Ijin, T1: Telat < 1j, T2: Telat > 1j, L: Libur
    </div>
</body>
</html>
