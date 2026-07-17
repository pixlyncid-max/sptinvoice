@extends('layouts.app')

@section('title', 'Edit Barang Inventaris')

@section('actions')
<div class="flex gap-2">
    <a href="{{ route('inventaris.show', $inventaris) }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50 transition">
        Lihat Detail / QR
    </a>
    <a href="{{ route('inventaris.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50 transition">
        Kembali
    </a>
</div>
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-lg border border-slate-200">
    <form action="{{ route('inventaris.update', $inventaris) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                
                <!-- Kode Barang -->
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Kode Barang</label>
                    <input type="text" value="{{ $inventaris->kode_barang }}" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border bg-slate-50 text-slate-600 font-mono font-bold cursor-not-allowed" readonly>
                    <p class="mt-1 text-xs text-slate-400">Kode barang unik tidak dapat diubah setelah di-generate.</p>
                </div>

                <!-- Kategori -->
                <div class="col-span-1">
                    <label for="inventaris_category_id" class="block text-sm font-medium text-slate-700">Kategori <span class="text-red-500">*</span></label>
                    <select name="inventaris_category_id" id="inventaris_category_id" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border bg-white @error('inventaris_category_id') border-red-500 @enderror" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('inventaris_category_id', $inventaris->inventaris_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('inventaris_category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Barang -->
                <div class="col-span-1">
                    <label for="nama_barang" class="block text-sm font-medium text-slate-700">Nama Barang <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang', $inventaris->nama_barang) }}" placeholder="Contoh: Laptop ThinkPad X1" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('nama_barang') border-red-500 @enderror" required>
                    @error('nama_barang')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Merk -->
                <div class="col-span-1">
                    <label for="nama_merk" class="block text-sm font-medium text-slate-700">Merk / Brand</label>
                    <input type="text" name="nama_merk" id="nama_merk" value="{{ old('nama_merk', $inventaris->nama_merk) }}" placeholder="Contoh: Lenovo" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('nama_merk') border-red-500 @enderror">
                    @error('nama_merk')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Beli -->
                <div class="col-span-1">
                    <label for="tanggal_beli" class="block text-sm font-medium text-slate-700">Tanggal Pembelian <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_beli" id="tanggal_beli" value="{{ old('tanggal_beli', $inventaris->tanggal_beli ? $inventaris->tanggal_beli->format('Y-m-d') : '') }}" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('tanggal_beli') border-red-500 @enderror" required>
                    @error('tanggal_beli')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kondisi -->
                <div class="col-span-1">
                    <label for="kondisi" class="block text-sm font-medium text-slate-700">Kondisi Barang <span class="text-red-500">*</span></label>
                    <select name="kondisi" id="kondisi" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border bg-white @error('kondisi') border-red-500 @enderror" required>
                        <option value="baik" {{ old('kondisi', $inventaris->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak_ringan" {{ old('kondisi', $inventaris->kondisi) == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="rusak_berat" {{ old('kondisi', $inventaris->kondisi) == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                    @error('kondisi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pengguna (Karyawan) -->
                <div class="col-span-1 sm:col-span-2">
                    <label for="employee_id" class="block text-sm font-medium text-slate-700">Pengguna / Penanggung Jawab</label>
                    <select name="employee_id" id="employee_id" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border bg-white @error('employee_id') border-red-500 @enderror">
                        <option value="">-- Belum Diserahkan / Tidak Ada (Kosongkan) --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id', $inventaris->employee_id) == $employee->id ? 'selected' : '' }}>
                                {{ $employee->nama }} (NIK: {{ $employee->nik }}) - {{ $employee->position->nama ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>
        <div class="px-4 py-3 bg-slate-50 text-right sm:px-6 border-t border-slate-200 rounded-b-lg flex justify-end gap-2">
            <a href="{{ route('inventaris.index') }}" class="inline-flex justify-center py-2 px-4 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 focus:outline-none transition">
                Batal
            </a>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
