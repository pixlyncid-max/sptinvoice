@extends('layouts.app')

@section('title', 'Data GAA')

@section('actions')
<div class="flex flex-wrap gap-2" x-data>
    @if($gaaList->total() > 0)
    <form action="{{ route('gaa.destroy-all') }}" method="POST" class="inline" onsubmit="return confirm('APAKAH ANDA YAKIN?\n\nSeluruh {{ $gaaList->total() }} Data GAA akan dihapus secara permanen dari sistem!');">
        @csrf
        @method('DELETE')
        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-red-300 text-sm font-medium rounded-md shadow-sm text-red-700 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
            <svg class="w-4 h-4 mr-1.5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
            Hapus Semua Data
        </button>
    </form>
    @endif
    <a href="{{ route('gaa.template') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
        <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
        Download Template
    </a>
    <button type="button" @click="$dispatch('open-import-modal')" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
        <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
        Import Excel
    </button>
    <a href="{{ route('gaa.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
        + Tambah Data GAA
    </a>
</div>
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-lg border border-slate-200">
    <div class="px-4 py-5 sm:p-6 border-b border-slate-200">
        <form action="{{ route('gaa.index') }}" method="GET" class="flex flex-wrap gap-4 items-center">
            <div class="flex-1 min-w-[240px]">
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="focus:ring-primary focus:border-primary block w-full pl-10 sm:text-sm border-slate-300 rounded-md py-2 px-3 border" placeholder="Cari nama perusahaan, NPWP, KPP, PIC, dll...">
                </div>
            </div>

            <div class="w-48">
                <select name="checklist_coretax" class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border" onchange="this.form.submit()">
                    <option value="">-- Status Coretax --</option>
                    <option value="Sudah" {{ request('checklist_coretax') == 'Sudah' ? 'selected' : '' }}>Sudah</option>
                    <option value="Belum" {{ request('checklist_coretax') == 'Belum' ? 'selected' : '' }}>Belum</option>
                </select>
            </div>

            <div class="w-44">
                <select name="per_page" class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border font-medium text-slate-700 bg-slate-50" onchange="this.form.submit()" title="Tampilkan Jumlah Data">
                    <option value="15" {{ request('per_page', '15') == '15' ? 'selected' : '' }}>Tampil: 15 Per Hal</option>
                    <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>Tampil: 25 Per Hal</option>
                    <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>Tampil: 50 Per Hal</option>
                    <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>Tampil: 100 Per Hal</option>
                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>⚡ Tampilkan Semua Data</option>
                </select>
            </div>

            <button type="submit" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                Cari
            </button>

            @if(request('search') || request('checklist_coretax') || request('per_page'))
            <a href="{{ route('gaa.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                Reset
            </a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 border-collapse">
            <thead class="bg-slate-100 text-slate-700 text-xs font-semibold uppercase tracking-wider">
                <tr>
                    <th rowspan="2" class="px-4 py-3 border border-slate-200 text-left">Nama Perusahaan</th>
                    <th rowspan="2" class="px-4 py-3 border border-slate-200 text-left">Nomor NPWP</th>
                    <th rowspan="2" class="px-4 py-3 border border-slate-200 text-left">KPP</th>
                    <th rowspan="2" class="px-4 py-3 border border-slate-200 text-left">Email</th>
                    <th rowspan="2" class="px-4 py-3 border border-slate-200 text-left">Password Email</th>
                    <th colspan="2" class="px-4 py-2 border border-slate-200 text-center bg-indigo-50 text-indigo-800">DJP Online</th>
                    <th rowspan="2" class="px-4 py-3 border border-slate-200 text-left">User NPWP 16</th>
                    <th colspan="2" class="px-4 py-2 border border-slate-200 text-center bg-amber-50 text-amber-800">PIC</th>
                    <th rowspan="2" class="px-4 py-3 border border-slate-200 text-left bg-emerald-50 text-emerald-800">Coretax Password</th>
                    <th rowspan="2" class="px-4 py-3 border border-slate-200 text-left">Keterangan</th>
                    <th rowspan="2" class="px-4 py-3 border border-slate-200 text-center">Checklist Coretax</th>
                    <th rowspan="2" class="px-4 py-3 border border-slate-200 text-right">Aksi</th>
                </tr>
                <tr>
                    <!-- Sub headers for DJP Online -->
                    <th class="px-3 py-2 border border-slate-200 text-left bg-indigo-50/50 text-indigo-700 font-normal">User</th>
                    <th class="px-3 py-2 border border-slate-200 text-left bg-indigo-50/50 text-indigo-700 font-normal">Password</th>
                    <!-- Sub headers for PIC -->
                    <th class="px-3 py-2 border border-slate-200 text-left bg-amber-50/50 text-amber-700 font-normal">NIK</th>
                    <th class="px-3 py-2 border border-slate-200 text-left bg-amber-50/50 text-amber-700 font-normal">Nama PIC</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200 text-sm text-slate-700">
                @forelse ($gaaList as $gaa)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3 border border-slate-200 font-medium text-slate-900 whitespace-nowrap">
                        {{ $gaa->nama_perusahaan }}
                    </td>
                    <td class="px-4 py-3 border border-slate-200 whitespace-nowrap font-mono text-xs">
                        {{ $gaa->npwp ?: '-' }}
                    </td>
                    <td class="px-4 py-3 border border-slate-200 whitespace-nowrap">
                        {{ $gaa->kpp ?: '-' }}
                    </td>
                    <td class="px-4 py-3 border border-slate-200 whitespace-nowrap">
                        {{ $gaa->email ?: '-' }}
                    </td>
                    <td class="px-4 py-3 border border-slate-200 whitespace-nowrap font-mono text-xs text-slate-600">
                        {{ $gaa->password_email ?: '-' }}
                    </td>
                    <td class="px-3 py-3 border border-slate-200 whitespace-nowrap font-mono text-xs bg-indigo-50/20">
                        {{ $gaa->djp_user ?: '-' }}
                    </td>
                    <td class="px-3 py-3 border border-slate-200 whitespace-nowrap font-mono text-xs bg-indigo-50/20 text-slate-600">
                        {{ $gaa->djp_password ?: '-' }}
                    </td>
                    <td class="px-4 py-3 border border-slate-200 whitespace-nowrap font-mono text-xs">
                        {{ $gaa->user_npwp_16 ?: '-' }}
                    </td>
                    <td class="px-3 py-3 border border-slate-200 whitespace-nowrap font-mono text-xs bg-amber-50/20">
                        {{ $gaa->pic_nik ?: '-' }}
                    </td>
                    <td class="px-3 py-3 border border-slate-200 whitespace-nowrap bg-amber-50/20">
                        {{ $gaa->pic_nama ?: '-' }}
                    </td>
                    <td class="px-4 py-3 border border-slate-200 whitespace-nowrap font-mono text-xs bg-emerald-50/20 text-slate-600">
                        {{ $gaa->coretax_password ?: '-' }}
                    </td>
                    <td class="px-4 py-3 border border-slate-200 max-w-xs truncate" title="{{ $gaa->keterangan }}">
                        {{ $gaa->keterangan ?: '-' }}
                    </td>
                    <td class="px-4 py-3 border border-slate-200 text-center whitespace-nowrap">
                        <form action="{{ route('gaa.toggle-checklist', $gaa->id) }}" method="POST" class="inline-block form-toggle-checklist" data-id="{{ $gaa->id }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    title="Klik untuk mengubah status (Sudah / Belum)" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold shadow-sm transition-all duration-150 transform hover:scale-105 cursor-pointer border {{ $gaa->checklist_coretax == 'Sudah' ? 'bg-emerald-100 text-emerald-800 border-emerald-300 hover:bg-emerald-200' : 'bg-amber-100 text-amber-800 border-amber-300 hover:bg-amber-200' }}">
                                @if($gaa->checklist_coretax == 'Sudah')
                                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Sudah</span>
                                @else
                                    <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span>Belum</span>
                                @endif
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-3 border border-slate-200 text-right whitespace-nowrap">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('gaa.edit', $gaa->id) }}" class="text-emerald-600 hover:text-emerald-900 px-2 py-1 bg-emerald-50 hover:bg-emerald-100 rounded transition text-xs font-medium">Edit</a>
                            
                            <form action="{{ route('gaa.destroy', $gaa->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data GAA ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 px-2 py-1 bg-red-50 hover:bg-red-100 rounded transition text-xs font-medium">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="14" class="px-6 py-12 text-center text-slate-500">
                        <svg class="mx-auto h-12 w-12 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Belum ada Data GAA.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($gaaList->hasPages())
    <div class="px-4 py-3 border-t border-slate-200 sm:px-6">
        {{ $gaaList->links() }}
    </div>
    @endif
