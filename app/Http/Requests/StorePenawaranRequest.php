<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePenawaranRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $penawaranId = $this->route('penawaran') ? $this->route('penawaran')->id : null;

        return [
            'nomor_penawaran' => 'required|string|unique:penawaran,nomor_penawaran,' . $penawaranId,
            'perihal' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
            'tanggal' => 'required|date',
            'berlaku_hingga' => 'required|date|after_or_equal:tanggal',
            'status' => 'required|in:draft,dikirim,disetujui,ditolak',
            'catatan' => 'nullable|string',
            'diskon' => 'nullable|numeric|min:0',
            'dengan_ttd' => 'boolean',
            'pajak_persen' => 'nullable|numeric|min:0|max:100',
            'pajak_label' => 'nullable|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.deskripsi' => 'required|string|max:255',
            'items.*.keterangan' => 'nullable|string|max:1000',
            'items.*.kategori_layanan' => 'required|string|in:Jasa Pembuatan Aset Digital,Jasa Digital Marketing,Jasa Perpajakan,Jasa Perizinan,Fee Pekerjaan,Jenis Pekerjaan,Data Yang Diperlukan',
            'items.*.qty' => 'required|numeric|min:0',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'nomor_penawaran.required' => 'Nomor penawaran wajib diisi.',
            'nomor_penawaran.unique' => 'Nomor penawaran sudah ada.',
            'client_id.required' => 'Client wajib dipilih.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'berlaku_hingga.required' => 'Berlaku hingga wajib diisi.',
            'berlaku_hingga.after_or_equal' => 'Berlaku hingga harus setelah atau sama dengan tanggal.',
            'items.required' => 'Penawaran harus memiliki minimal satu item.',
            'items.*.deskripsi.required' => 'Deskripsi item wajib diisi.',
            'items.*.qty.min' => 'Qty minimal 1.',
            'items.*.harga_satuan.min' => 'Harga satuan minimal 0.',
        ];
    }
}
