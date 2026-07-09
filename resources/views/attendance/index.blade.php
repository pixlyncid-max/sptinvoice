@extends('layouts.app')

@section('title', 'Absensi Karyawan')

@section('actions')
<div class="flex gap-2" x-data>
    <button type="button" @click="$dispatch('open-daily-modal')" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark">
        Input Absensi Harian
    </button>
    <a href="{{ route('attendance.export.pdf', request()->all()) }}" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
        PDF
    </a>
    <a href="{{ route('attendance.export.excel', request()->all()) }}" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
        Excel
    </a>
</div>
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
    <div class="px-4 py-5 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
        <form action="{{ route('attendance.index') }}" method="GET" class="flex items-center gap-2">
            <select name="month" class="rounded-md border-slate-300 text-sm py-1">
                @for($m=1; $m<=12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>
            <select name="year" class="rounded-md border-slate-300 text-sm py-1">
                @for($y=date('Y')-1; $y<=date('Y')+1; $y++)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="bg-primary text-white px-3 py-1 rounded text-sm">Pilih</button>
        </form>
        <div class="text-xs text-slate-500">
            H: Hadir | S: Sakit | S+: Sakit dengan Surat | I: Ijin | T1: Telat < 1j | T2: Telat > 1j | L: Libur
        </div>
    </div>

    <form action="{{ route('attendance.bulkStore') }}" method="POST">
        @csrf
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-[10px]">
                <thead class="bg-slate-100">
                    <tr>
                        <th class="px-2 py-2 text-left font-bold text-slate-700 uppercase border-r">ID</th>
                        <th class="px-2 py-2 text-left font-bold text-slate-700 uppercase border-r min-w-[120px]">Name</th>
                        <th class="px-2 py-2 text-left font-bold text-slate-700 uppercase border-r min-w-[100px]">Position</th>
                        @php
                            $indonesianDays = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                        @endphp
                        @foreach($period as $carbonDate)
                        @php
                            $isWeekend = $carbonDate->isWeekend();
                        @endphp
                        <th class="px-1 py-1 text-center font-bold text-slate-700 border-r {{ $isWeekend ? 'bg-red-50 text-red-600' : '' }}">
                            <div class="text-[7px] uppercase opacity-70">{{ $indonesianDays[$carbonDate->dayOfWeek] }}</div>
                            <div class="text-[10px]">{{ $carbonDate->day }}</div>
                        </th>
                        @endforeach
                        <th class="px-2 py-2 text-center font-bold text-slate-700">Summary</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @foreach($employees as $employee)
                    <tr>
                        <td class="px-2 py-1 border-r text-slate-500">{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-2 py-1 border-r font-medium text-slate-900">{{ $employee->nama }}</td>
                        <td class="px-2 py-1 border-r text-slate-500">{{ $employee->jabatan }}</td>
                        @php
                            $empAttendances = $employee->attendances->keyBy(fn($a) => $a->tanggal->format('Y-m-d'));
                        @endphp
                        @foreach($period as $carbonDate)
                        @php
                            $dateStr = $carbonDate->format('Y-m-d');
                            $isWeekend = $carbonDate->isWeekend();
                            $att = $empAttendances->get($dateStr);
                            $status = $att ? $att->status : ($isWeekend ? 'libur' : '');
                            $lembur_jam = $att ? $att->lembur_jam : 0;
                            $sakit_surat = $att ? $att->sakit_dengan_surat : false;
                        @endphp
                        <td class="px-0 py-1 border-r text-center {{ $isWeekend ? 'bg-red-50/50' : '' }}">
                            <div class="flex flex-col items-center gap-0.5 px-0.5">
                                <select name="attendances[{{ $employee->id }}][{{ $dateStr }}][status]" class="w-full h-6 border-0 p-0 text-[8px] focus:ring-0 cursor-pointer bg-transparent text-center appearance-none {{ $status == 'libur' ? 'text-red-500 font-bold' : '' }}">
                                    <option value=""></option>
                                    <option value="hadir" {{ $status == 'hadir' ? 'selected' : '' }}>H</option>
                                    <option value="sakit" {{ $status == 'sakit' && !$sakit_surat ? 'selected' : '' }}>S</option>
                                    <option value="sakit_surat" {{ ($status == 'sakit' && $sakit_surat) || $status == 'sakit_surat' ? 'selected' : '' }}>S+</option>
                                    <option value="ijin" {{ $status == 'ijin' ? 'selected' : '' }}>I</option>
                                    <option value="telat_1" {{ $status == 'telat_1' ? 'selected' : '' }}>T1</option>
                                    <option value="telat_2" {{ $status == 'telat_2' ? 'selected' : '' }}>T2</option>
                                    <option value="libur" {{ $status == 'libur' ? 'selected' : '' }}>L</option>
                                </select>
                                <div class="flex items-center justify-center w-full px-1">
                                    <input type="number" name="attendances[{{ $employee->id }}][{{ $dateStr }}][lembur]" value="{{ $lembur_jam > 0 ? $lembur_jam : '' }}" min="0" max="4" class="w-full h-4 border border-slate-200 rounded-[2px] text-[7px] p-0 text-center focus:border-primary focus:ring-0" placeholder="L">
                                </div>
                            </div>
                        </td>
                        @endforeach
                        <td class="px-2 py-1 text-center whitespace-nowrap bg-slate-50">
                            <span class="text-emerald-600 font-bold">{{ $employee->attendances->where('status', 'hadir')->count() }}H</span>
                            <span class="text-amber-600 font-bold">{{ $employee->attendances->whereIn('status', ['telat_1', 'telat_2'])->count() }}T</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-4 py-3 bg-slate-50 border-t border-slate-200 text-right">
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-md font-medium hover:bg-primary-dark transition shadow-sm">
                Simpan Absensi
            </button>
        </div>
    </form>
</div>

<!-- Modal Input Absensi Harian -->
<div x-data="{ open: false }" 
     x-on:open-daily-modal.window="open = true" 
     x-on:keydown.escape.window="open = false"
     x-show="open" 
     style="display: none;"
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
        <div x-show="open" 
             class="fixed inset-0 bg-transparent transition-opacity z-40" 
             aria-hidden="true" @click="open = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="open" 
             class="relative z-50 inline-block bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
            
            <form action="{{ route('attendance.dailyStore') }}" method="POST">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Input Absensi Harian</h3>
                            <div class="mt-4 mb-4 flex items-center gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm" required>
                                </div>
                            </div>
                            
                            <div class="mt-2 overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-sm border">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 text-left font-semibold text-gray-700 border-r" rowspan="2">Nama</th>
                                            <th class="px-1 py-1 text-center border-b border-l bg-green-100" colspan="7">
                                                <button type="button" onclick="checkAllHadir()" class="text-green-800 font-bold w-full text-xs hover:text-green-900">HADIR SEMUA</button>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="px-1 py-1 text-center font-semibold text-gray-700 border-l border-b w-8">H</th>
                                            <th class="px-1 py-1 text-center font-semibold text-gray-700 border-l border-b w-8">S</th>
                                            <th class="px-1 py-1 text-center font-semibold text-gray-700 border-l border-b w-8">S+</th>
                                            <th class="px-1 py-1 text-center font-semibold text-gray-700 border-l border-b w-8">I</th>
                                            <th class="px-1 py-1 text-center font-semibold text-gray-700 border-l border-b w-8">T1</th>
                                            <th class="px-1 py-1 text-center font-semibold text-gray-700 border-l border-b w-8">T2</th>
                                            <th class="px-1 py-1 text-center font-semibold text-gray-700 border-l border-b w-8">L</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($employees as $employee)
                                        <tr>
                                            <td class="px-3 py-2 border-r">{{ $employee->nama }}</td>
                                            <td class="px-1 py-2 text-center border-l bg-green-50"><input type="radio" name="daily_attendance[{{ $employee->id }}]" value="hadir" class="hadir-radio h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500"></td>
                                            <td class="px-1 py-2 text-center border-l"><input type="radio" name="daily_attendance[{{ $employee->id }}]" value="sakit" class="h-4 w-4 text-primary border-gray-300 focus:ring-primary"></td>
                                            <td class="px-1 py-2 text-center border-l"><input type="radio" name="daily_attendance[{{ $employee->id }}]" value="sakit_surat" class="h-4 w-4 text-primary border-gray-300 focus:ring-primary"></td>
                                            <td class="px-1 py-2 text-center border-l"><input type="radio" name="daily_attendance[{{ $employee->id }}]" value="ijin" class="h-4 w-4 text-primary border-gray-300 focus:ring-primary"></td>
                                            <td class="px-1 py-2 text-center border-l"><input type="radio" name="daily_attendance[{{ $employee->id }}]" value="telat_1" class="h-4 w-4 text-primary border-gray-300 focus:ring-primary"></td>
                                            <td class="px-1 py-2 text-center border-l"><input type="radio" name="daily_attendance[{{ $employee->id }}]" value="telat_2" class="h-4 w-4 text-primary border-gray-300 focus:ring-primary"></td>
                                            <td class="px-1 py-2 text-center border-l"><input type="radio" name="daily_attendance[{{ $employee->id }}]" value="libur" class="h-4 w-4 text-primary border-gray-300 focus:ring-primary"></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:ml-3 sm:w-auto sm:text-sm">
                        Simpan Absensi Harian
                    </button>
                    <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    select::-ms-expand { display: none; }
    select { -webkit-appearance: none; -moz-appearance: none; text-indent: 1px; text-overflow: ''; }
</style>
@endpush

@push('scripts')
<script>
    function checkAllHadir() {
        const radios = document.querySelectorAll('.hadir-radio');
        radios.forEach(radio => {
            radio.checked = true;
        });
    }
</script>
@endpush
