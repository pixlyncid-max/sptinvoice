@extends('layouts.app')

@section('title', 'Slip Gaji Karyawan')

@section('content')
<div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
    <div class="px-4 py-5 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
        <form action="{{ route('salary.index') }}" method="GET" class="flex items-center gap-2">
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
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Karyawan</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Hadir</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Telat < 1j</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Telat > 1j</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase">Lembur</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Gaji Bersih</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @foreach($employees as $employee)
                @php $stats = $employee->salary_stats; @endphp
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-slate-900">{{ $employee->nama }}</div>
                        <div class="text-xs text-slate-500">{{ $employee->jabatan }} | Rp {{ number_format($employee->gaji_pokok, 0, ',', '.') }} /bulan</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-600">{{ $stats['hadir'] }} hari</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-amber-600">{{ $stats['telat_1'] }}x</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-red-600">{{ $stats['telat_2'] }}x</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                        <div class="font-bold text-primary">{{ $stats['lembur_weekday_hours'] + $stats['lembur_weekend_hours'] }} jam</div>
                        <div class="text-[10px] text-slate-400">WD: {{ $stats['lembur_weekday_hours'] }}j | WE: {{ $stats['lembur_weekend_hours'] }}j</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-slate-900" x-data="{ show: false }">
                        <div class="flex items-center justify-end gap-2">
                            <span x-show="show">Rp {{ number_format($stats['gaji_bersih'], 0, ',', '.') }}</span>
                            <span x-show="!show">Rp ••••••••</span>
                            <button @click="show = !show" class="text-slate-400 hover:text-primary transition-colors focus:outline-none">
                                <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.076m1.573-1.573A9.954 9.954 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.059 10.059 0 01-1.425 3.037m-2.427 2.427A4.992 4.992 0 0112 17c-1.285 0-2.46-.488-3.345-1.29m4.635-4.635a3 3 0 11-4.243 4.243m4.243-4.243L8.757 15.243"></path></svg>
                            </button>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button type="button" 
                            onclick="openAdjustmentModal({{ json_encode($employee) }}, {{ json_encode($employee->adjustment) }})"
                            class="text-amber-600 hover:text-amber-700 font-bold bg-amber-50 px-3 py-1 rounded mr-2">
                            Input Gaji
                        </button>
                        <a href="{{ route('salary.slip', ['employee' => $employee->id, 'month' => $month, 'year' => $year]) }}" target="_blank" class="text-primary hover:text-primary-dark font-bold bg-slate-100 px-3 py-1 rounded">Cetak Slip</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Adjustment Modal -->
<div id="adjustmentModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" onclick="closeAdjustmentModal()"></div>
        
        <div class="bg-white rounded-xl shadow-2xl z-50 w-full max-w-2xl transform transition-all">
            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50 rounded-t-xl">
                <h3 class="text-lg font-bold text-slate-800" id="modalTitle">Input Rincian Gaji</h3>
                <button onclick="closeAdjustmentModal()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <form action="{{ route('salary.adjustment') }}" method="POST" id="adjustmentForm" class="p-6">
                @csrf
                <input type="hidden" name="employee_id" id="adj_employee_id">
                <input type="hidden" name="month" value="{{ $month }}">
                <input type="hidden" name="year" value="{{ $year }}">
                
                <div class="grid grid-cols-2 gap-6">
                    <!-- PENDAPATAN -->
                    <div class="space-y-4">
                        <h4 class="font-bold text-emerald-600 border-b pb-2 uppercase text-xs">Pendapatan Extra</h4>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Reimburse</label>
                            <input type="number" name="reimburse" id="adj_reimburse" class="w-full rounded-md border-slate-300 text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Uang Kehadiran</label>
                            <input type="number" name="uang_kehadiran" id="adj_uang_kehadiran" class="w-full rounded-md border-slate-300 text-sm" placeholder="0">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Transport</label>
                            <input type="number" name="transport" id="adj_transport" class="w-full rounded-md border-slate-300 text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Bonus</label>
                            <input type="number" name="bonus" id="adj_bonus" class="w-full rounded-md border-slate-300 text-sm" placeholder="0">
                        </div>
                    </div>

                    <!-- POTONGAN -->
                    <div class="space-y-4">
                        <h4 class="font-bold text-red-600 border-b pb-2 uppercase text-xs">Potongan Extra</h4>

                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">PPH 21</label>
                            <input type="number" name="pph21" id="adj_pph21" class="w-full rounded-md border-slate-300 text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Pinjaman</label>
                            <input type="number" name="pinjaman" id="adj_pinjaman" class="w-full rounded-md border-slate-300 text-sm" placeholder="0">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Lain-lain</label>
                            <input type="number" name="lain_lain" id="adj_lain_lain" class="w-full rounded-md border-slate-300 text-sm" placeholder="0">
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" onclick="closeAdjustmentModal()" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-md hover:bg-slate-50">Batal</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-md hover:bg-primary-dark shadow-sm">Simpan Data Gaji</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openAdjustmentModal(employee, adjustment) {
    document.getElementById('modalTitle').innerText = 'Input Rincian Gaji: ' + employee.nama;
    document.getElementById('adj_employee_id').value = employee.id;
    
    // Reset form
    const fields = ['reimburse', 'uang_kehadiran', 'transport', 'bonus', 'pph21', 'pinjaman', 'lain_lain'];
    fields.forEach(field => {
        const el = document.getElementById('adj_' + field);
        if (el) el.value = adjustment ? adjustment[field] : '';
    });

    document.getElementById('adjustmentModal').classList.remove('hidden');
}

function closeAdjustmentModal() {
    document.getElementById('adjustmentModal').classList.add('hidden');
}
</script>
@endpush
@endsection
