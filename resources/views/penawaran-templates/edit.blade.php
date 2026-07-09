@extends('layouts.app')

@section('title', 'Edit Template: ' . $penawaranTemplate->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('penawaran-templates.update', $penawaranTemplate) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Informasi Dasar</h3>
                    <p class="mt-1 text-sm text-gray-500">Gunakan kode yang unik untuk mengidentifikasi template ini.</p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2 space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Template</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $penawaranTemplate->name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700">Kode (Slug)</label>
                        <input type="text" name="code" id="code" value="{{ old('code', $penawaranTemplate->code) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Isi Surat</h3>
                    <p class="mt-1 text-sm text-gray-500">Blok teks yang akan muncul di dokumen cetak.</p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2 space-y-6">
                    <div>
                        <label for="tujuan" class="block text-sm font-medium text-gray-700">A. Tujuan dan Lingkup Penugasan (Pembuka)</label>
                        <textarea id="tujuan" name="tujuan" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('tujuan', $penawaranTemplate->tujuan) }}</textarea>
                    </div>
                    <div>
                        <label for="lingkup" class="block text-sm font-medium text-gray-700">A. Tujuan dan Lingkup Penugasan (Detail)</label>
                        <textarea id="lingkup" name="lingkup" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('lingkup', $penawaranTemplate->lingkup) }}</textarea>
                    </div>
                    <div>
                        <label for="jenis_pekerjaan_intro" class="block text-sm font-medium text-gray-700">B. Jenis Pekerjaan (Intro)</label>
                        <textarea id="jenis_pekerjaan_intro" name="jenis_pekerjaan_intro" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('jenis_pekerjaan_intro', $penawaranTemplate->jenis_pekerjaan_intro) }}</textarea>
                    </div>
                    <div>
                        <label for="prasyarat" class="block text-sm font-medium text-gray-700">D. Prasyarat</label>
                        <textarea id="prasyarat" name="prasyarat" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('prasyarat', $penawaranTemplate->prasyarat) }}</textarea>
                    </div>
                    <div>
                        <label for="penutup" class="block text-sm font-medium text-gray-700">E. Penutup</label>
                        <textarea id="penutup" name="penutup" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('penutup', $penawaranTemplate->penutup) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('penawaran-templates.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Batal</a>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
