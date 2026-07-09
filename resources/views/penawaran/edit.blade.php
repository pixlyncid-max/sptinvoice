@extends('layouts.app')

@section('title', 'Edit Penawaran: ' . $penawaran->nomor_penawaran)

@section('actions')
<div class="flex gap-2">
    @if($penawaran->status == 'disetujui' || $penawaran->status == 'dikirim')
    <form action="{{ route('penawaran.convert', $penawaran) }}" method="POST" onsubmit="return confirm('Buat Invoice dari Penawaran ini?');">
        @csrf
        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
            Jadikan Invoice
        </button>
    </form>
    @endif
    <a href="{{ route('penawaran.print', $penawaran) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50">
        Preview/Print
    </a>
    <a href="{{ route('penawaran.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50">
        Kembali
    </a>
</div>
@endsection

@section('content')
<div x-data="penawaranForm()" class="bg-white shadow-sm rounded-lg border border-slate-200">
    <form action="{{ route('penawaran.update', $penawaran) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Header Info -->
        <div class="px-4 py-5 border-b border-slate-200 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-slate-900 mb-4">Informasi Dokumen</h3>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Nomor Penawaran <span class="text-red-500">*</span></label>
                    <input type="text" name="nomor_penawaran" value="{{ old('nomor_penawaran', $penawaran->nomor_penawaran) }}" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('nomor_penawaran') border-red-500 @enderror" required>
                    @error('nomor_penawaran') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Client <span class="text-red-500">*</span></label>
                    <select name="client_id" x-model="clientId" class="mt-1 block w-full py-2 px-3 border border-slate-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm @error('client_id') border-red-500 @enderror" required>
                        <option value="">-- Pilih Client --</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ (old('client_id', $penawaran->client_id)) == $client->id ? 'selected' : '' }}>{{ $client->nama }} {{ $client->perusahaan ? '('.$client->perusahaan.')' : '' }}</option>
                        @endforeach
                    </select>
                    @error('client_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="mt-1 block w-full py-2 px-3 border border-slate-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm" required>
                        <option value="draft" {{ old('status', $penawaran->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="dikirim" {{ old('status', $penawaran->status) == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="disetujui" {{ old('status', $penawaran->status) == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ old('status', $penawaran->status) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $penawaran->tanggal->toDateString()) }}" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('tanggal') border-red-500 @enderror" required>
                    @error('tanggal') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Berlaku Hingga <span class="text-red-500">*</span></label>
                    <input type="date" name="berlaku_hingga" value="{{ old('berlaku_hingga', $penawaran->berlaku_hingga->toDateString()) }}" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('berlaku_hingga') border-red-500 @enderror" required>
                    @error('berlaku_hingga') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-3">
                    <label class="block text-sm font-medium text-slate-700">Perihal <span class="text-red-500">*</span></label>
                    <select name="perihal" x-model="perihal" @change="fetchRateCards()" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('perihal') border-red-500 @enderror" required>
                        <option value="Surat Penawaran Jasa Digital dan Digital Marketing" {{ old('perihal', $penawaran->perihal) == 'Surat Penawaran Jasa Digital dan Digital Marketing' ? 'selected' : '' }}>Surat Penawaran Jasa Digital dan Digital Marketing</option>
                        <option value="Surat Penawaran Jasa Keuangan dan Perpajakan" {{ old('perihal', $penawaran->perihal) == 'Surat Penawaran Jasa Keuangan dan Perpajakan' ? 'selected' : '' }}>Surat Penawaran Jasa Keuangan dan Perpajakan</option>
                        <option value="Surat Penawaran Jasa Perizinan dan Perpajakan" {{ old('perihal', $penawaran->perihal) == 'Surat Penawaran Jasa Perizinan dan Perpajakan' ? 'selected' : '' }}>Surat Penawaran Jasa Perizinan dan Perpajakan</option>
                    </select>
                    @error('perihal') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-3">
                    <div class="flex items-start mt-4">
                        <div class="flex h-6 items-center">
                            <input type="hidden" name="dengan_ttd" value="0">
                            <input id="dengan_ttd" name="dengan_ttd" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" {{ old('dengan_ttd', $penawaran->dengan_ttd) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm leading-6">
                            <label for="dengan_ttd" class="font-medium text-slate-900">Tampilkan Tanda Tangan & Stempel</label>
                            <p class="text-slate-500">Centang jika ingin menampilkan tanda tangan dan stempel di hasil cetak PDF.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Items Section -->
        <div class="px-4 py-5 border-b border-slate-200 sm:px-6 space-y-8">
            
            <!-- 1. JENIS PEKERJAAN -->
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-md font-bold text-slate-800 uppercase tracking-wider">1. Jenis Pekerjaan</h3>
                    <button type="button" @click="addItem('Jenis Pekerjaan')" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-primary hover:bg-primary-dark shadow-sm transition-all">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Baris
                    </button>
                </div>
                <div class="overflow-x-auto border border-slate-200 rounded-lg shadow-sm">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase w-12">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Jenis Pekerjaan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Keterangan (Opsional)</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase w-16">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            <template x-for="(item, index) in items.filter(i => i.kategori_layanan === 'Jenis Pekerjaan')" :key="item.id">
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-3 text-sm text-slate-500 text-center" x-text="index + 1"></td>
                                    <td class="px-4 py-3">
                                        <input type="text" x-model="item.deskripsi" class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border" placeholder="Contoh: Pembuatan Akun Coretax" required>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" x-model="item.keterangan" class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border text-slate-500" placeholder="-">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" @click="removeItem(item.id)" class="text-red-400 hover:text-red-600 transition-colors">
                                            <svg class="h-5 w-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="items.filter(i => i.kategori_layanan === 'Jenis Pekerjaan').length === 0">
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-slate-400 text-sm italic">Belum ada item jenis pekerjaan. Klik "Tambah Baris" untuk menambah.</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 2. FEE PEKERJAAN -->
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-md font-bold text-slate-800 uppercase tracking-wider">2. Fee Pekerjaan</h3>
                    <div class="flex gap-2">
                        <button type="button" @click="showRateCardModal = true" class="inline-flex items-center px-3 py-1.5 border border-primary text-xs font-medium rounded-md text-primary bg-white hover:bg-slate-50 shadow-sm transition-all">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Dari Rate Card
                        </button>
                        <button type="button" @click="addItem('Fee Pekerjaan')" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-primary hover:bg-primary-dark shadow-sm transition-all">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Baris
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto border border-slate-200 rounded-lg shadow-sm">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase w-12">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Keterangan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase w-20">Qty</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase w-48">Harga Satuan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase w-48">Subtotal</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase w-16">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            <template x-for="(item, index) in items.filter(i => i.kategori_layanan === 'Fee Pekerjaan')" :key="item.id">
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-3 text-sm text-slate-500 text-center" x-text="index + 1"></td>
                                    <td class="px-4 py-3">
                                        <select x-model="item.deskripsi" 
                                            @change="updatePriceFromSelect(item)"
                                            class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border" required>
                                            <option value="">-- Pilih Rate Card --</option>
                                            <template x-for="rc in rateCards" :key="'select-' + rc.id">
                                                <option :value="rc.nama_paket" x-text="rc.nama_paket"></option>
                                            </template>
                                        </select>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" x-model.number="item.qty" @input="calculate()" min="1" class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border" required>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-slate-400 sm:text-sm">Rp</span>
                                            </div>
                                            <input type="number" x-model.number="item.harga_satuan" @input="calculate()" min="0" class="focus:ring-primary focus:border-primary block w-full pl-9 sm:text-sm border-slate-300 rounded-md py-2 px-3 border" required>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm font-bold text-slate-900" x-text="formatCurrency(item.qty * item.harga_satuan)"></td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" @click="removeItem(item.id)" class="text-red-400 hover:text-red-600 transition-colors">
                                            <svg class="h-5 w-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="items.filter(i => i.kategori_layanan === 'Fee Pekerjaan').length === 0">
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-slate-400 text-sm italic">Belum ada item fee pekerjaan. Gunakan "Dari Rate Card" atau "Tambah Baris".</td>
                                </tr>
                            </template>
                        </tbody>
                        <tfoot class="bg-slate-50">
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-right text-sm font-bold text-slate-700 uppercase">Total</td>
                                <td class="px-4 py-3 text-right text-md font-bold text-slate-800" x-text="formatCurrency(subtotal)"></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-right text-sm font-bold text-slate-700 uppercase">Diskon</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="relative rounded-md shadow-sm w-48 ml-auto">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-400 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" name="diskon" x-model.number="diskon" @input="calculate()" min="0" class="focus:ring-primary focus:border-primary block w-full pl-9 sm:text-sm border-slate-300 rounded-md py-1.5 px-3 border text-right">
                                    </div>
                                </td>
                                <td></td>
                            </tr>
                            <tr class="bg-primary/5 border-t-2 border-primary/20">
                                <td colspan="4" class="px-4 py-4 text-right text-md font-black text-slate-800 uppercase">Total Penawaran</td>
                                <td class="px-4 py-4 text-right text-lg font-black text-primary" x-text="formatCurrency(setelah_diskon)"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- 3. DATA YANG DIPERLUKAN -->
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-md font-bold text-slate-800 uppercase tracking-wider">3. Data Yang Diperlukan</h3>
                    <button type="button" @click="addItem('Data Yang Diperlukan')" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-primary hover:bg-primary-dark shadow-sm transition-all">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Baris
                    </button>
                </div>
                <div class="overflow-x-auto border border-slate-200 rounded-lg shadow-sm">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase w-12">No</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase w-1/3">Data Yang Diperlukan</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-600 uppercase">Keterangan / Deskripsi</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-slate-600 uppercase w-16">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-100">
                            <template x-for="(item, index) in items.filter(i => i.kategori_layanan === 'Data Yang Diperlukan')" :key="item.id">
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-3 text-sm text-slate-500 text-center" x-text="index + 1"></td>
                                    <td class="px-4 py-3">
                                        <input type="text" x-model="item.deskripsi" class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border" placeholder="Contoh: Akta Pendirian" required>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" x-model="item.keterangan" class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md py-2 px-3 border" placeholder="Contoh: Scan Asli">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" @click="removeItem(item.id)" class="text-red-400 hover:text-red-600 transition-colors">
                                            <svg class="h-5 w-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                            <template x-if="items.filter(i => i.kategori_layanan === 'Data Yang Diperlukan').length === 0">
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-slate-400 text-sm italic">Belum ada data yang diperlukan. Klik "Tambah Baris" untuk menambah.</td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Hidden fields for submission -->
            <template x-for="(item, index) in items" :key="'submit-'+item.id">
                <div>
                    <input type="hidden" :name="`items[${index}][kategori_layanan]`" :value="item.kategori_layanan">
                    <input type="hidden" :name="`items[${index}][deskripsi]`" :value="item.deskripsi">
                    <input type="hidden" :name="`items[${index}][keterangan]`" :value="item.keterangan">
                    <input type="hidden" :name="`items[${index}][qty]`" :value="item.qty">
                    <input type="hidden" :name="`items[${index}][harga_satuan]`" :value="item.harga_satuan">
                </div>
            </template>
            <input type="hidden" name="pajak_persen" :value="pajak_persen">
            <input type="hidden" name="pajak_label" :value="pajak_label">
        </div>

        <!-- Notes -->
        <div class="px-4 py-5 sm:px-6">
            <label class="block text-sm font-medium text-slate-700 mb-2">Catatan / Keterangan Tambahan</label>
            <textarea name="catatan" rows="3" class="focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border">{{ old('catatan', $penawaran->catatan) }}</textarea>
        </div>

        <!-- Submit -->
        <div class="px-4 py-4 bg-slate-50 text-right sm:px-6 border-t border-slate-200 rounded-b-lg flex justify-between">
            <button type="button" onclick="if(confirm('Yakin ingin menghapus penawaran ini?')) document.getElementById('delete-form').submit();" class="inline-flex justify-center py-2 px-4 border border-slate-300 shadow-sm text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50 transition-all">
                Hapus Penawaran
            </button>
            <button type="submit" class="inline-flex justify-center py-2.5 px-8 border border-transparent shadow-sm text-sm font-bold rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all">
                Simpan Perubahan
            </button>
        </div>
    </form>
    
    <form id="delete-form" action="{{ route('penawaran.destroy', $penawaran) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- Rate Card Modal -->
    <div x-show="showRateCardModal" class="fixed z-50 inset-0 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div x-show="showRateCardModal" x-transition.opacity class="fixed inset-0 bg-slate-500 bg-opacity-75" @click="showRateCardModal = false"></div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <!-- Content -->
            <div x-show="showRateCardModal" x-transition.scale class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full z-[60] relative">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-8 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h3 class="text-2xl font-bold text-slate-900" id="modal-title">Pilih Item dari Rate Card</h3>
                                    <p class="text-sm text-slate-500 mt-1">Pilih layanan yang sesuai dengan divisi penawaran Anda.</p>
                                </div>
                                <button @click="showRateCardModal = false" class="text-slate-400 hover:text-slate-600 transition-colors p-2 bg-slate-50 rounded-full">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 mb-6 p-4 bg-slate-50 rounded-xl">
                                <div class="relative flex-1">
                                    <input type="text" x-model="searchRateCard" placeholder="Cari layanan atau kategori..." class="block w-full pl-10 pr-3 py-2.5 text-sm border-slate-300 focus:outline-none focus:ring-primary focus:border-primary rounded-lg shadow-sm border">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-bold text-slate-400 uppercase hidden sm:block">Divisi:</span>
                                    <select x-model="filterDivisi" @change="fetchRateCardsByDivisi(filterDivisi)" class="block w-full sm:w-auto pl-3 pr-10 py-2.5 text-sm border-slate-300 focus:outline-none focus:ring-primary focus:border-primary rounded-lg shadow-sm bg-white border">
                                        <option value="digital_marketing">Digital Marketing</option>
                                        <option value="keuangan_perpajakan">Keuangan & Perpajakan</option>
                                        <option value="perizinan">Perizinan</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <div x-show="loadingRateCards" class="text-center py-10">
                                    <svg class="animate-spin h-8 w-8 text-primary mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <p class="mt-2 text-sm text-slate-500">Memuat data...</p>
                                </div>
                                
                                <div x-show="!loadingRateCards && Array.isArray(rateCards) && getFilteredRateCards().length === 0" class="text-center py-10 border-2 border-dashed border-slate-200 rounded-xl">
                                    <p class="text-sm text-slate-400 italic">Tidak ditemukan item yang cocok.</p>
                                </div>
                                
                                <div x-show="!loadingRateCards && Array.isArray(rateCards) && getFilteredRateCards().length > 0" class="max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                                    <div class="space-y-6">
                                        <template x-for="(items, subKategori) in groupBy(getFilteredRateCards(), 'sub_kategori')" :key="subKategori">
                                            <div class="space-y-2">
                                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider sticky top-0 bg-white py-1" x-text="subKategori || 'Layanan Umum'"></h4>
                                                <div class="grid grid-cols-1 gap-2">
                                                    <template x-for="rc in items" :key="'rc-' + rc.id">
                                                        <div class="p-3 border border-slate-200 rounded-xl hover:border-primary hover:bg-primary/5 transition-all cursor-pointer flex justify-between items-center group" @click="addFromRateCard(rc)">
                                                            <div class="flex-1 min-w-0">
                                                                <p class="text-sm font-bold text-slate-900 group-hover:text-primary truncate" x-text="rc.nama_paket"></p>
                                                            </div>
                                                            <div class="text-right ml-4">
                                                                <p class="text-sm font-bold text-primary" x-text="formatCurrency(rc.harga)"></p>
                                                                <span class="text-[10px] text-slate-400" x-text="'/ ' + rc.satuan"></span>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-4 sm:px-8 sm:flex sm:flex-row-reverse border-t border-slate-100">
                    <button type="button" @click="showRateCardModal = false" class="w-full inline-flex justify-center rounded-lg border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none sm:w-auto sm:text-sm">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('penawaranForm', () => ({
            clientId: '{{ old('client_id', $penawaran->client_id) }}',
            perihal: '{{ old('perihal', $penawaran->perihal) }}',
            items: {!! json_encode($penawaran->items->map(function($item) {
                return [
                    'id' => $item->id,
                    'kategori_layanan' => $item->kategori_layanan ?? 'Fee Pekerjaan',
                    'deskripsi' => $item->deskripsi,
                    'keterangan' => $item->keterangan ?? '',
                    'qty' => $item->qty,
                    'harga_satuan' => (float) $item->harga_satuan
                ];
            })) !!},
            diskon: {{ old('diskon', (float) ($penawaran->diskon ?? 0)) }},
            subtotal: 0,
            pajak_persen: {{ old('pajak_persen', $penawaran->pajak_persen ?? 0) }},
            pajak_label: '{{ old('pajak_label', $penawaran->pajak_label ?? 'Pajak') }}',
            pajak_amount: 0,
            setelah_diskon: 0,
            total: 0,
            showRateCardModal: false,
            rateCards: [],
            searchRateCard: '',
            loadingRateCards: false,
            filterDivisi: 'digital_marketing',
            fetchController: null,

            perihalToDivisi: {
                'Surat Penawaran Jasa Digital dan Digital Marketing': 'digital_marketing',
                'Surat Penawaran Jasa Keuangan dan Perpajakan': 'keuangan_perpajakan',
                'Surat Penawaran Jasa Perizinan dan Perpajakan': 'perizinan',
            },

            init() {
                this.calculate();
                
                // Fetch rate cards immediately and then watch for changes
                this.filterDivisi = this.perihalToDivisi[this.perihal] || 'digital_marketing';
                
                this.$nextTick(() => {
                    this.fetchRateCards();
                });

                // Watch for perihal changes to refresh rate cards
                this.$watch('perihal', (value) => {
                    console.log('Perihal changed to:', value);
                    this.filterDivisi = this.perihalToDivisi[value] || 'digital_marketing';
                    this.fetchRateCards();
                });
            },

            addItem(kategori) {
                this.items.push({ 
                    id: Date.now() + Math.random(), 
                    kategori_layanan: kategori, 
                    deskripsi: '', 
                    keterangan: '', 
                    qty: 1, 
                    harga_satuan: 0 
                });
            },

            removeItem(id) {
                this.items = this.items.filter(i => i.id !== id);
                this.calculate();
            },

            calculate() {
                this.subtotal = this.items
                    .filter(i => i.kategori_layanan === 'Fee Pekerjaan')
                    .reduce((sum, item) => sum + (item.qty * item.harga_satuan), 0);
                
                let diskonVal = parseFloat(this.diskon) || 0;
                this.setelah_diskon = this.subtotal - diskonVal;
                
                this.pajak_amount = (this.pajak_persen / 100) * this.setelah_diskon;
                
                const isTermin = this.pajak_label.toLowerCase().includes('termin') || this.pajak_label.toLowerCase().includes('dp');
                this.total = isTermin ? this.pajak_amount : (this.setelah_diskon + this.pajak_amount);
            },

            formatCurrency(value) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(value);
            },

            groupBy(array, key) {
                return array.reduce((result, currentValue) => {
                    (result[currentValue[key] || 'Layanan Umum'] = result[currentValue[key] || 'Layanan Umum'] || []).push(currentValue);
                    return result;
                }, {});
            },

            getFilteredRateCards() {
                if (!this.searchRateCard) return this.rateCards;
                const search = this.searchRateCard.toLowerCase();
                return this.rateCards.filter(rc => 
                    (rc.nama_paket && rc.nama_paket.toLowerCase().includes(search)) || 
                    (rc.sub_kategori && rc.sub_kategori.toLowerCase().includes(search))
                );
            },

            fetchRateCards() {
                this.fetchRateCardsByDivisi(this.filterDivisi);
            },

            fetchRateCardsByDivisi(divisi) {
                console.log('Fetching rate cards for divisi:', divisi);
                this.filterDivisi = divisi;
                
                // Abort previous request if any
                if (this.fetchController) {
                    this.fetchController.abort();
                }
                this.fetchController = new AbortController();
                
                this.loadingRateCards = true;
                this.rateCards = []; // Reset to avoid Alpine.js diffing issues
                
                fetch(`{{ route('rate-cards.items') }}?divisi=${divisi}`, {
                    signal: this.fetchController.signal
                })
                    .then(res => {
                        if (!res.ok) throw new Error('Network response was not ok');
                        return res.json();
                    })
                    .then(data => {
                        console.log('Rate cards loaded:', Array.isArray(data) ? data.length : 'not an array', 'items');
                        this.rateCards = Array.isArray(data) ? data : [];
                        this.loadingRateCards = false;
                    })
                    .catch(err => {
                        if (err.name === 'AbortError') return;
                        console.error('Error fetching rate cards:', err);
                        this.loadingRateCards = false;
                        this.rateCards = [];
                    });
            },

            addFromRateCard(rc) {
                console.log('Adding from rate card:', rc);
                const harga = parseFloat(rc.harga) || 0;

                // Find first empty fee row or add new
                const index = this.items.findIndex(i => i.kategori_layanan === 'Fee Pekerjaan' && i.deskripsi === '' && (i.harga_satuan == 0 || i.harga_satuan == null));
                
                if (index !== -1) {
                    this.items.splice(index, 1, {...this.items[index], deskripsi: rc.nama_paket, harga_satuan: harga, qty: 1});
                } else {
                    this.items.push({ 
                        id: Date.now() + Math.random(), 
                        kategori_layanan: 'Fee Pekerjaan', 
                        deskripsi: rc.nama_paket, 
                        keterangan: '', 
                        qty: 1, 
                        harga_satuan: harga 
                    });
                }
                
                this.calculate();
                this.showRateCardModal = false;
            },

            updatePriceFromSelect(item) {
                if (item.deskripsi) {
                    const rc = this.rateCards.find(r => r.nama_paket === item.deskripsi);
                    if (rc) {
                        item.harga_satuan = parseFloat(rc.harga) || 0;
                    }
                } else {
                    item.harga_satuan = 0;
                }
                this.calculate();
            }
        }));
    });
</script>
@endpush
@endsection
