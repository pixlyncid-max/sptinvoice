@extends('layouts.app')

@section('title', 'Buat Campaign Email Baru')

@section('content')
<div class="max-w-4xl" x-data="{
    campaignName: @js(old('name', '')),
    templateId: @js(old('template_id', '')),
    subject: @js(old('subject', '')),
    recipientType: @js(old('recipient_type', 'all_subscribed')),
    selectedContacts: @js(old('selected_contacts', [])),
    totalSubscribed: {{ $totalSubscribed }},
    templates: @js($templates->map(function($t) { return ['id' => (string)$t->id, 'name' => $t->name, 'subject' => $t->subject ?? '', 'body' => $t->body ?? '']; })),
    contactSearch: '',

    previewModalOpen: false,
    confirmModalOpen: false,
    previewSubject: '',
    previewBody: '',

    get recipientCount() {
        if (this.recipientType === 'all_subscribed') {
            return this.totalSubscribed;
        }
        return this.selectedContacts.length;
    },

    onTemplateChange() {
        const found = this.templates.find(t => String(t.id) === String(this.templateId));
        if (found && (!this.subject || this.subject.trim() === '')) {
            this.subject = found.subject || '';
        }
    },

    openPreview() {
        const found = this.templates.find(t => String(t.id) === String(this.templateId));
        if (!found) {
            alert('Silakan pilih template terlebih dahulu.');
            return;
        }

        const sampleName = 'Budi Santoso';
        const sampleEmail = 'budi@example.com';
        const sampleCompany = 'PT Maju Bersama';
        const sampleUnsubscribe = '{{ url('/email/unsubscribe/sample-token') }}';

        let sub = this.subject || found.subject || '';
        let bod = found.body || '';

        sub = sub.replace(/\{\{name\}\}/g, sampleName)
                 .replace(/\{\{email\}\}/g, sampleEmail)
                 .replace(/\{\{company\}\}/g, sampleCompany)
                 .replace(/\{\{unsubscribe_url\}\}/g, sampleUnsubscribe);

        bod = bod.replace(/\{\{name\}\}/g, sampleName)
                 .replace(/\{\{email\}\}/g, sampleEmail)
                 .replace(/\{\{company\}\}/g, sampleCompany)
                 .replace(/\{\{unsubscribe_url\}\}/g, sampleUnsubscribe);

        this.previewSubject = sub;
        this.previewBody = bod;
        this.previewModalOpen = true;
    },

    openConfirm() {
        if (!this.campaignName || this.campaignName.trim() === '') {
            alert('Nama campaign wajib diisi.');
            return;
        }
        if (!this.templateId) {
            alert('Template email wajib dipilih.');
            return;
        }
        if (!this.subject || this.subject.trim() === '') {
            alert('Subject email wajib diisi.');
            return;
        }
        if (this.recipientCount === 0) {
            alert('Belum ada kontak penerima yang dipilih atau kontak berstatus subscribed masih 0. Tambahkan kontak terlebih dahulu.');
            return;
        }

        this.confirmModalOpen = true;
    }
}">
    @if($templates->isEmpty())
    <div class="mb-5 p-4 bg-amber-50 border border-amber-300 rounded-lg text-sm text-amber-800 flex items-center justify-between">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span>Anda belum memiliki <strong>Template Email</strong>. Buat template terlebih dahulu sebelum membuat campaign.</span>
        </div>
        <a href="{{ route('email-marketing.templates.create') }}" class="px-3 py-1 bg-amber-600 hover:bg-amber-700 text-white rounded text-xs font-semibold whitespace-nowrap">
            + Buat Template
        </a>
    </div>
    @endif

    @if($totalSubscribed === 0)
    <div class="mb-5 p-4 bg-blue-50 border border-blue-300 rounded-lg text-sm text-blue-800 flex items-center justify-between">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>Belum ada kontak aktif (Subscribed). Tambahkan kontak atau lakukan import data kontak terlebih dahulu.</span>
        </div>
        <a href="{{ route('email-marketing.contacts.create') }}" class="px-3 py-1 bg-primary hover:bg-primary-dark text-white rounded text-xs font-semibold whitespace-nowrap">
            + Tambah Kontak
        </a>
    </div>
    @endif

    <form id="campaignForm" action="{{ route('email-marketing.campaigns.store') }}" method="POST">
        @csrf

        <div class="bg-white shadow-sm rounded-lg border border-slate-200 p-6 space-y-6">
            <!-- Step 1: Info Dasar -->
            <div>
                <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4 flex items-center">
                    <span class="w-6 h-6 rounded-full bg-primary text-white text-xs flex items-center justify-center mr-2">1</span>
                    Informasi Campaign
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Campaign <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" x-model="campaignName" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm py-2 px-3 border" placeholder="e.g. Broadcast Promo Bulan Ini">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="template_id" class="block text-sm font-medium text-slate-700 mb-1">Pilih Template Email <span class="text-red-500">*</span></label>
                        <select name="template_id" id="template_id" x-model="templateId" @change="onTemplateChange()" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm py-2 px-3 border">
                            <option value="">-- Pilih Template --</option>
                            @foreach ($templates as $template)
                            <option value="{{ $template->id }}" {{ old('template_id') == $template->id ? 'selected' : '' }}>{{ $template->name }}</option>
                            @endforeach
                        </select>
                        @error('template_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <label for="subject" class="block text-sm font-medium text-slate-700 mb-1">Subject Email <span class="text-red-500">*</span></label>
                    <input type="text" name="subject" id="subject" x-model="subject" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm py-2 px-3 border" placeholder="e.g. Penawaran Spesial untuk {{company}}">
                    <p class="text-xs text-slate-400 mt-1">Mendukung tag @verbatim{{name}}@endverbatim dan @verbatim{{company}}@endverbatim.</p>
                    @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Step 2: Target Penerima -->
            <div>
                <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-2 mb-4 flex items-center">
                    <span class="w-6 h-6 rounded-full bg-primary text-white text-xs flex items-center justify-center mr-2">2</span>
                    Target Penerima (Recipients)
                </h3>

                <div class="space-y-3">
                    <div class="flex items-center gap-6">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="recipient_type" value="all_subscribed" x-model="recipientType" class="text-primary focus:ring-primary h-4 w-4">
                            <span class="ml-2 text-sm font-medium text-slate-700">Semua Kontak Aktif (Subscribed)</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="recipient_type" value="custom" x-model="recipientType" class="text-primary focus:ring-primary h-4 w-4">
                            <span class="ml-2 text-sm font-medium text-slate-700">Pilih Kontak Tertentu</span>
                        </label>
                    </div>

                    <!-- Recipient Counter Badge -->
                    <div class="p-3 bg-blue-50 border border-blue-100 rounded-md flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-xs text-blue-800 font-medium">Total Penerima Terpilih:</span>
                        </div>
                        <span class="text-sm font-bold text-blue-900 bg-white px-3 py-0.5 rounded-full border border-blue-200" x-text="recipientCount + ' Kontak'"></span>
                    </div>

                    <!-- Custom Contacts Selection Box -->
                    <div x-show="recipientType === 'custom'" x-cloak class="mt-3 border border-slate-200 rounded-lg p-4 bg-slate-50">
                        <div class="mb-3 flex items-center justify-between">
                            <input type="text" x-model="contactSearch" placeholder="Cari kontak..." class="text-xs rounded border-slate-300 py-1.5 px-3 w-64 border">
                            <div class="text-xs space-x-2">
                                <button type="button" @click="selectedContacts = {{ json_encode($subscribedContacts->pluck('id')->toArray()) }}" class="text-primary hover:underline font-medium">Pilih Semua</button>
                                <button type="button" @click="selectedContacts = []" class="text-slate-500 hover:underline">Hapus Pilihan</button>
                            </div>
                        </div>

                        <div class="max-h-60 overflow-y-auto space-y-1.5 bg-white p-3 rounded border border-slate-200">
                            @forelse ($subscribedContacts as $contact)
                            <label class="flex items-center p-1.5 hover:bg-slate-50 rounded cursor-pointer text-xs" x-show="contactSearch === '' || '{{ strtolower($contact->name . ' ' . $contact->email . ' ' . $contact->company) }}'.includes(contactSearch.toLowerCase())">
                                <input type="checkbox" name="selected_contacts[]" value="{{ $contact->id }}" x-model="selectedContacts" class="rounded border-slate-300 text-primary focus:ring-primary h-3.5 w-3.5 mr-2.5">
                                <span class="font-medium text-slate-800">{{ $contact->name }}</span>
                                <span class="text-slate-400 mx-1.5">&bull;</span>
                                <span class="text-slate-500">{{ $contact->email }}</span>
                                @if($contact->company)
                                <span class="text-slate-400 mx-1.5">&bull;</span>
                                <span class="text-slate-600 font-mono text-[11px]">({{ $contact->company }})</span>
                                @endif
                            </label>
                            @empty
                            <p class="text-xs text-slate-400 py-3 text-center">Belum ada kontak aktif.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Bar: Preview & Kirim -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('email-marketing.campaigns.index') }}" class="px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                    Batal
                </a>

                <div class="flex items-center gap-3">
                    <button type="button" @click="openPreview()" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 shadow-sm">
                        <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Preview Email
                    </button>
                    <button type="button" @click="openConfirm()" class="inline-flex items-center px-5 py-2 border border-transparent text-sm font-semibold rounded-md text-white bg-primary hover:bg-primary-dark shadow-sm">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Kirim Campaign
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Preview Modal -->
    <div x-show="previewModalOpen" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div x-show="previewModalOpen" class="fixed inset-0 bg-slate-900/50 transition-opacity z-40" @click="previewModalOpen = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="previewModalOpen" class="relative z-50 inline-block bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-slate-100 px-5 py-3 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-800">Preview Tampilan Email</h3>
                    <button type="button" @click="previewModalOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>
                <div class="p-6 bg-slate-50">
                    <div class="bg-white p-3 rounded border border-slate-200 mb-3">
                        <span class="text-xs text-slate-500 font-semibold block mb-0.5">Subject:</span>
                        <div class="text-sm font-medium text-slate-900" x-text="previewSubject"></div>
                    </div>
                    <div class="bg-white p-6 rounded border border-slate-200 min-h-[220px]" x-html="previewBody"></div>
                    <p class="text-[11px] text-slate-400 mt-2">* Ditampilkan menggunakan data simulasi contoh penerima.</p>
                </div>
                <div class="bg-white px-5 py-3 border-t border-slate-200 text-right">
                    <button type="button" @click="previewModalOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirm Send Modal -->
    <div x-show="confirmModalOpen" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div x-show="confirmModalOpen" class="fixed inset-0 bg-slate-900/50 transition-opacity z-40" @click="confirmModalOpen = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="confirmModalOpen" class="relative z-50 inline-block bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="p-6 bg-white">
                    <div class="w-12 h-12 rounded-full bg-blue-50 text-primary flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 text-center">Konfirmasi Pengiriman</h3>
                    <p class="text-xs text-slate-500 text-center mt-1">Periksa kembali detail campaign sebelum dimasukkan ke antrean:</p>

                    <div class="mt-4 p-3 bg-slate-50 rounded-lg text-xs space-y-2 border border-slate-100">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Campaign:</span>
                            <span class="font-semibold text-slate-800" x-text="campaignName"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Subject:</span>
                            <span class="font-semibold text-slate-800 truncate max-w-[200px]" x-text="subject"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Total Penerima:</span>
                            <span class="font-bold text-primary" x-text="recipientCount + ' Kontak'"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Metode Pengiriman:</span>
                            <span class="text-slate-700">Individual via Laravel Queue</span>
                        </div>
                    </div>

                    <p class="text-[11px] text-slate-400 mt-3 text-center">Setiap email dikirim satu per satu dengan jeda aman sesuai limit SMTP.</p>
                </div>

                <div class="bg-slate-50 px-5 py-3 border-t border-slate-200 flex justify-end gap-2">
                    <button type="button" @click="confirmModalOpen = false" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 text-xs font-semibold rounded hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="button" @click="confirmModalOpen = false; document.getElementById('campaignForm').submit()" class="px-5 py-2 bg-primary text-white text-xs font-semibold rounded hover:bg-primary-dark shadow-sm">
                        Ya, Kirim Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
