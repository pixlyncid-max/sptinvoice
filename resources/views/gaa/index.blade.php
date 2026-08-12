@extends('layouts.app')

@section('title', 'Data GAA')

@section('actions')
<a href="{{ route('gaa.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
    + Tambah Data GAA
</a>
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

            <button type="submit" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                Cari
            </button>

            @if(request('search') || request('checklist_coretax'))
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
                        @if($gaa->checklist_coretax == 'Sudah')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                Sudah
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                Belum
                            </span>
                        @endif
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
@endsection
