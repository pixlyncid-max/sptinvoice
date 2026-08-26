@extends('layouts.app')

@section('title', 'Edit Kontak Email')

@section('content')
<div class="max-w-2xl bg-white shadow-sm rounded-lg border border-slate-200 p-6">
    <form action="{{ route('email-marketing.contacts.update', $contact) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $contact->name) }}" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm py-2 px-3 border">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Alamat Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ old('email', $contact->email) }}" required class="w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm py-2 px-3 border">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="company" class="block text-sm font-medium text-slate-700 mb-1">Perusahaan / Instansi (Opsional)</label>
                <input type="text" name="company" id="company" value="{{ old('company', $contact->company) }}" class="w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary sm:text-sm py-2 px-3 border">
                @error('company') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="pt-2">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_subscribed" value="1" {{ old('is_subscribed', $contact->is_subscribed) ? 'checked' : '' }} class="rounded border-slate-300 text-primary shadow-sm focus:ring-primary h-4 w-4">
                    <span class="ml-2 text-sm text-slate-700 font-medium">Status Berlangganan (Subscribed)</span>
                </label>
                <p class="text-xs text-slate-500 ml-6">Kontak akan menerima email campaign marketing jika opsi ini dicentang.</p>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('email-marketing.contacts.index') }}" class="px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50">
                Batal
            </a>
            <button type="submit" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-primary-dark">
                Perbarui Kontak
            </button>
        </div>
    </form>
</div>
@endsection
