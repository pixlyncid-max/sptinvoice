@extends('layouts.app')

@section('title', 'Tambah Bank')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg max-w-2xl">
    <div class="p-6 text-gray-900">
        <form action="{{ route('banks.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="nama_bank" class="block text-sm font-medium text-gray-700">Nama Bank</label>
                <input type="text" name="nama_bank" id="nama_bank" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                @error('nama_bank')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="nomor_rekening" class="block text-sm font-medium text-gray-700">Nomor Rekening</label>
                <input type="text" name="nomor_rekening" id="nomor_rekening" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                @error('nomor_rekening')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="atas_nama" class="block text-sm font-medium text-gray-700">Atas Nama</label>
                <input type="text" name="atas_nama" id="atas_nama" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                @error('atas_nama')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="is_active" class="inline-flex items-center">
                    <input id="is_active" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active" value="1" checked>
                    <span class="ml-2 text-sm text-gray-600">Aktif</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a href="{{ route('banks.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 text-sm">Batal</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
