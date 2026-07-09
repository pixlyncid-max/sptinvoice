<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $invoiceId = $this->route('invoice') ? $this->route('invoice')->id : null;

        return [
            'nomor_invoice' => 'required|string|unique:invoices,nomor_invoice,' . $invoiceId,
            'client_id' => 'required|exists:clients,id',
            'tanggal_invoice' => 'required|date',
            'tanggal_jatuh_tempo' => 'required|date|after_or_equal:tanggal_invoice',
            'status' => 'required|in:draft,dikirim,dibayar,batal',
            'periode' => 'nullable|string|max:100',
            'catatan' => 'nullable|string',
            'dengan_ttd' => 'boolean',
            'bank_id' => 'nullable|exists:banks,id',
            'pajak_persen' => 'nullable|numeric|min:0|max:100',
            'pajak_label' => 'nullable|string|max:100',
            'items' => 'required|array|min:1',
            'items.*.deskripsi' => 'required|string|max:255',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'nomor_invoice.required' => 'Nomor invoice wajib diisi.',
            'nomor_invoice.unique' => 'Nomor invoice sudah ada.',
            'client_id.required' => 'Client wajib dipilih.',
            'client_id.exists' => 'Client tidak ditemukan.',
            'tanggal_invoice.required' => 'Tanggal invoice wajib diisi.',
            'tanggal_jatuh_tempo.required' => 'Tanggal jatuh tempo wajib diisi.',
            'tanggal_jatuh_tempo.after_or_equal' => 'Tanggal jatuh tempo harus setelah atau sama dengan tanggal invoice.',
            'items.required' => 'Invoice harus memiliki minimal satu item.',
            'items.min' => 'Invoice harus memiliki minimal satu item.',
            'items.*.deskripsi.required' => 'Deskripsi item wajib diisi.',
            'items.*.qty.required' => 'Qty item wajib diisi.',
            'items.*.qty.min' => 'Qty minimal 1.',
            'items.*.harga_satuan.required' => 'Harga satuan wajib diisi.',
            'items.*.harga_satuan.min' => 'Harga satuan minimal 0.',
        ];
    }
}
