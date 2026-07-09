@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Overview Header -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-lg font-bold text-slate-800 uppercase tracking-wide">Overview</h2>
    </div>
    <div class="flex items-center gap-3">
        <select class="text-sm border border-slate-200 rounded-xl px-4 py-2 bg-white text-slate-600 focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent/20 cursor-pointer">
            <option>Monthly</option>
            <option>Weekly</option>
            <option>Yearly</option>
        </select>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <!-- Total Pendapatan -->
    <div class="stat-card">
        <div class="flex items-center gap-4">
            <div class="stat-icon bg-gradient-to-br from-pink-100 to-pink-50 text-pink-500">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="stat-label">Total Pendapatan</p>
                <p class="stat-value text-pink-500">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Total Revenue (Invoices) -->
    <div class="stat-card">
        <div class="flex items-center gap-4">
            <div class="stat-icon bg-gradient-to-br from-blue-100 to-blue-50 text-blue-500">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="stat-label">Total Invoice</p>
                <p class="stat-value text-blue-600">{{ $totalInvoices }}</p>
            </div>
        </div>
        <div class="mt-3 flex gap-2 text-xs">
            <span class="stat-change positive">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                {{ $invoicesByStatus['dibayar'] ?? 0 }} Dibayar
            </span>
            <span class="text-slate-300">|</span>
            <span class="text-blue-500 font-semibold">{{ $invoicesByStatus['dikirim'] ?? 0 }} Dikirim</span>
        </div>
    </div>

    <!-- Active Clients -->
    <div class="stat-card">
        <div class="flex items-center gap-4">
            <div class="stat-icon bg-gradient-to-br from-emerald-100 to-emerald-50 text-emerald-500">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <p class="stat-label">Active Clients</p>
                <p class="stat-value text-emerald-600">{{ $totalClients }}</p>
            </div>
        </div>
    </div>

    <!-- Total Penawaran -->
    <div class="stat-card">
        <div class="flex items-center gap-4">
            <div class="stat-icon bg-gradient-to-br from-amber-100 to-amber-50 text-amber-500">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
            </div>
            <div>
                <p class="stat-label">Total Penawaran</p>
                <p class="stat-value text-amber-600">{{ $totalPenawaran }}</p>
            </div>
        </div>
        <div class="mt-3 flex gap-2 text-xs">
            <span class="stat-change positive">
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                {{ $penawaranByStatus['disetujui'] ?? 0 }} Disetujui
            </span>
            <span class="text-slate-300">|</span>
            <span class="text-slate-500 font-semibold">{{ $penawaranByStatus['draft'] ?? 0 }} Draft</span>
        </div>
    </div>
</div>

<!-- Tables Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Latest Invoices -->
    <div class="data-card">
        <div class="card-header">
            <h2>Invoice Terbaru</h2>
            <a href="{{ route('invoices.index') }}">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-xs text-slate-400 uppercase border-b border-slate-100">
                        <th class="px-6 py-3 font-semibold">No. Invoice</th>
                        <th class="px-6 py-3 font-semibold">Client</th>
                        <th class="px-6 py-3 font-semibold">Total</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($latestInvoices as $invoice)
                    <tr class="hover:bg-slate-50/50 transition duration-150">
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            <a href="{{ route('invoices.edit', $invoice) }}" class="hover:text-accent transition">{{ $invoice->nomor_invoice }}</a>
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $invoice->client->nama }}</td>
                        <td class="px-6 py-4 text-slate-800 font-semibold">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <x-status-badge :status="$invoice->status" />
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                            <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Belum ada invoice
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Latest Penawaran -->
    <div class="data-card">
        <div class="card-header">
            <h2>Penawaran Terbaru</h2>
            <a href="{{ route('penawaran.index') }}">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="text-xs text-slate-400 uppercase border-b border-slate-100">
                        <th class="px-6 py-3 font-semibold">No. Penawaran</th>
                        <th class="px-6 py-3 font-semibold">Client</th>
                        <th class="px-6 py-3 font-semibold">Total</th>
                        <th class="px-6 py-3 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($latestPenawaran as $quo)
                    <tr class="hover:bg-slate-50/50 transition duration-150">
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            <a href="{{ route('penawaran.edit', $quo) }}" class="hover:text-accent transition">{{ $quo->nomor_penawaran }}</a>
                        </td>
                        <td class="px-6 py-4 text-slate-500">{{ $quo->client->nama }}</td>
                        <td class="px-6 py-4 text-slate-800 font-semibold">Rp {{ number_format($quo->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <x-status-badge :status="$quo->status" />
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                            <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Belum ada penawaran
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="mt-8 pt-4 border-t border-slate-100">
    <p class="text-xs text-slate-400 text-center">&copy; {{ date('Y') }} SPT Invoice. All rights reserved.</p>
</div>
@endsection
