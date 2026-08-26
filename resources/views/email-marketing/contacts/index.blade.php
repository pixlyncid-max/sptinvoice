@extends('layouts.app')

@section('title', 'Daftar Kontak Email')

@section('actions')
<div class="flex flex-wrap gap-2" x-data>
    <a href="{{ route('email-marketing.contacts.template') }}" class="inline-flex items-center justify-center px-3.5 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50">
        <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
        Template Excel
    </a>
    <a href="{{ route('email-marketing.contacts.export') }}" class="inline-flex items-center justify-center px-3.5 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50">
        <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        Export Excel
    </a>
    <button type="button" @click="$dispatch('open-import-contact-modal')" class="inline-flex items-center justify-center px-3.5 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50">
        <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
        Import Kontak
    </button>
    <a href="{{ route('email-marketing.contacts.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark">
        + Tambah Kontak
    </a>
</div>
@endsection

@section('content')
<div x-data="{
    selected: [],
    selectAll: false,
    toggleAll() {
        if (this.selectAll) {
            this.selected = Array.from(document.querySelectorAll('.contact-checkbox')).map(el => el.value);
        } else {
            this.selected = [];
        }
    }
}">
    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Kontak</p>
                <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ number_format($totalContacts) }}</h3>
            </div>
            <div class="w-11 h-11 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Subscribed (Aktif)</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-1">{{ number_format($subscribedCount) }}</h3>
            </div>
            <div class="w-11 h-11 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Unsubscribed</p>
                <h3 class="text-2xl font-bold text-slate-500 mt-1">{{ number_format($unsubscribedCount) }}</h3>
            </div>
            <div class="w-11 h-11 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white shadow-sm rounded-lg border border-slate-200">
        <!-- Search & Filter Bar -->
        <div class="px-4 py-4 sm:p-5 border-b border-slate-200">
            <form action="{{ route('email-marketing.contacts.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" class="focus:ring-primary focus:border-primary block w-full pl-10 sm:text-sm border-slate-300 rounded-md py-2 px-3 border" placeholder="Cari nama, email, atau perusahaan...">
                    </div>
                </div>
                <div class="w-full sm:w-44">
                    <select name="subscription" class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border">
                        <option value="">Semua Status</option>
                        <option value="subscribed" {{ request('subscription') == 'subscribed' ? 'selected' : '' }}>Subscribed</option>
                        <option value="unsubscribed" {{ request('subscription') == 'unsubscribed' ? 'selected' : '' }}>Unsubscribed</option>
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                    Cari
                </button>
                @if(request('search') || request('subscription'))
                <a href="{{ route('email-marketing.contacts.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                    Reset
                </a>
                @endif
            </form>
        </div>

        <!-- Bulk Action Bar -->
        <div x-show="selected.length > 0" x-cloak class="bg-amber-50 px-4 py-3 border-b border-amber-200 flex items-center justify-between">
            <span class="text-sm font-medium text-amber-800">
                <span x-text="selected.length"></span> kontak dipilih
            </span>
            <form action="{{ route('email-marketing.contacts.bulk-delete') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua kontak yang dipilih?');">
                @csrf
                @method('DELETE')
                <template x-for="id in selected" :key="id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-semibold rounded shadow-sm text-white bg-red-600 hover:bg-red-700">
                    Hapus Kontak Terpilih
                </button>
            </form>
        </div>

        <!-- Contacts Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left w-10">
                            <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4">
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama / Email</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Perusahaan</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status Langganan</th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Ditambahkan</th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse ($contacts as $contact)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-4 whitespace-nowrap">
                            <input type="checkbox" :value="{{ $contact->id }}" x-model="selected" class="contact-checkbox rounded border-slate-300 text-primary focus:ring-primary h-4 w-4">
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-9 w-9 shrink-0 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold text-sm">
                                    {{ substr($contact->name, 0, 1) }}
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-semibold text-slate-900">{{ $contact->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $contact->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="text-sm text-slate-700">{{ $contact->company ?: '-' }}</span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <form action="{{ route('email-marketing.contacts.toggle-subscription', $contact) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" title="Klik untuk ubah status" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium cursor-pointer transition {{ $contact->is_subscribed ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                                    <span class="w-1.5 h-1.5 mr-1.5 rounded-full {{ $contact->is_subscribed ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                    {{ $contact->is_subscribed ? 'Subscribed' : 'Unsubscribed' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-xs text-slate-500">
                            {{ $contact->created_at ? $contact->created_at->format('d M Y') : '-' }}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('email-marketing.contacts.edit', $contact) }}" class="text-emerald-600 hover:text-emerald-900 px-2 py-1 bg-emerald-50 hover:bg-emerald-100 rounded text-xs transition">Edit</a>
                                <form action="{{ route('email-marketing.contacts.destroy', $contact) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kontak ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 px-2 py-1 bg-red-50 hover:bg-red-100 rounded text-xs transition">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <svg class="mx-auto h-12 w-12 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Belum ada kontak email. Klik <strong>+ Tambah Kontak</strong> atau <strong>Import Kontak</strong>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($contacts->hasPages())
        <div class="px-4 py-3 border-t border-slate-200 sm:px-6">
            {{ $contacts->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Import Kontak -->
<div x-data="{ open: false }" 
     x-on:open-import-contact-modal.window="open = true" 
     x-on:keydown.escape.window="open = false"
     x-show="open" 
     style="display: none;"
     class="fixed inset-0 z-50 overflow-y-auto" 
     role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
        <div x-show="open" class="fixed inset-0 bg-slate-900/50 transition-opacity z-40" @click="open = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

        <div x-show="open" class="relative z-50 inline-block bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form action="{{ route('email-marketing.contacts.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="bg-white px-5 pt-5 pb-4 sm:p-6">
                    <h3 class="text-lg font-semibold text-slate-900">Import Data Kontak (.xlsx / .csv)</h3>
                    <p class="text-xs text-slate-500 mt-1">Sistem otomatis mendeteksi format dan mengabaikan email duplikat.</p>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Pilih File Excel / CSV</label>
                        <input type="file" name="file_excel" accept=".xlsx, .xls, .csv" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary-dark" required>
                    </div>

                    <div class="mt-4 p-3 bg-blue-50 rounded-md border border-blue-100">
                        <p class="text-xs text-blue-700">
                            <strong>Tips:</strong> Unduh <a href="{{ route('email-marketing.contacts.template') }}" class="underline font-semibold">Template Excel</a> untuk memastikan header kolom sesuai (Nama, Email, Perusahaan, Subscribed).
                        </p>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary text-sm font-medium text-white hover:bg-primary-dark sm:w-auto">
                        Mulai Import
                    </button>
                    <button type="button" @click="open = false" class="mt-3 sm:mt-0 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 sm:w-auto">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