</div>

<!-- Modal Import Excel -->
<div x-data="{ open: false }" 
     x-on:open-import-modal.window="open = true" 
     x-on:keydown.escape.window="open = false"
     x-show="open" 
     style="display: none;"
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
        <div x-show="open" 
             class="fixed inset-0 bg-slate-900/50 transition-opacity z-40" 
             aria-hidden="true" @click="open = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="open" 
             class="relative z-50 inline-block bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            
            <form action="{{ route('gaa.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-semibold text-slate-900" id="modal-title">Import Data GAA via Excel</h3>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-slate-700">Pilih File Excel (.xlsx, .xls, .csv)</label>
                                <input type="file" name="file_excel" accept=".xlsx, .xls, .csv" class="mt-2 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark" required>
                                <p class="mt-3 text-xs text-slate-500 leading-relaxed">
                                    Format file Excel harus memiliki header kolom: <strong>Nama Perusahaan, Nomor NPWP, KPP, Email, Password Email, DJP User, DJP Password, User NPWP 16, PIC NIK, PIC Nama, Coretax Password, Keterangan, Checklist Coretax</strong>.
                                    <br>Gunakan tombol <strong>Download Template</strong> untuk mengunduh contoh format file.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-base font-medium text-white hover:bg-primary-dark focus:outline-none sm:w-auto sm:text-sm">
                        Proses Import
                    </button>
                    <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.form-toggle-checklist').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = form.querySelector('button');
            const originalContent = btn.innerHTML;
            btn.style.opacity = '0.6';
            btn.disabled = true;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    _method: 'PATCH'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.checklist_coretax === 'Sudah') {
                        btn.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold shadow-sm transition-all duration-150 transform hover:scale-105 cursor-pointer border bg-emerald-100 text-emerald-800 border-emerald-300 hover:bg-emerald-200';
                        btn.innerHTML = `<svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg><span>Sudah</span>`;
                    } else {
                        btn.className = 'inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold shadow-sm transition-all duration-150 transform hover:scale-105 cursor-pointer border bg-amber-100 text-amber-800 border-amber-300 hover:bg-amber-200';
                        btn.innerHTML = `<svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg><span>Belum</span>`;
                    }
                } else {
                    btn.innerHTML = originalContent;
                }
            })
            .catch(err => {
                console.error(err);
                form.submit();
            })
            .finally(() => {
                btn.style.opacity = '1';
                btn.disabled = false;
            });
        });
    });
});
</script>
@endpush
