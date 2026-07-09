@extends('layouts.app')

@section('title', 'Edit Admin')

@section('actions')
<a href="{{ route('users.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50">
    Kembali
</a>
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-lg border border-slate-200">
    <form action="{{ route('users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="px-4 py-5 sm:p-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                
                <div class="col-span-1 sm:col-span-2 lg:col-span-1">
                    <label for="name" class="block text-sm font-medium text-slate-700">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('name') border-red-500 @enderror" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1 sm:col-span-2 lg:col-span-1">
                    <label for="email" class="block text-sm font-medium text-slate-700">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('email') border-red-500 @enderror" required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1 sm:col-span-2 lg:col-span-2">
                    <div class="mt-4 border-t border-slate-200 pt-6">
                        <h4 class="text-sm font-medium text-slate-900 mb-4">Ubah Password <span class="text-slate-500 font-normal">(Opsional, biarkan kosong jika tidak ingin mengubah password)</span></h4>
                    </div>
                </div>

                <div class="col-span-1 sm:col-span-2 lg:col-span-1">
                    <label for="password" class="block text-sm font-medium text-slate-700">Password Baru</label>
                    <input type="password" name="password" id="password" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1 sm:col-span-2 lg:col-span-1">
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border">
                </div>

            </div>
        </div>
        <div class="px-4 py-3 bg-slate-50 text-right sm:px-6 border-t border-slate-200 rounded-b-lg">
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                Perbarui Admin
            </button>
        </div>
    </form>
</div>
@endsection
