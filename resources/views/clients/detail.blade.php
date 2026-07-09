@extends('layouts.app')

@section('title', 'Detail Client: ' . $client->nama)

@section('actions')
<div class="flex gap-2">
    <a href="{{ route('clients.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50">
        Kembali
    </a>
    <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark">
        Edit Client
    </a>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kolom Kiri: Info Client -->
    <div class="col-span-1">
        <div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 px-4 py-5 border-b border-slate-200 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-slate-900">Informasi Client</h3>
            </div>
            <div class="px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-slate-200">
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-slate-500">Nama Lengkap</dt>
                        <dd class="mt-1 text-sm text-slate-900 sm:mt-0 sm:col-span-2 font-medium">{{ $client->nama }}</dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-slate-500">Perusahaan</dt>
                        <dd class="mt-1 text-sm text-slate-900 sm:mt-0 sm:col-span-2">{{ $client->perusahaan ?: '-' }}</dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-slate-500">Email</dt>
                        <dd class="mt-1 text-sm text-slate-900 sm:mt-0 sm:col-span-2">
                            <a href="mailto:{{ $client->email }}" class="text-blue-600 hover:underline">{{ $client->email }}</a>
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-slate-500">Telepon</dt>
                        <dd class="mt-1 text-sm text-slate-900 sm:mt-0 sm:col-span-2">{{ $client->telepon ?: '-' }}</dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-slate-500">Alamat</dt>
                        <dd class="mt-1 text-sm text-slate-900 sm:mt-0 sm:col-span-2">{{ $client->alamat ?: '-' }}</dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-slate-500">Bergabung Sejak</dt>
                        <dd class="mt-1 text-sm text-slate-900 sm:mt-0 sm:col-span-2">{{ $client->created_at->format('d M Y') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Dokumen Terkait -->
    <div class="col-span-1 lg:col-span-2 space-y-6">
        
        <!-- Invoices -->
        <div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 px-4 py-4 border-b border-slate-200 flex justify-between items-center sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-slate-900">Riwayat Invoice</h3>
                <a href="{{ route('invoices.create', ['client_id' => $client->id]) }}" class="text-sm text-primary hover:text-primary-dark font-medium">+ Buat Invoice</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Nomor</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Total</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse ($client->invoices as $invoice)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('invoices.edit', $invoice) }}" class="text-primary hover:text-primary-dark">{{ $invoice->nomor_invoice }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $invoice->tanggal_invoice->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 font-medium">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <x-status-badge :status="$invoice->status" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus invoice ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-slate-500">Belum ada invoice untuk client ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Penawaran -->
        <div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
            <div class="bg-slate-50 px-4 py-4 border-b border-slate-200 flex justify-between items-center sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-slate-900">Riwayat Penawaran</h3>
                <a href="{{ route('penawaran.create', ['client_id' => $client->id]) }}" class="text-sm text-primary hover:text-primary-dark font-medium">+ Buat Penawaran</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Nomor</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Tanggal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Total</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse ($client->penawaran as $quo)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="{{ route('penawaran.edit', $quo) }}" class="text-primary hover:text-primary-dark">{{ $quo->nomor_penawaran }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $quo->tanggal->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900 font-medium">Rp {{ number_format($quo->total, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <x-status-badge :status="$quo->status" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <form action="{{ route('penawaran.destroy', $quo) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus penawaran ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-slate-500">Belum ada penawaran untuk client ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
