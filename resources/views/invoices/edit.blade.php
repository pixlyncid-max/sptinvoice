@extends('layouts.app')

@section('title', 'Edit Invoice: ' . $invoice->nomor_invoice)

@section('actions')
<div class="flex gap-2">
    <a href="{{ route('invoices.print', $invoice) }}" target="_blank" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50">
        Preview/Print
    </a>
    <a href="{{ route('invoices.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md shadow-sm text-slate-700 bg-white hover:bg-slate-50">
        Kembali
    </a>
</div>
@endsection

@section('content')
<div x-data="invoiceForm()" class="bg-white shadow-sm rounded-lg border border-slate-200">
    <form action="{{ route('invoices.update', $invoice) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Header Info -->
        <div class="px-4 py-5 border-b border-slate-200 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-slate-900 mb-4">Informasi Dokumen</h3>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Nomor Invoice <span class="text-red-500">*</span></label>
                    <input type="text" name="nomor_invoice" value="{{ old('nomor_invoice', $invoice->nomor_invoice) }}" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('nomor_invoice') border-red-500 @enderror" required>
                    @error('nomor_invoice') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Client <span class="text-red-500">*</span></label>
                    <select name="client_id" x-model="clientId" @change="fetchRateCards()" class="mt-1 block w-full py-2 px-3 border border-slate-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm @error('client_id') border-red-500 @enderror" required>
                        <option value="">-- Pilih Client --</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ (old('client_id', $invoice->client_id)) == $client->id ? 'selected' : '' }}>{{ $client->nama }} {{ $client->perusahaan ? '('.$client->perusahaan.')' : '' }}</option>
                        @endforeach
                    </select>
                    @error('client_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="mt-1 block w-full py-2 px-3 border border-slate-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm" required>
                        <option value="draft" {{ old('status', $invoice->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="dikirim" {{ old('status', $invoice->status) == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="dibayar" {{ old('status', $invoice->status) == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                        <option value="batal" {{ old('status', $invoice->status) == 'batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>

                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Tanggal Invoice <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_invoice" value="{{ old('tanggal_invoice', $invoice->tanggal_invoice->toDateString()) }}" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('tanggal_invoice') border-red-500 @enderror" required>
                    @error('tanggal_invoice') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Jatuh Tempo <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo', $invoice->tanggal_jatuh_tempo->toDateString()) }}" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('tanggal_jatuh_tempo') border-red-500 @enderror" required>
                    @error('tanggal_jatuh_tempo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Periode <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <input type="text" name="periode" value="{{ old('periode', $invoice->periode) }}" placeholder="Contoh: Juli 2026" class="mt-1 focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border @error('periode') border-red-500 @enderror">
                    @error('periode') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="col-span-1">
                    <label class="block text-sm font-medium text-slate-700">Bank <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <select name="bank_id" class="mt-1 block w-full py-2 px-3 border border-slate-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                        <option value="">-- Gunakan Default --</option>
                        @foreach($banks as $bank)
                        <option value="{{ $bank->id }}" {{ old('bank_id', $invoice->bank_id) == $bank->id ? 'selected' : '' }}>
                            {{ $bank->nama_bank }} - {{ $bank->nomor_rekening }} ({{ $bank->atas_nama }})
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-3">
                    <div class="flex items-start mt-4">
                        <div class="flex h-6 items-center">
                            <input type="hidden" name="dengan_ttd" value="0">
                            <input id="dengan_ttd" name="dengan_ttd" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" {{ old('dengan_ttd', $invoice->dengan_ttd) ? 'checked' : '' }}>
                        </div>
                        <div class="ml-3 text-sm leading-6">
                            <label for="dengan_ttd" class="font-medium text-slate-900">Tampilkan Tanda Tangan & Stempel</label>
                            <p class="text-slate-500">Centang jika ingin menampilkan tanda tangan dan stempel di hasil cetak PDF.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Items Table -->
        <div class="px-4 py-5 border-b border-slate-200 sm:px-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg leading-6 font-medium text-slate-900">Daftar Item</h3>
                <div class="flex gap-2">
                    <button type="button" @click="showRateCardModal = true" class="inline-flex items-center px-3 py-1.5 border border-primary text-xs font-medium rounded-md text-primary bg-white hover:bg-slate-50">
                        + Dari Rate Card
                    </button>
                    <button type="button" @click="addItem()" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-primary hover:bg-primary-dark">
                        + Tambah Baris
                    </button>
                </div>
            </div>

            @error('items') <div class="mb-4 text-sm text-red-600 bg-red-50 p-3 rounded">{{ $message }}</div> @enderror

            <div class="overflow-x-auto border border-slate-200 rounded-md">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase w-10">No</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Deskripsi Pekerjaan</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase w-24">Qty</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase w-48">Harga Satuan</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase w-48">Subtotal</th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase w-16">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        <template x-for="(item, index) in items" :key="item.id">
                            <tr>
                                <td class="px-4 py-3 text-sm text-slate-500 text-center" x-text="index + 1"></td>
                                <td class="px-4 py-3">
                                    <div class="relative" x-data="{ open: false }">
                                        <input type="text" x-model="item.deskripsi" 
                                            @input="open = true" 
                                            @focus="open = true"
                                            @click.away="open = false"
                                            @keydown.escape="open = false"
                                            :name="`items[${index}][deskripsi]`" 
                                            class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md py-1 px-2 border" 
                                            placeholder="Deskripsi" required>
                                        
                                        <div x-show="open && item.deskripsi.length > 0 && getFilteredRateCardsForInput(item.deskripsi).length > 0" 
                                            class="absolute z-50 w-full mt-1 bg-white border border-slate-200 rounded-md shadow-lg max-h-60 overflow-y-auto"
                                            x-cloak>
                                            <template x-for="rc in getFilteredRateCardsForInput(item.deskripsi)" :key="rc.id">
                                                <div @click="selectRateCardFromInput(item, rc); open = false" 
                                                    class="px-4 py-2 cursor-pointer hover:bg-slate-50 border-b border-slate-100 last:border-0 transition-colors">
                                                    <div class="flex justify-between items-start">
                                                        <div>
                                                            <div class="text-sm font-medium text-slate-900" x-text="rc.nama_paket"></div>
                                                            <div class="text-xs text-slate-500" x-text="rc.sub_kategori || 'Layanan'"></div>
                                                        </div>
                                                        <div class="text-xs font-semibold text-primary" x-text="formatCurrency(rc.harga)"></div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" x-model.number="item.qty" @input="calculate()" min="1" :name="`items[${index}][qty]`" class="focus:ring-primary focus:border-primary block w-full sm:text-sm border-slate-300 rounded-md py-1 px-2 border" required>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                            <span class="text-slate-500 sm:text-sm">Rp</span>
                                        </div>
                                        <input type="number" x-model.number="item.harga_satuan" @input="calculate()" min="0" :name="`items[${index}][harga_satuan]`" class="focus:ring-primary focus:border-primary block w-full pl-8 sm:text-sm border-slate-300 rounded-md py-1 px-2 border" required>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-right text-sm font-medium text-slate-900" x-text="formatCurrency(item.qty * item.harga_satuan)">
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700 focus:outline-none" x-show="items.length > 1">
                                        <svg class="h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                    <tfoot class="bg-slate-50">
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-right text-sm font-medium text-slate-500">Subtotal</td>
                            <td class="px-4 py-3 text-right text-sm font-bold text-slate-900" x-text="formatCurrency(subtotal)"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-4 py-3 text-right text-sm font-medium text-slate-500">
                                <div class="flex items-center justify-end gap-2">
                                    <select x-model="pajak_label" name="pajak_label" class="focus:ring-primary focus:border-primary block w-40 sm:text-sm border-slate-300 rounded-md py-1 px-2 border" @change="pajak_persen = (pajak_label === 'Pajak (11%)' ? 11 : (pajak_label.includes('50%') ? 50 : 0)); calculate();">
                                        <option value="Pajak">Tanpa Pajak (0%)</option>
                                        <option value="Pajak (11%)">Pajak (11%)</option>
                                        <option value="DP 50%">DP 50%</option>
                                        <option value="Termin 1 (50%)">Termin 1 (50%)</option>
                                        <option value="Termin 2 (50%)">Termin 2 (50%)</option>
                                    </select>
                                    <input type="hidden" name="pajak_persen" :value="pajak_persen">
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="relative rounded-md shadow-sm w-48 ml-auto">
                                    <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                        <span class="text-slate-500 sm:text-sm">Rp</span>
                                    </div>
                                    <input type="number" x-model.number="pajak_amount" @input="calculateTotalOnly()" name="pajak_amount" class="focus:ring-primary focus:border-primary block w-full pl-8 sm:text-sm border-slate-300 rounded-md py-1 px-2 border text-right font-medium">
                                </div>
                            </td>
                        </tr>
                        <tr class="bg-primary/5">
                            <td colspan="4" class="px-4 py-4 text-right text-base font-bold text-slate-900 uppercase">Grand Total</td>
                            <td class="px-4 py-4 text-right text-base font-bold text-primary" x-text="formatCurrency(total)"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Notes -->
        <div class="px-4 py-5 sm:px-6">
            <label class="block text-sm font-medium text-slate-700 mb-2">Catatan / Keterangan Tambahan</label>
            <textarea name="catatan" rows="3" class="focus:ring-primary focus:border-primary block w-full shadow-sm sm:text-sm border-slate-300 rounded-md py-2 px-3 border">{{ old('catatan', $invoice->catatan) }}</textarea>
        </div>

        <!-- Submit -->
        <div class="px-4 py-4 bg-slate-50 text-right sm:px-6 border-t border-slate-200 rounded-b-lg flex justify-between">
            <button type="button" onclick="if(confirm('Yakin ingin menghapus invoice ini?')) document.getElementById('delete-form').submit();" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                Hapus Invoice
            </button>
            <button type="submit" class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                Simpan Perubahan
            </button>
        </div>
    </form>
    
    <form id="delete-form" action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <!-- Rate Card Modal (Same as Create) -->
    <div x-show="showRateCardModal" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-cloak>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showRateCardModal" x-transition.opacity class="fixed inset-0 bg-slate-500 bg-opacity-75 transition-opacity" @click="showRateCardModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showRateCardModal" x-transition.scale class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title">
                                Pilih Item dari Rate Card
                            </h3>
                            <div class="mt-4">
                                <div x-show="rateCards.length === 0" class="text-center py-4 text-sm text-slate-500">
                                    Pilih client terlebih dahulu untuk melihat rate card, atau belum ada rate card aktif.
                                </div>
                                <div class="max-h-64 overflow-y-auto border border-slate-200 rounded-md">
                                    <ul class="divide-y divide-slate-200">
                                        <template x-for="rc in rateCards" :key="rc.id">
                                            <li class="p-3 hover:bg-slate-50 flex justify-between items-center">
                                                <div>
                                                    <p class="text-sm font-medium text-slate-900" x-text="rc.nama_paket"></p>
                                                    <p class="text-xs text-slate-500" x-text="rc.kategori || 'Umum'"></p>
                                                </div>
                                                <div class="flex items-center gap-4">
                                                    <p class="text-sm font-semibold text-slate-900" x-text="formatCurrency(rc.harga) + ' / ' + rc.satuan"></p>
                                                    <button type="button" @click="addFromRateCard(rc)" class="px-2 py-1 bg-primary text-white text-xs rounded hover:bg-primary-dark">Pilih</button>
                                                </div>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="showRateCardModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('invoiceForm', () => ({
            clientId: '{{ old('client_id', $invoice->client_id) }}',
            items: {!! json_encode($invoice->items->map(function($item) {
                return [
                    'id' => $item->id,
                    'deskripsi' => $item->deskripsi,
                    'qty' => $item->qty,
                    'harga_satuan' => (float) $item->harga_satuan
                ];
            })) !!},
            subtotal: 0,
            pajak_persen: {{ old('pajak_persen', (float)$invoice->pajak_persen) }},
            pajak_label: '{{ old('pajak_label', $invoice->pajak_label ?? 'Pajak') }}',
            pajak_amount: 0,
            total: 0,
            showRateCardModal: false,
            rateCards: [],

            init() {
                if (this.items.length === 0) {
                    this.addItem();
                }
                
                @if(old('items'))
                    this.items = @json(old('items')).map(item => ({...item, id: Math.random()}));
                @endif

                if(this.clientId) {
                    this.fetchRateCards();
                }

                this.calculate();
            },

            addItem() {
                this.items.push({ id: Date.now(), deskripsi: '', qty: 1, harga_satuan: 0 });
            },

            removeItem(index) {
                if (this.items.length > 1) {
                    this.items.splice(index, 1);
                    this.calculate();
                }
            },

            calculate() {
                this.subtotal = this.items.reduce((sum, item) => sum + (item.qty * item.harga_satuan), 0);
                this.pajak_amount = (this.pajak_persen / 100) * this.subtotal;
                this.calculateTotalOnly();
            },

            calculateTotalOnly() {
                const isTermin = this.pajak_label.toLowerCase().includes('termin') || this.pajak_label.toLowerCase().includes('dp');
                this.total = isTermin ? this.pajak_amount : (this.subtotal + (this.pajak_amount || 0));
            },

            formatCurrency(value) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0, maximumFractionDigits: 0 }).format(value);
            },

            fetchRateCards() {
                fetch(`{{ route('rate-cards.items') }}?client_id=${this.clientId}`)
                    .then(res => res.json())
                    .then(data => {
                        this.rateCards = data;
                    });
            },

            addFromRateCard(rc) {
                console.log('Adding from rate card:', rc);
                const harga = parseFloat(rc.harga) || 0;

                // If only 1 empty item exists, replace it
                if (this.items.length === 1 && this.items[0].deskripsi === '' && (this.items[0].harga_satuan == 0 || this.items[0].harga_satuan == null)) {
                    this.items[0].deskripsi = rc.nama_paket;
                    this.items[0].harga_satuan = harga;
                    this.items[0].qty = 1;
                    this.items[0] = {...this.items[0]};
                } else {
                    this.items.push({ id: Date.now(), deskripsi: rc.nama_paket, qty: 1, harga_satuan: harga });
                }
                this.calculate();
                this.showRateCardModal = false;
            },

            getFilteredRateCardsForInput(search) {
                if (!search || search.length < 2) return [];
                const s = search.toLowerCase();
                return this.rateCards.filter(rc => 
                    (rc.nama_paket && rc.nama_paket.toLowerCase().includes(s)) ||
                    (rc.sub_kategori && rc.sub_kategori.toLowerCase().includes(s))
                ).slice(0, 10);
            },

            selectRateCardFromInput(item, rc) {
                const index = this.items.findIndex(i => i.id === item.id);
                if (index !== -1) {
                    this.items[index].deskripsi = rc.nama_paket;
                    this.items[index].harga_satuan = parseFloat(rc.harga) || 0;
                    this.items[index] = {...this.items[index]};
                    this.calculate();
                }
            }
        }));
    });
</script>
@endpush
@endsection
