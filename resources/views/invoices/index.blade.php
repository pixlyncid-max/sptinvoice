@extends('layouts.app')

@section('title', 'Daftar Invoice')

@section('actions')
<a href="{{ route('invoices.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
    + Buat Invoice
</a>
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-lg border border-slate-200">
    <div class="px-4 py-5 sm:p-6 border-b border-slate-200">
        <form action="{{ route('invoices.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" /></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="focus:ring-primary focus:border-primary block w-full pl-10 sm:text-sm border-slate-300 rounded-md py-2 px-3 border" placeholder="Cari nomor invoice atau client...">
                </div>
            </div>
            <div>
                <select name="status" class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="dikirim" {{ request('status') == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                    <option value="dibayar" {{ request('status') == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                    <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                </select>
            </div>
            <div class="flex gap-2 items-center">
                <input type="date" name="dari" value="{{ request('dari') }}" class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border">
                <span class="text-slate-500">-</span>
                <input type="date" name="sampai" value="{{ request('sampai') }}" class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border">
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                Search
            </button>
            @if(request()->anyFilled(['search', 'status', 'dari', 'sampai']))
            <a href="{{ route('invoices.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                Reset
            </a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nomor</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Client</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Tanggal</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Total</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse ($invoices as $invoice)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('invoices.edit', $invoice) }}" class="text-sm font-medium text-primary hover:text-primary-dark">{{ $invoice->nomor_invoice }}</a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-slate-900">{{ $invoice->client->nama }}</div>
                        <div class="text-xs text-slate-500">{{ $invoice->client->perusahaan }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-slate-900">{{ $invoice->tanggal_invoice->format('d M Y') }}</div>
                        <div class="text-xs text-slate-500">Jatuh Tempo: {{ $invoice->tanggal_jatuh_tempo->format('d M Y') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                        Rp {{ number_format($invoice->total, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div x-data="{ open: false }" class="relative inline-block text-left">
                            <button @click="open = !open" @click.away="open = false" class="focus:outline-none flex items-center">
                                <x-status-badge :status="$invoice->status" />
                                <svg class="ml-1 h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                            </button>
                            <div x-show="open" x-cloak class="origin-top-left absolute left-0 mt-2 w-32 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10">
                                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                    <form action="{{ route('invoices.status', $invoice) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" name="status" value="draft" class="block w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 hover:text-slate-900" role="menuitem">Draft</button>
                                        <button type="submit" name="status" value="dikirim" class="block w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 hover:text-slate-900" role="menuitem">Dikirim</button>
                                        <button type="submit" name="status" value="dibayar" class="block w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 hover:text-slate-900" role="menuitem">Dibayar</button>
                                        <button type="submit" name="status" value="batal" class="block w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-100 hover:text-slate-900" role="menuitem">Batal</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('invoices.print', $invoice) }}" target="_blank" class="text-slate-600 hover:text-slate-900 px-2 py-1 bg-slate-100 hover:bg-slate-200 rounded transition" title="Print/Preview">Cetak</a>
                            <a href="{{ route('invoices.pdf', $invoice) }}" class="text-emerald-600 hover:text-emerald-900 px-2 py-1 bg-emerald-50 hover:bg-emerald-100 rounded transition" title="Download PDF">PDF</a>
                            <a href="{{ route('invoices.edit', $invoice) }}" class="text-blue-600 hover:text-blue-900 px-2 py-1 bg-blue-50 hover:bg-blue-100 rounded transition">Edit</a>
                            
                            <form action="{{ route('invoices.duplicate', $invoice) }}" method="POST" class="inline" onsubmit="return confirm('Buat duplikat invoice ini?');">
                                @csrf
                                <button type="submit" class="text-amber-600 hover:text-amber-900 px-2 py-1 bg-amber-50 hover:bg-amber-100 rounded transition" title="Duplicate">Copy</button>
                            </form>
                            
                            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus invoice ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 px-2 py-1 bg-red-50 hover:bg-red-100 rounded transition">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-slate-500">
                        <svg class="mx-auto h-12 w-12 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Belum ada data invoice.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($invoices->hasPages())
    <div class="px-4 py-3 border-t border-slate-200 sm:px-6">
        {{ $invoices->links() }}
    </div>
    @endif
</div>
@endsection
