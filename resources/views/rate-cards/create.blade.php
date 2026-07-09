@extends('layouts.app')

@section('title', 'Tambah Rate Card')

@section('actions')
<a href="{{ route('rate-cards.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50">
    Batal
</a>
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-lg border border-slate-200">
    <form action="{{ route('rate-cards.store') }}" method="POST">
        @csrf
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Divisi <span class="text-red-500">*</span></label>
                    <select name="divisi" class="mt-1 block w-full py-2 px-3 border border-slate-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm @error('divisi') border-red-500 @enderror" required>
                        <option value="">-- Pilih Divisi --</option>
                        @foreach($divisi_labels as $key => $label)
                        <option value="{{ $key }}" {{ old('divisi') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('divisi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Sub Kategori</label>
                    <input type="text" name="sub_kategori" value="{{ old('sub_kategori') }}" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('sub_kategori') border-red-500 @enderror" placeholder="Contoh: Content Production, NPWP, Layanan OSS">
                    <p class="mt-1 text-xs text-slate-500">Untuk mengelompokkan layanan dalam satu divisi</p>
                    @error('sub_kategori') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Nama Layanan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_paket" value="{{ old('nama_paket') }}" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('nama_paket') border-red-500 @enderror" required>
                    @error('nama_paket') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Harga (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga" value="{{ old('harga') }}" min="0" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('harga') border-red-500 @enderror" required>
                    @error('harga') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Satuan</label>
                    <input type="text" name="satuan" value="{{ old('satuan', 'Paket') }}" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('satuan') border-red-500 @enderror" placeholder="Contoh: Bulan, Paket, Jam">
                    <p class="mt-1 text-xs text-slate-500">Bulan, Jam, Paket, Unit, Campaign, dll.</p>
                    @error('satuan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Kategori</label>
                    <input type="text" name="kategori" value="{{ old('kategori') }}" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('kategori') border-red-500 @enderror" placeholder="Opsional">
                    @error('kategori') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-1 sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Deskripsi / Detail Pekerjaan</label>
                    <textarea name="deskripsi" rows="3" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>
        <div class="px-4 py-3 bg-slate-50 text-right sm:px-6 border-t border-slate-200 rounded-b-lg">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                Simpan Rate Card
            </button>
        </div>
    </form>
</div>
@endsection
