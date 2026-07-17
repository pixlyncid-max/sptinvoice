@extends('layouts.app')

@section('title', 'Manajemen Karyawan')

@section('actions')
<button @click="$dispatch('open-modal', 'import-employee')" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50 transition duration-150 mr-2">
    Impor Data
</button>
<a href="{{ route('employees.template') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50 transition duration-150 mr-2">
    Unduh Template
</a>
<button @click="$dispatch('open-modal', 'add-employee')" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
    + Tambah Karyawan
</button>
@endsection

@section('content')
<div class="bg-white shadow-sm rounded-lg border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nama / NIK</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Jabatan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Gaji Pokok</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @forelse($employees as $employee)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-slate-900">{{ $employee->nama }}</div>
                        <div class="text-xs text-slate-500">NIK: {{ $employee->nik ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-slate-900">{{ $employee->jabatan }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">Rp {{ number_format($employee->gaji_pokok, 0, ',', '.') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button @click="$dispatch('open-modal', 'edit-employee-{{ $employee->id }}')" class="text-indigo-600 hover:text-indigo-900 font-bold mr-4">Edit</button>
                        <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus karyawan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Hapus</button>
                        </form>

                        {{-- Edit Modal --}}
                        <x-modal name="edit-employee-{{ $employee->id }}" maxWidth="4xl">
                            <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50 rounded-t-lg">
                                <h2 class="text-lg font-bold text-slate-800">Edit Data Karyawan</h2>
                                <button @click="show = false" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
                            </div>
                            <form action="{{ route('employees.update', $employee) }}" method="POST" class="p-8 text-left">
                                @csrf @method('PATCH')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-5">
                                        <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-widest border-b pb-2">Informasi Personal</h3>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap</label>
                                            <input type="text" name="nama" value="{{ $employee->nama }}" required class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NIK</label>
                                            <input type="text" name="nik" value="{{ $employee->nik }}" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jabatan</label>
                                            <select name="position_id" required class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                                                <option value="">Pilih Jabatan</option>
                                                @foreach($positions as $pos)
                                                    <option value="{{ $pos->id }}" {{ $employee->position_id == $pos->id ? 'selected' : '' }}>{{ $pos->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="space-y-5">
                                        <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-widest border-b pb-2">Penggajian & Bank</h3>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Gaji Pokok</label>
                                            <div class="relative">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-slate-500 text-sm">Rp</span>
                                                </div>
                                                <input type="number" name="gaji_pokok" value="{{ (int)$employee->gaji_pokok }}" required class="block w-full pl-10 rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Bank</label>
                                            <input type="text" name="bank" value="{{ $employee->bank }}" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5" placeholder="Contoh: BNI, BCA">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">No Rekening</label>
                                            <input type="text" name="no_rekening" value="{{ $employee->no_rekening }}" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Atas Nama Rekening</label>
                                            <input type="text" name="nama_rekening" value="{{ $employee->nama_rekening }}" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tgl Masuk</label>
                                            <input type="date" name="tgl_masuk" value="{{ $employee->tgl_masuk ? $employee->tgl_masuk->format('Y-m-d') : '' }}" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-10 flex justify-end gap-3 border-t pt-6">
                                    <button type="button" @click="show = false" class="px-6 py-2.5 text-sm font-bold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">Batal</button>
                                    <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-md">Simpan Perubahan</button>
                                </div>
                            </form>
                        </x-modal>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-20 text-center text-slate-500">
                        <div class="text-4xl mb-4 opacity-20">👥</div>
                        <p class="text-lg font-medium">Belum ada data karyawan.</p>
                        <p class="text-sm opacity-60">Silakan tambahkan karyawan pertama Anda.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Add Modal --}}
<x-modal name="add-employee" maxWidth="4xl">
    <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50 rounded-t-lg">
        <h2 class="text-lg font-bold text-slate-800">Tambah Karyawan Baru</h2>
        <button @click="show = false" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
    </div>
    <form action="{{ route('employees.store') }}" method="POST" class="p-8">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-5">
                <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-widest border-b pb-2">Informasi Personal</h3>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" required class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">NIK</label>
                    <input type="text" name="nik" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jabatan</label>
                    <select name="position_id" required class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                        <option value="">Pilih Jabatan</option>
                        @foreach($positions as $pos)
                            <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="space-y-5">
                <h3 class="text-sm font-bold text-indigo-600 uppercase tracking-widest border-b pb-2">Penggajian & Bank</h3>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Gaji Pokok</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-slate-500 text-sm">Rp</span>
                        </div>
                        <input type="number" name="gaji_pokok" required class="block w-full pl-10 rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Bank</label>
                    <input type="text" name="bank" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5" placeholder="Contoh: BNI, BCA">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">No Rekening</label>
                    <input type="text" name="no_rekening" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Atas Nama Rekening</label>
                    <input type="text" name="nama_rekening" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tgl Masuk</label>
                    <input type="date" name="tgl_masuk" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5">
                </div>
            </div>
        </div>
        <div class="mt-10 flex justify-end gap-3 border-t pt-6">
            <button type="button" @click="show = false" class="px-6 py-2.5 text-sm font-bold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">Batal</button>
            <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-md">Simpan</button>
        </div>
    </form>
</x-modal>

{{-- Import Modal --}}
<x-modal name="import-employee" maxWidth="2xl">
    <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50 rounded-t-lg">
        <h2 class="text-lg font-bold text-slate-800">Impor Data Karyawan</h2>
        <button @click="show = false" class="text-slate-400 hover:text-slate-600 text-2xl">&times;</button>
    </div>
    <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data" class="p-8">
        @csrf
        <div class="mb-6">
            <p class="text-sm text-slate-600 mb-4">Silakan unggah file Excel (.xlsx atau .csv) yang sudah diisi sesuai dengan template. Anda bisa mengunduh template terlebih dahulu jika belum memilikinya.</p>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">File Excel</label>
            <input type="file" name="file" required accept=".xlsx, .xls, .csv" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-slate-300 rounded-lg">
        </div>
        <div class="flex justify-end gap-3 border-t pt-6">
            <button type="button" @click="show = false" class="px-6 py-2.5 text-sm font-bold text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50">Batal</button>
            <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-md">Mulai Impor</button>
        </div>
    </form>
</x-modal>
@endsection
