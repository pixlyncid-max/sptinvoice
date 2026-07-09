<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRateCardRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'divisi' => 'required|in:digital_marketing,keuangan_perpajakan,perizinan',
            'sub_kategori' => 'nullable|string|max:255',
            'nama_paket' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:50',
            'status' => 'nullable|in:aktif,nonaktif',
        ];
    }

    public function messages(): array
    {
        return [
            'divisi.required' => 'Divisi wajib dipilih.',
            'nama_paket.required' => 'Nama paket wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.min' => 'Harga minimal 0.',
            'satuan.required' => 'Satuan wajib diisi.',
        ];
    }
}
