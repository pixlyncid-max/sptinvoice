@extends('layouts.app')

@section('title', 'Template Email')

@section('actions')
<a href="{{ route('email-marketing.templates.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark">
    + Tambah Template
</a>
@endsection

@section('content')
<div x-data="{
    previewOpen: false,
    previewTitle: '',
    previewSubject: '',
    previewBody: '',
    openPreview(name, subject, bodyUrl) {
        this.previewTitle = name;
        this.previewSubject = 'Loading...';
        this.previewBody = '<div class=\'text-center py-6 text-slate-400\'>Memuat preview...</div>';
        this.previewOpen = true;

        fetch(bodyUrl)
            .then(res => res.json())
            .then(data => {
                this.previewSubject = data.subject || '(Tanpa Subject)';
                this.previewBody = data.body || '';
            })
            .catch(() => {
                this.previewBody = '<div class=\'text-red-500 text-center py-6\'>Gagal memuat preview.</div>';
            });
    }
}">
    <!-- Search Bar -->
    <div class="bg-white shadow-sm rounded-lg border border-slate-200 mb-6 p-4">
        <form action="{{ route('email-marketing.templates.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" class="focus:ring-primary focus:border-primary block w-full pl-10 sm:text-sm border-slate-300 rounded-md py-2 px-3 border" placeholder="Cari nama template atau subject...">
                </div>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                Cari
            </button>
            @if(request('search'))
            <a href="{{ route('email-marketing.templates.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($templates as $template)
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm flex flex-col justify-between hover:shadow-md transition">
            <div class="p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">{{ $template->name }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Subject: {{ $template->subject ?: '(Belum diatur)' }}</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-700">
                        {{ $template->creator ? $template->creator->name : 'System' }}
                    </span>
                </div>

                <div class="mt-4 p-3 bg-slate-50 rounded border border-slate-100 text-xs text-slate-600 line-clamp-4 font-mono whitespace-pre-wrap">
                    {{ Str::limit(strip_tags($template->body), 180) }}
                </div>

                <div class="mt-3 flex items-center justify-between text-xs text-slate-400">
                    <span>Diperbarui: {{ $template->updated_at->format('d M Y H:i') }}</span>
                </div>
            </div>

            <div class="bg-slate-50 px-5 py-3 border-t border-slate-200 flex items-center justify-between rounded-b-lg">
                <button type="button" @click="openPreview('{{ addslashes($template->name) }}', '{{ addslashes($template->subject) }}', '{{ route('email-marketing.templates.preview', $template) }}')" class="inline-flex items-center text-xs font-semibold text-primary hover:text-primary-dark">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Preview
                </button>

                <div class="flex items-center gap-2">
                    <form action="{{ route('email-marketing.templates.duplicate', $template) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-slate-600 hover:text-slate-900 text-xs px-2 py-1 bg-white border border-slate-200 rounded hover:bg-slate-100 transition" title="Duplikasi">
                            Duplikat
                        </button>
                    </form>
                    <a href="{{ route('email-marketing.templates.edit', $template) }}" class="text-emerald-600 hover:text-emerald-900 text-xs px-2 py-1 bg-emerald-50 hover:bg-emerald-100 rounded transition">
                        Edit
                    </a>
                    <form action="{{ route('email-marketing.templates.destroy', $template) }}" method="POST" class="inline" onsubmit="return confirm('Hapus template ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-900 text-xs px-2 py-1 bg-red-50 hover:bg-red-100 rounded transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-white p-12 text-center rounded-lg border border-slate-200 text-slate-500">
            <svg class="mx-auto h-12 w-12 text-slate-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            Belum ada template email. Klik <strong>+ Tambah Template</strong> untuk membuat template baru.
        </div>
        @endforelse
    </div>

    @if($templates->hasPages())
    <div class="mt-6">
        {{ $templates->links() }}
    </div>
    @endif

    <!-- Preview Modal -->
    <div x-show="previewOpen" x-cloak style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
            <div x-show="previewOpen" class="fixed inset-0 bg-slate-900/50 transition-opacity z-40" @click="previewOpen = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div x-show="previewOpen" class="relative z-50 inline-block bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-slate-100 px-5 py-3 border-b border-slate-200 flex justify-between items-center">
                    <h3 class="text-sm font-bold text-slate-800" x-text="'Preview: ' + previewTitle"></h3>
                    <button type="button" @click="previewOpen = false" class="text-slate-400 hover:text-slate-600">&times;</button>
                </div>
                <div class="p-6 bg-slate-50">
                    <div class="bg-white p-4 rounded border border-slate-200 mb-4">
                        <span class="text-xs text-slate-500 font-semibold block mb-1">Subject:</span>
                        <div class="text-sm font-medium text-slate-900" x-text="previewSubject"></div>
                    </div>
                    <div class="bg-white p-6 rounded border border-slate-200 min-h-[250px]" x-html="previewBody"></div>
                    <p class="text-xs text-slate-400 mt-2">* Variabel dinamis otomatis diganti dengan data contoh kontak.</p>
                </div>
                <div class="bg-white px-5 py-3 border-t border-slate-200 text-right">
                    <button type="button" @click="previewOpen = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
