@extends('layouts.app')

@section('title', 'Riwayat Log Email')

@section('actions')
@if($totalLogs > 0)
<form action="{{ route('email-marketing.logs.clear') }}" method="POST" class="inline" onsubmit="return confirm('Bersihkan seluruh catatan riwayat log email?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="inline-flex items-center px-3.5 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
        <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        Bersihkan Log
    </button>
</form>
@endif
@endsection

@section('content')
<div>
    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Log</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalLogs) }}</h3>
            </div>
            <div class="w-11 h-11 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Berhasil Terkirim</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-1">{{ number_format($sentLogs) }}</h3>
            </div>
            <div class="w-11 h-11 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
        </div>
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Gagal</p>
                <h3 class="text-2xl font-bold text-red-600 mt-1">{{ number_format($failedLogs) }}</h3>
            </div>
            <div class="w-11 h-11 rounded-full bg-red-50 flex items-center justify-center text-red-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white shadow-sm rounded-lg border border-slate-200">
        <!-- Search & Filter Bar -->
        <div class="px-4 py-4 sm:p-5 border-b border-slate-200">
            <form action="{{ route('email-marketing.logs.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" class="focus:ring-primary focus:border-primary block w-full pl-10 sm:text-sm border-slate-300 rounded-md py-2 px-3 border" placeholder="Cari email penerima atau subject...">
                    </div>
                </div>
                <div class="w-full sm:w-44">
                    <select name="status" class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border">
                        <option value="">Semua Status</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent (Berhasil)</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed (Gagal)</option>
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                    Cari
                </button>
                @if(request('search') || request('status'))
                <a href="{{ route('email-marketing.logs.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                    Reset
                </a>
                @endif
            </form>
        </div>

        <!-- Logs Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Penerima</th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Subject & Campaign</th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Waktu Terkirim</th>
                        <th scope="col" class="px-5 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Keterangan Error</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse ($logs as $log)
                    <tr class="hover:bg-slate-50 text-xs">
                        <td class="px-5 py-3.5 whitespace-nowrap font-semibold text-slate-900">
                            {{ $log->recipient_email }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-medium text-slate-800">{{ $log->subject }}</div>
                            @if($log->campaign)
                            <a href="{{ route('email-marketing.campaigns.show', $log->campaign) }}" class="text-[11px] text-primary hover:underline">
                                {{ $log->campaign->name }}
                            </a>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @if($log->status === 'sent')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full font-medium bg-emerald-100 text-emerald-800">
                                    Sent
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full font-medium bg-red-100 text-red-800">
                                    Failed
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap text-slate-500">
                            {{ $log->sent_at ? $log->sent_at->format('d M Y H:i:s') : '-' }}
                        </td>
                        <td class="px-5 py-3.5 text-red-600 max-w-xs truncate" title="{{ $log->error_message }}">
                            {{ $log->error_message ?: '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <svg class="mx-auto h-12 w-12 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Belum ada riwayat log pengiriman email.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="px-4 py-3 border-t border-slate-200 sm:px-6">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
