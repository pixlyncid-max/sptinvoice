@extends('layouts.app')

@section('title', 'Tambah Client')

@section('actions')
<a href="{{ route('clients.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50">
    Kembali
</a>
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-lg border border-slate-200">
    <form action="{{ route('clients.store') }}" method="POST">
        @csrf
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                
                <div class="col-span-1">
                    <label for="nama" class="block text-sm font-medium text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('nama') border-red-500 @enderror" required>
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1">
                    <label for="perusahaan" class="block text-sm font-medium text-slate-700">Nama Perusahaan</label>
                    <input type="text" name="perusahaan" id="perusahaan" value="{{ old('perusahaan') }}" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('perusahaan') border-red-500 @enderror">
                    @error('perusahaan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1">
                    <label for="email" class="block text-sm font-medium text-slate-700">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('email') border-red-500 @enderror" required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1">
                    <label for="telepon" class="block text-sm font-medium text-slate-700">Telepon</label>
                    <input type="text" name="telepon" id="telepon" value="{{ old('telepon') }}" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('telepon') border-red-500 @enderror">
                    @error('telepon')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-2">
                    <label for="alamat" class="block text-sm font-medium text-slate-700">Alamat Lengkap</label>
                    <textarea name="alamat" id="alamat" rows="3" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('alamat') border-red-500 @enderror">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>
        <div class="px-4 py-3 bg-slate-50 text-right sm:px-6 border-t border-slate-200 rounded-b-lg">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                Simpan Client
            </button>
        </div>
    </form>
</div>
@endsection
