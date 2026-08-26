@extends('layouts.app')

@section('title', 'Detail Campaign: ' . $campaign->name)

@section('actions')
<div class="flex items-center gap-2">
    <a href="{{ route('email-marketing.campaigns.index') }}" class="inline-flex items-center px-3.5 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
        &larr; Kembali
    </a>
    @if($stats['failed'] > 0)
    <form action="{{ route('email-marketing.campaigns.retry-failed', $campaign) }}" method="POST" class="inline" onsubmit="return confirm('Kirim ulang antrean untuk {{ $stats['failed'] }} email yang gagal?');">
        @csrf
        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-amber-600 hover:bg-amber-700 shadow-sm">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            Retry {{ $stats['failed'] }} Email Gagal
        </button>
    </form>
    @endif
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Campaign Header Card & Counters -->
    <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-xl font-bold text-slate-800">{{ $campaign->name }}</h2>
                    @if($campaign->status === 'completed')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Completed</span>
                    @elseif($campaign->status === 'sending')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 animate-pulse">Sending...</span>
                    @elseif($campaign->status === 'queued')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">Queued</span>
                    @elseif($campaign->status === 'failed')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Failed</span>
                    @else
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-800">{{ ucfirst($campaign->status) }}</span>
                    @endif
                </div>
                <p class="text-sm text-slate-600 mt-1"><strong>Subject:</strong> {{ $campaign->subject }}</p>
                <div class="flex flex-wrap gap-4 text-xs text-slate-500 mt-2">
                    <span><strong>Template:</strong> {{ $campaign->template ? $campaign->template->name : '-' }}</span>
                    <span>&bull;</span>
                    <span><strong>Dibuat oleh:</strong> {{ $campaign->creator ? $campaign->creator->name : 'System' }}</span>
                    <span>&bull;</span>
                    <span><strong>Waktu:</strong> {{ $campaign->created_at->format('d M Y H:i:s') }}</span>
                </div>
            </div>

            <!-- Progress Bar Info -->
            <div class="w-full md:w-64 bg-slate-50 p-3 rounded-lg border border-slate-200">
                <div class="flex justify-between text-xs font-semibold text-slate-700 mb-1.5">
                    <span>Status Progress</span>
                    <span>{{ $campaign->progress_percentage }}%</span>
                </div>
                <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-primary h-2.5 rounded-full transition-all duration-500" style="width: {{ $campaign->progress_percentage }}%"></div>
                </div>
                <p class="text-[11px] text-slate-500 mt-1 text-center">{{ $stats['sent'] + $stats['failed'] }} dari {{ $stats['total'] }} diproses</p>
            </div>
        </div>

        <!-- 4 Statistics Counter Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6">
            <div class="p-4 bg-slate-50 rounded-lg border border-slate-100 text-center">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Total</span>
                <span class="text-2xl font-black text-slate-800 mt-1 block">{{ number_format($stats['total']) }}</span>
            </div>
            <div class="p-4 bg-amber-50 rounded-lg border border-amber-100 text-center">
                <span class="text-xs font-semibold text-amber-700 uppercase tracking-wider block">Queued (Antrean)</span>
                <span class="text-2xl font-black text-amber-800 mt-1 block">{{ number_format($stats['queued']) }}</span>
            </div>
            <div class="p-4 bg-emerald-50 rounded-lg border border-emerald-100 text-center">
                <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wider block">Sent (Terkirim)</span>
                <span class="text-2xl font-black text-emerald-800 mt-1 block">{{ number_format($stats['sent']) }}</span>
            </div>
            <div class="p-4 bg-red-50 rounded-lg border border-red-100 text-center">
                <span class="text-xs font-semibold text-red-700 uppercase tracking-wider block">Failed (Gagal)</span>
                <span class="text-2xl font-black text-red-800 mt-1 block">{{ number_format($stats['failed']) }}</span>
            </div>
        </div>
    </div>

    <!-- Recipients List Card -->
    <div class="bg-white shadow-sm rounded-lg border border-slate-200">
        <div class="px-5 py-4 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-3">
            <h3 class="text-base font-bold text-slate-800">Daftar Penerima & Status Pengiriman</h3>

            <form action="{{ route('email-marketing.campaigns.show', $campaign) }}" method="GET" class="flex gap-2 w-full sm:w-auto">
                <select name="status" class="text-xs rounded-md border-slate-300 py-1.5 px-3 border" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending / Queued</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="skipped" {{ request('status') == 'skipped' ? 'selected' : '' }}>Skipped</option>
                </select>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari email..." class="text-xs rounded-md border-slate-300 py-1.5 px-3 border">
                <button type="submit" class="text-xs px-3 py-1.5 bg-slate-100 hover:bg-slate-200 rounded border border-slate-300 text-slate-700 font-medium">
                    Filter
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Email Penerima</th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama Kontak</th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Percobaan</th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Waktu Terkirim</th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Keterangan / Error</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse ($recipients as $recipient)
                    <tr class="hover:bg-slate-50 text-xs">
                        <td class="px-5 py-3 font-semibold text-slate-900 whitespace-nowrap">
                            {{ $recipient->email }}
                        </td>
                        <td class="px-5 py-3 text-slate-600 whitespace-nowrap">
                            {{ $recipient->contact ? $recipient->contact->name : '-' }}
                            @if($recipient->contact && $recipient->contact->company)
                                <span class="text-slate-400">({{ $recipient->contact->company }})</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            @if($recipient->status === 'sent')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full font-medium bg-emerald-100 text-emerald-800">
                                    Sent
                                </span>
                            @elseif($recipient->status === 'failed')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full font-medium bg-red-100 text-red-800">
                                    Failed
                                </span>
                            @elseif($recipient->status === 'skipped')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full font-medium bg-slate-100 text-slate-700">
                                    Skipped
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full font-medium bg-amber-100 text-amber-800">
                                    Pending / Queued
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-slate-500 whitespace-nowrap">
                            {{ $recipient->attempts }}x
                        </td>
                        <td class="px-5 py-3 text-slate-500 whitespace-nowrap">
                            {{ $recipient->sent_at ? $recipient->sent_at->format('d M Y H:i:s') : '-' }}
                        </td>
                        <td class="px-5 py-3 text-red-600 max-w-xs truncate" title="{{ $recipient->error_message }}">
                            {{ $recipient->error_message ?: '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-400 text-xs">
                            Tidak ada data penerima yang sesuai filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($recipients->hasPages())
        <div class="px-5 py-3 border-t border-slate-200">
            {{ $recipients->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
