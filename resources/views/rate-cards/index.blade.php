@extends('layouts.app')

@section('title', 'Manajemen Rate Card')

@section('actions')
<a href="{{ route('rate-cards.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
    + Tambah Rate Card
</a>
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-lg border border-slate-200">
    <div class="px-4 py-5 sm:p-6 border-b border-slate-200">
        <form action="{{ route('rate-cards.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="focus:ring-primary focus:border-primary block w-full pl-10 sm:text-sm border-slate-300 rounded-md py-2 px-3 border" placeholder="Cari nama layanan...">
                </div>
            </div>
            <div>
                <select name="divisi" class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border">
                    <option value="">Semua Divisi</option>
                    @foreach($divisi_labels as $key => $label)
                    <option value="{{ $key }}" {{ request('divisi') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                Search
            </button>
            @if(request()->anyFilled(['search', 'divisi']))
            <a href="{{ route('rate-cards.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                Reset
            </a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Divisi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Sub Kategori</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama Layanan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Harga</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse ($rate_cards as $rc)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $badgeColors = [
                                'digital_marketing' => 'bg-blue-50 text-blue-700',
                                'keuangan_perpajakan' => 'bg-emerald-50 text-emerald-700',
                                'perizinan' => 'bg-purple-50 text-purple-700',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $badgeColors[$rc->divisi] ?? 'bg-slate-100 text-slate-700' }}">
                            {{ $rc->divisi_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                        {{ $rc->sub_kategori ?: '-' }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-slate-900">{{ $rc->nama_paket }}</div>
                        @if($rc->deskripsi)
                        <div class="text-xs text-slate-500 line-clamp-1" title="{{ $rc->deskripsi }}">{{ $rc->deskripsi }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                        Rp {{ number_format($rc->harga, 0, ',', '.') }} <span class="text-xs text-slate-500 font-normal">/ {{ $rc->satuan }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('rate-cards.edit', $rc) }}" class="text-emerald-600 hover:text-emerald-900 px-2 py-1 bg-emerald-50 hover:bg-emerald-100 rounded transition">Edit</a>
                            <form action="{{ route('rate-cards.destroy', $rc) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus rate card ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 px-2 py-1 bg-red-50 hover:bg-red-100 rounded transition">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                        <svg class="mx-auto h-12 w-12 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                        Belum ada data rate card.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($rate_cards->hasPages())
    <div class="px-4 py-3 border-t border-slate-200 sm:px-6">
        {{ $rate_cards->links() }}
    </div>
    @endif
</div>
@endsection
