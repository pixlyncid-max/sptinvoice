@extends('layouts.app')

@section('title', 'Preview Template: ' . $template->name)

@section('actions')
<a href="{{ route('email-marketing.templates.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
    &larr; Kembali ke Daftar Template
</a>
@endsection

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-sm rounded-lg border border-slate-200 p-6 space-y-6">
    <div class="border-b border-slate-200 pb-4">
        <h2 class="text-lg font-bold text-slate-800">{{ $template->name }}</h2>
        <div class="mt-2 p-3 bg-slate-50 rounded border border-slate-200">
            <span class="text-xs text-slate-500 font-semibold block">Subject:</span>
            <div class="text-sm font-semibold text-slate-900 mt-0.5">{{ $rendered['subject'] ?: '(Tanpa Subject)' }}</div>
        </div>
    </div>

    <div>
        <span class="text-xs text-slate-500 font-semibold block mb-2">Tampilan Body Email:</span>
        <div class="p-6 bg-slate-50 rounded-lg border border-slate-200 min-h-[250px]">
            <div class="bg-white p-6 rounded shadow-sm border border-slate-200">
                {!! $rendered['body'] !!}
            </div>
        </div>
        <p class="text-xs text-slate-400 mt-2">* Variabel dinamis otomatis diganti dengan data contoh kontak simulasi.</p>
    </div>

    <div class="flex justify-end gap-3 pt-4 border-t border-slate-200">
        <a href="{{ route('email-marketing.templates.edit', $template) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-md">
            Edit Template
        </a>
    </div>
</div>
@endsection
