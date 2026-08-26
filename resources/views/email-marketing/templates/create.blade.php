@extends('layouts.app')

@section('title', 'Buat Template Email')

@section('content')
<div class="max-w-4xl bg-white shadow-sm rounded-lg border border-slate-200 p-6" x-data="{
    bodyText: @js(old('body', $defaultBody ?? '')),
    insertTag(tag) {
        const textarea = document.getElementById('template_body');
        if (!textarea) return;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        this.bodyText = text.substring(0, start) + tag + text.substring(end);
        textarea.value = this.bodyText;
        textarea.focus();
        setTimeout(() => {
            textarea.setSelectionRange(start + tag.length, start + tag.length);
        }, 50);
    }
}">
    <form action="{{ route('email-marketing.templates.store') }}" method="POST">
        @csrf

        <div class="space-y-5">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Template <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm py-2 px-3 border" placeholder="e.g. Informasi Penawaran Khusus">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="subject" class="block text-sm font-medium text-slate-700 mb-1">Subject Default (Opsional)</label>
                <input type="text" name="subject" id="subject" value="{{ old('subject') }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm py-2 px-3 border" placeholder="e.g. Update Layanan untuk @{{company}}">
                <p class="text-xs text-slate-500 mt-1">Dapat diubah kembali saat membuat Campaign.</p>
                @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5 flex-wrap gap-2">
                    <label for="template_body" class="block text-sm font-medium text-slate-700">Isi Email (HTML / Teks) <span class="text-red-500">*</span></label>
                    
                    <!-- Variable Insert Buttons -->
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-xs text-slate-500 font-medium mr-1">Klik untuk sisipkan:</span>
                        <button type="button" @click="insertTag('@{{name}}')" class="px-2 py-0.5 text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-mono rounded border border-slate-300 transition" title="Nama Kontak">
                            @{{name}}
                        </button>
                        <button type="button" @click="insertTag('@{{company}}')" class="px-2 py-0.5 text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-mono rounded border border-slate-300 transition" title="Nama Perusahaan">
                            @{{company}}
                        </button>
                        <button type="button" @click="insertTag('@{{email}}')" class="px-2 py-0.5 text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 font-mono rounded border border-slate-300 transition" title="Alamat Email">
                            @{{email}}
                        </button>
                        <button type="button" @click="insertTag('@{{unsubscribe_url}}')" class="px-2 py-0.5 text-xs bg-amber-50 hover:bg-amber-100 text-amber-800 font-mono rounded border border-amber-300 transition" title="Link Unsubscribe">
                            @{{unsubscribe_url}}
                        </button>
                    </div>
                </div>

                <textarea name="body" id="template_body" rows="12" required x-model="bodyText" class="font-mono text-sm w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary py-2 px-3 border leading-relaxed"></textarea>
                @error('body') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="p-4 bg-slate-50 rounded-md border border-slate-200 text-xs text-slate-600 space-y-1">
                <p class="font-semibold text-slate-700">Panduan Template:</p>
                <ul class="list-disc list-inside space-y-0.5 pl-1">
                    <li>Gunakan <code>@{{name}}</code> untuk menyapa penerima dengan nama mereka.</li>
                    <li>Gunakan <code>@{{company}}</code> untuk menampilkan nama perusahaan penerima.</li>
                    <li>Gunakan <code>@{{unsubscribe_url}}</code> pada tag <code>&lt;a href="@{{unsubscribe_url}}"&gt;Unsubscribe&lt;/a&gt;</code> untuk kepatuhan anti-spam.</li>
                    <li>Mendukung format HTML sederhana seperti <code>&lt;strong&gt;</code>, <code>&lt;p&gt;</code>, <code>&lt;br&gt;</code>, <code>&lt;a&gt;</code>, <code>&lt;ul&gt;</code>, dll.</li>
                </ul>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('email-marketing.templates.index') }}" class="px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark">
                Simpan Template
            </button>
        </div>
    </form>
</div>
@endsection
