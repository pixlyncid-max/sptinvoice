@extends('layouts.app')

@section('title', 'Template Isi Surat Penawaran')

@section('actions')
    <a href="{{ route('penawaran-templates.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none transition ease-in-out duration-150">
        Tambah Template
    </a>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($templates as $template)
    <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-gray-200 flex flex-col">
        <div class="p-5 flex-1">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-bold text-gray-800">{{ $template->name }}</h3>
                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold uppercase rounded">{{ $template->code }}</span>
            </div>
            <p class="text-sm text-gray-500 line-clamp-3 mb-4">
                {{ $template->tujuan ?: 'Tidak ada deskripsi tujuan.' }}
            </p>
        </div>
        <div class="px-5 py-3 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
            <a href="{{ route('penawaran-templates.edit', $template) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">Edit Template</a>
            <form action="{{ route('penawaran-templates.destroy', $template) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus template ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-900">Hapus</button>
            </form>
        </div>
    </div>
    @endforeach
</div>

@if($templates->isEmpty())
<div class="bg-white p-12 text-center rounded-lg shadow-sm border border-dashed border-gray-300">
    <div class="text-gray-400 mb-4">
        <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
    </div>
    <h3 class="text-lg font-medium text-gray-900">Belum ada template</h3>
    <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat template penawaran baru.</p>
</div>
@endif
@endsection
