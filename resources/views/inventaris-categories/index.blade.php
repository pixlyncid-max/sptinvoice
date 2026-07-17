@extends('layouts.app')

@section('title', 'Kategori Inventaris')

@section('actions')
    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'add-category')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none transition ease-in-out duration-150">
        Tambah Kategori
    </button>
@endsection

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prefix (Kode)</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($categories as $category)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $category->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $category->prefix }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button x-data="" 
                                x-on:click.prevent="$dispatch('open-modal', 'edit-category-{{ $category->id }}')" 
                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                            Edit
                        </button>
                        <form action="{{ route('inventaris-categories.destroy', $category) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                        </form>
                    </td>
                </tr>

                {{-- Edit Modal --}}
                <x-modal name="edit-category-{{ $category->id }}" maxWidth="md">
                    <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50 rounded-t-lg">
                        <h2 class="text-lg font-bold text-slate-800">Edit Kategori</h2>
                        <button @click="show = false" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
                    </div>
                    <form action="{{ route('inventaris-categories.update', $category) }}" method="POST" class="p-6">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Kategori</label>
                                <input type="text" name="name" value="{{ $category->name }}" required class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Prefix Kode</label>
                                <input type="text" name="prefix" value="{{ $category->prefix }}" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5" placeholder="Kosongkan jika auto">
                                <p class="text-xs text-gray-500 mt-1">Satu atau dua huruf (contoh: E untuk Elektronik).</p>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="button" @click="show = false" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                                Batal
                            </button>
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Simpan
                            </button>
                        </div>
                    </form>
                </x-modal>

                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Add Modal --}}
<x-modal name="add-category" maxWidth="md">
    <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50 rounded-t-lg">
        <h2 class="text-lg font-bold text-slate-800">Tambah Kategori</h2>
        <button @click="show = false" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
    </div>
    <form action="{{ route('inventaris-categories.store') }}" method="POST" class="p-6">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Kategori</label>
                <input type="text" name="name" required class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Prefix Kode</label>
                <input type="text" name="prefix" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5" placeholder="Kosongkan jika auto">
                <p class="text-xs text-gray-500 mt-1">Satu atau dua huruf (contoh: E untuk Elektronik).</p>
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <button type="button" @click="show = false" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3">
                Batal
            </button>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Simpan
            </button>
        </div>
    </form>
</x-modal>
@endsection
