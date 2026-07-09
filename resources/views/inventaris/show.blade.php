@extends('layouts.app')

@section('title', 'Detail Barang Inventaris')

@section('actions')
<div class="flex gap-2">
    <a href="{{ route('inventaris.qr', $inventaris) }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-semibold rounded-md shadow-xs text-indigo-750 bg-indigo-50 hover:bg-indigo-100 transition border-indigo-200">
        <svg class="w-4 h-4 mr-1.5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m0 11v1m5-6h-1m-11 0h1m2-5a7 7 0 1114 0 7 7 0 01-14 0z" /><circle cx="12" cy="12" r="2" /></svg>
        Cetak QR Code
    </a>
    <a href="{{ route('inventaris.edit', $inventaris) }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
        Edit Barang
    </a>
    <a href="{{ route('inventaris.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50 transition">
        Kembali
    </a>
</div>
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
        <h3 class="text-lg leading-6 font-semibold text-slate-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            Informasi Aset Perusahaan
        </h3>
        <span class="inline-flex items-center px-3 py-1 rounded-md text-sm font-mono font-bold bg-slate-200 text-slate-800 border border-slate-300">
            {{ $inventaris->kode_barang }}
        </span>
    </div>
    <div class="p-6">
        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
            <div class="sm:col-span-1">
                <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nama Barang</dt>
                <dd class="mt-1 text-sm font-medium text-slate-950">{{ $inventaris->nama_barang }}</dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Merk / Brand</dt>
                <dd class="mt-1 text-sm text-slate-900">{{ $inventaris->nama_merk ?: '-' }}</dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kategori</dt>
                <dd class="mt-1 text-sm">
                    @php
                        $catColors = [
                            'elektronik' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            'furniture' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'alat_kerja' => 'bg-sky-50 text-sky-700 border-sky-200',
                            'kendaraan' => 'bg-purple-50 text-purple-700 border-purple-200'
                        ];
                        $catColor = $catColors[$inventaris->kategori] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $catColor }}">
                        {{ $inventaris->kategori_label }}
                    </span>
                </dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal Pembelian</dt>
                <dd class="mt-1 text-sm text-slate-900">{{ $inventaris->tanggal_beli->format('d F Y') }}</dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kondisi Saat Ini</dt>
                <dd class="mt-1 text-sm">
                    @php
                        $condColors = [
                            'baik' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'rusak_ringan' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                            'rusak_berat' => 'bg-red-50 text-red-700 border-red-200'
                        ];
                        $condColor = $condColors[$inventaris->kondisi] ?? 'bg-slate-50 text-slate-700 border-slate-200';
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $condColor }}">
                        {{ $inventaris->kondisi_label }}
                    </span>
                </dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Terakhir Diperbarui</dt>
                <dd class="mt-1 text-sm text-slate-500">{{ $inventaris->updated_at->format('d M Y, H:i') }} WIB</dd>
            </div>
            <div class="sm:col-span-2 border-t border-slate-100 pt-5">
                <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Pengguna / Penanggung Jawab</dt>
                <dd class="mt-1">
                    @if($inventaris->employee)
                    <div class="flex items-center bg-slate-50 p-3 rounded-lg border border-slate-200">
                        <div class="h-10 w-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-700 font-bold text-base">
                            {{ substr($inventaris->employee->nama, 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-semibold text-slate-900">{{ $inventaris->employee->nama }}</div>
                            <div class="text-xs text-slate-500 font-mono">NIK. {{ $inventaris->employee->nik }}</div>
                            <div class="text-xs text-slate-600 mt-0.5">
                                {{ $inventaris->employee->position->nama ?? '' }} • {{ $inventaris->employee->division->nama ?? '' }}
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 p-3 rounded-lg text-sm flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <span>Barang ini belum diserahkan ke karyawan. Masih tersimpan di inventaris umum.</span>
                    </div>
                    @endif
                </dd>
            </div>
        </dl>
    </div>
</div>
@endsection
