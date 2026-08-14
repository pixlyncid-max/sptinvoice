<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clientId = $this->route('client') ? $this->route('client')->id : null;

        return [
            'nama' => 'nullable|string|max:255',
            'perusahaan' => 'required|string|max:255',
            'email' => 'nullable|email|unique:clients,email,' . $clientId,
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'jenis_pekerjaan' => 'required|in:Satuan,Bulanan,Tahunan',
            'status' => 'required|in:Aktif,Non Aktif,Pending',
        ];
    }

    public function messages(): array
    {
        return [
            'perusahaan.required' => 'Nama perusahaan wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'jenis_pekerjaan.required' => 'Jenis pekerjaan wajib dipilih.',
            'status.required' => 'Status wajib dipilih.',
        ];
    }
}
