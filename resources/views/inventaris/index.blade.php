@extends('layouts.app')

@section('title', 'Manajemen Inventaris Barang')

@section('actions')
<div class="flex gap-2">
    <a href="{{ route('inventaris.scan') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-semibold rounded-md shadow-xs text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition border-indigo-200">
        <svg class="w-4 h-4 mr-1.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m0 11v1m5-6h-1m-11 0h1m2-5a7 7 0 1114 0 7 7 0 01-14 0z" /><circle cx="12" cy="12" r="2" /></svg>
        Scan Kamera QR
    </a>
    <a href="{{ route('inventaris.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
        + Tambah Barang
    </a>
</div>
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-lg border border-slate-200">
    <!-- Filter Panel -->
    <div class="px-4 py-5 sm:p-6 border-b border-slate-200">
        <form action="{{ route('inventaris.index') }}" method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <!-- Search bar -->
            <div class="md:col-span-2">
                <label for="search" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Cari Barang</label>
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="focus:ring-primary focus:border-primary block w-full pl-10 sm:text-sm border-slate-300 rounded-md py-2 px-3 border" placeholder="Cari kode, nama, merk...">
                </div>
            </div>

            <!-- Filter Kategori -->
            <div>
                <label for="kategori" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Kategori</label>
                <select name="kategori" id="kategori" class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border bg-white">
                    <option value="">Semua Kategori</option>
                    <option value="elektronik" {{ request('kategori') == 'elektronik' ? 'selected' : '' }}>Elektronik</option>
                    <option value="furniture" {{ request('kategori') == 'furniture' ? 'selected' : '' }}>Furniture</option>
                    <option value="alat_kerja" {{ request('kategori') == 'alat_kerja' ? 'selected' : '' }}>Alat Kerja</option>
                    <option value="kendaraan" {{ request('kategori') == 'kendaraan' ? 'selected' : '' }}>Kendaraan</option>
                </select>
            </div>

            <!-- Filter Kondisi -->
            <div>
                <label for="kondisi" class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Kondisi</label>
                <select name="kondisi" id="kondisi" class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border bg-white">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                    <option value="rusak_ringan" {{ request('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                    <option value="rusak_berat" {{ request('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                </select>
            </div>

            <!-- Action buttons -->
            <div class="md:col-span-4 flex justify-end gap-2 pt-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition">
                    Filter & Cari
                </button>
                @if(request('search') || request('kategori') || request('kondisi'))
                <a href="{{ route('inventaris.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kode Barang</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama Barang / Merk</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kategori</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal Beli</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Kondisi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Pengguna</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse ($inventaris as $item)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-sm font-semibold bg-slate-100 text-slate-800 border border-slate-200 font-mono">
                            {{ $item->kode_barang }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-slate-900">{{ $item->nama_barang }}</div>
                        <div class="text-sm text-slate-500">{{ $item->nama_merk ?: '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $catColors = [
                                'elektronik' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                'furniture' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'alat_kerja' => 'bg-sky-50 text-sky-700 border-sky-200',
                                'kendaraan' => 'bg-purple-50 text-purple-700 border-purple-200'
                            ];
                            $catColor = $catColors[$item->kategori] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $catColor }}">
                            {{ $item->kategori_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                        {{ $item->tanggal_beli->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $condColors = [
                                'baik' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'rusak_ringan' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'rusak_berat' => 'bg-red-50 text-red-700 border-red-200'
                            ];
                            $condColor = $condColors[$item->kondisi] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $condColor }}">
                            {{ $item->kondisi_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($item->employee)
                        <div class="flex items-center">
                            <div class="h-7 w-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold text-xs border border-slate-200">
                                {{ substr($item->employee->nama, 0, 1) }}
                            </div>
                            <div class="ml-2">
                                <div class="text-sm font-medium text-slate-900">{{ $item->employee->nama }}</div>
                                <div class="text-xs text-slate-500 font-mono">{{ $item->employee->nik }}</div>
                            </div>
                        </div>
                        @else
                        <span class="text-sm text-slate-400 italic">Belum diserahkan</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('inventaris.show', $item) }}" class="text-blue-600 hover:text-blue-900 px-2 py-1 bg-blue-50 hover:bg-blue-100 rounded transition flex items-center gap-1 shadow-xs border border-blue-100">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                Detail
                            </a>
                            <a href="{{ route('inventaris.qr', $item) }}" class="text-indigo-600 hover:text-indigo-900 px-2 py-1 bg-indigo-50 hover:bg-indigo-100 rounded transition flex items-center gap-1 shadow-xs border border-indigo-100">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m0 11v1m5-6h-1m-11 0h1m2-5a7 7 0 1114 0 7 7 0 01-14 0z" /><circle cx="12" cy="12" r="2" /></svg>
                                Cetak QR
                            </a>
                            <a href="{{ route('inventaris.edit', $item) }}" class="text-emerald-600 hover:text-emerald-900 px-2 py-1 bg-emerald-50 hover:bg-emerald-100 rounded transition flex items-center gap-1 shadow-xs border border-emerald-100">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                Edit
                            </a>
                            <form action="{{ route('inventaris.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus barang inventaris ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 px-2 py-1 bg-red-50 hover:bg-red-100 rounded transition flex items-center gap-1 shadow-xs border border-red-100">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                        <svg class="mx-auto h-12 w-12 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <p class="text-base font-medium text-slate-600">Belum ada data barang inventaris</p>
                        <p class="text-xs text-slate-400 mt-1">Silakan klik tombol "Tambah Barang" untuk menambahkan aset baru.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($inventaris->hasPages())
    <div class="px-4 py-3 border-t border-slate-200 sm:px-6">
        {{ $inventaris->links() }}
    </div>
    @endif
</div>
@endsection
