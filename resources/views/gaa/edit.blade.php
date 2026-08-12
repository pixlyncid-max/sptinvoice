@extends('layouts.app')

@section('title', 'Edit Data GAA')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Data GAA</h1>
            <p class="text-sm text-slate-600">Perbarui data GAA perpajakan untuk <strong>{{ $gaa->nama_perusahaan }}</strong>.</p>
        </div>
        <a href="{{ route('gaa.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 rounded-md text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 shadow-sm">
            &larr; Kembali
        </a>
    </div>

    <form action="{{ route('gaa.update', $gaa->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Section 1: Perusahaan -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
            <h2 class="text-base font-semibold text-slate-900 mb-4 border-b pb-2 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                Profil Perusahaan & NPWP
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nama Perusahaan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $gaa->nama_perusahaan) }}" required class="w-full border-slate-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                    @error('nama_perusahaan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nomor NPWP</label>
                    <input type="text" name="npwp" value="{{ old('npwp', $gaa->npwp) }}" class="w-full border-slate-300 rounded-md shadow-sm focus:ring-primary focus:border-primary font-mono text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">KPP (Kantor Pelayanan Pajak)</label>
                    <input type="text" name="kpp" value="{{ old('kpp', $gaa->kpp) }}" class="w-full border-slate-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">User NPWP 16</label>
                    <input type="text" name="user_npwp_16" value="{{ old('user_npwp_16', $gaa->user_npwp_16) }}" class="w-full border-slate-300 rounded-md shadow-sm focus:ring-primary focus:border-primary font-mono text-sm">
                </div>
            </div>
        </div>

        <!-- Section 2: Account Email & Credentials -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
            <h2 class="text-base font-semibold text-slate-900 mb-4 border-b pb-2 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                Email & Akun Perusahaan
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Email Perusahaan</label>
                    <input type="email" name="email" value="{{ old('email', $gaa->email) }}" class="w-full border-slate-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Password Email</label>
                    <input type="text" name="password_email" value="{{ old('password_email', $gaa->password_email) }}" class="w-full border-slate-300 rounded-md shadow-sm focus:ring-primary focus:border-primary font-mono text-sm">
                </div>
            </div>
        </div>

        <!-- Section 3: DJP Online & Core Tax -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
            <h2 class="text-base font-semibold text-slate-900 mb-4 border-b pb-2 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                Akses DJP Online & Core Tax
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">DJP Online - User</label>
                    <input type="text" name="djp_user" value="{{ old('djp_user', $gaa->djp_user) }}" class="w-full border-slate-300 rounded-md shadow-sm focus:ring-primary focus:border-primary font-mono text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">DJP Online - Password</label>
                    <input type="text" name="djp_password" value="{{ old('djp_password', $gaa->djp_password) }}" class="w-full border-slate-300 rounded-md shadow-sm focus:ring-primary focus:border-primary font-mono text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Coretax - Password</label>
                    <input type="text" name="coretax_password" value="{{ old('coretax_password', $gaa->coretax_password) }}" class="w-full border-slate-300 rounded-md shadow-sm focus:ring-primary focus:border-primary font-mono text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Checklist Coretax <span class="text-red-500">*</span></label>
                    <select name="checklist_coretax" required class="w-full border-slate-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                        <option value="Belum" {{ old('checklist_coretax', $gaa->checklist_coretax) == 'Belum' ? 'selected' : '' }}>Belum</option>
                        <option value="Sudah" {{ old('checklist_coretax', $gaa->checklist_coretax) == 'Sudah' ? 'selected' : '' }}>Sudah</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Section 4: PIC & Keterangan -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-slate-200">
            <h2 class="text-base font-semibold text-slate-900 mb-4 border-b pb-2 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                Penanggung Jawab (PIC) & Keterangan
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">PIC - NIK</label>
                    <input type="text" name="pic_nik" value="{{ old('pic_nik', $gaa->pic_nik) }}" class="w-full border-slate-300 rounded-md shadow-sm focus:ring-primary focus:border-primary font-mono text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">PIC - Nama Lengkap</label>
                    <input type="text" name="pic_nama" value="{{ old('pic_nama', $gaa->pic_nama) }}" class="w-full border-slate-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">
                </div>

                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="3" class="w-full border-slate-300 rounded-md shadow-sm focus:ring-primary focus:border-primary">{{ old('keterangan', $gaa->keterangan) }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('gaa.index') }}" class="px-5 py-2.5 border border-slate-300 rounded-md text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">Batal</a>
            <button type="submit" class="px-5 py-2.5 bg-primary hover:bg-primary-dark text-white rounded-md text-sm font-medium shadow-sm">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
