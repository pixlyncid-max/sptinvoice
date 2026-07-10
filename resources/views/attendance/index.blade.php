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
    <div class="px-6 py-5 border-b border-slate-200 bg-white flex flex-col md:flex-row justify-between items-center gap-4">
        <form action="{{ route('attendance.index') }}" method="GET" class="flex items-center gap-2">
            <select name="month" class="rounded-md border-slate-300 text-sm py-1.5 focus:ring-primary focus:border-primary">
                @for($m=1; $m<=12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>
            <select name="year" class="rounded-md border-slate-300 text-sm py-1.5 focus:ring-primary focus:border-primary">
                @for($y=date('Y')-1; $y<=date('Y')+1; $y++)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
            <button type="submit" class="bg-[#1e293b] text-white px-4 py-1.5 rounded text-sm font-medium hover:bg-slate-800 transition">Pilih</button>
        </form>
        <div class="flex items-center gap-3 text-xs text-slate-600 font-medium flex-wrap justify-end">
            <div class="flex items-center gap-1"><span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span> Hadir</div>
            <div class="flex items-center gap-1"><span class="w-4 h-4 rounded-full bg-red-100 text-red-600 flex items-center justify-center"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></span> Sakit</div>
            <div class="flex items-center gap-1"><span class="w-4 h-4 rounded-full bg-red-100 text-red-800 flex items-center justify-center"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></span> S+: Sakit dengan Surat</div>
            <div class="flex items-center gap-1"><span class="w-4 h-4 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-[10px] font-bold">I</span> Ijin</div>
            <div class="flex items-center gap-1"><span class="w-4 h-4 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-[9px] font-bold">T1</span> Telat < 1j</div>
            <div class="flex items-center gap-1"><span class="w-4 h-4 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-[9px] font-bold">T2</span> Telat > 1j</div>
            <div class="flex items-center gap-1"><span class="text-slate-400 font-bold">L:</span> Libur</div>
        </div>
    </div>

    <form action="{{ route('attendance.bulkStore') }}" method="POST">
        @csrf
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-[11px]">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-3 py-3 text-left font-bold text-slate-700 uppercase border-r tracking-wider">ID</th>
                        <th class="px-3 py-3 text-left font-bold text-slate-700 uppercase border-r min-w-[150px] tracking-wider">Name</th>
                        <th class="px-3 py-3 text-left font-bold text-slate-700 uppercase border-r min-w-[120px] tracking-wider">Position</th>
                        @php
                            $indonesianDays = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
                        @endphp
                        @foreach($period as $carbonDate)
                        @php
                            $isWeekend = $carbonDate->isWeekend();
                        @endphp
                        <th class="px-1 py-2 text-center font-bold border-r {{ $isWeekend ? 'bg-red-50 text-red-600' : 'text-slate-700' }}">
                            <div class="text-[8px] uppercase tracking-wider mb-0.5">{{ $indonesianDays[$carbonDate->dayOfWeek] }}</div>
                            <div class="text-sm font-black">{{ $carbonDate->day }}</div>
                        </th>
                        @endforeach
                        <th class="px-3 py-3 text-center font-bold text-slate-700 bg-[#e2e8f0] uppercase tracking-wider">Summary</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @foreach($employees as $employee)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-3 py-2 border-r text-slate-500 font-mono text-[11px]">{{ str_pad($employee->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-3 py-2 border-r font-bold text-slate-800 text-xs">{{ $employee->nama }}</td>
                        <td class="px-3 py-2 border-r text-slate-500 text-[11px]">{{ $employee->jabatan }}</td>
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
                            
                            // Adjust status for sakit dengan surat so Alpine knows
                            if($status === 'sakit' && $sakit_surat) {
                                $status = 'sakit_surat';
                            }
                        @endphp
                        <td class="px-0 py-0 border-r text-center {{ $isWeekend ? 'bg-red-50/30' : '' }} relative align-top" x-data="{ status: '{{ $status }}', lembur: '{{ $lembur_jam > 0 ? $lembur_jam : '' }}' }">
                            <div class="flex flex-col items-center h-full">
                                <!-- Top part: Status -->
                                <div class="w-full h-[30px] flex items-center justify-center relative border-b border-slate-100">
                                    <select name="attendances[{{ $employee->id }}][{{ $dateStr }}][status]" x-model="status" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <option value=""></option>
                                        <option value="hadir">Hadir</option>
                                        <option value="sakit">Sakit</option>
                                        <option value="sakit_surat">Sakit Surat</option>
                                        <option value="ijin">Ijin</option>
                                        <option value="telat_1">Telat &lt; 1j</option>
                                        <option value="telat_2">Telat &gt; 1j</option>
                                        <option value="libur">Libur</option>
                                    </select>
                                    
                                    <div class="pointer-events-none">
                                        <template x-if="status == 'hadir'">
                                            <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                        </template>
                                        <template x-if="status == 'sakit'">
                                            <span class="w-5 h-5 rounded-full bg-red-100 text-red-600 flex items-center justify-center"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></span>
                                        </template>
                                        <template x-if="status == 'sakit_surat'">
                                            <span class="w-5 h-5 rounded-full bg-red-100 text-red-800 flex items-center justify-center"><svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg></span>
                                        </template>
                                        <template x-if="status == 'ijin'">
                                            <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-[10px] font-bold">I</span>
                                        </template>
                                        <template x-if="status == 'telat_1'">
                                            <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-[10px] font-bold">T1</span>
                                        </template>
                                        <template x-if="status == 'telat_2'">
                                            <span class="w-5 h-5 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-[10px] font-bold">T2</span>
                                        </template>
                                        <template x-if="status == 'libur' || (status == '' && {{ $isWeekend ? 'true' : 'false' }})">
                                            <span class="text-slate-300 font-bold text-[10px]">L</span>
                                        </template>
                                    </div>
                                </div>
                                
                                <!-- Bottom part: Lembur -->
                                <div class="w-full h-5 flex items-center justify-center bg-slate-50/50 relative">
                                    <input type="number" name="attendances[{{ $employee->id }}][{{ $dateStr }}][lembur]" x-model="lembur" min="0" max="4" class="absolute inset-0 w-full h-full opacity-0 cursor-text z-10" placeholder="L">
                                    <div class="pointer-events-none text-[9px] font-bold" :class="lembur ? 'text-[#1e293b]' : 'text-slate-300'">
                                        <span x-text="lembur ? lembur + 'h' : 'L'"></span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        @endforeach
                        <td class="px-3 py-2 bg-[#f1f5f9] border-l border-slate-200 align-top">
                            <div class="flex flex-col gap-1.5 text-[10px]">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg></span>
                                    <span class="font-bold text-slate-800">{{ $employee->attendances->where('status', 'hadir')->count() }}H</span>
                                    @if($employee->attendances->sum('lembur_jam') > 0)
                                        <span class="font-bold text-[#1e293b] bg-slate-200/80 px-1 rounded ml-1">{{ $employee->attendances->sum('lembur_jam') }}H OT</span>
                                    @endif
                                </div>
                                @if($employee->attendances->whereIn('status', ['telat_1', 'telat_2'])->count() > 0)
                                <div class="flex items-center gap-1.5">
                                    <span class="w-4 h-4 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-[9px] font-bold">T</span>
                                    <span class="font-bold text-slate-600">{{ $employee->attendances->whereIn('status', ['telat_1', 'telat_2'])->count() }} Telat</span>
                                </div>
                                @else
                                <div class="flex items-center gap-1.5">
                                    <span class="w-4 h-4 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-[9px]"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></span>
                                    <span class="font-bold text-slate-600">Hadir</span>
                                </div>
                                @endif
                            </div>
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
