@extends('layouts.app')

@section('title', 'Konfigurasi Sistem')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PATCH')

        @foreach($settings as $group => $items)
        <div class="bg-white overflow-hidden shadow-sm rounded-lg border border-slate-200 mb-6">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">{{ $group }}</h3>
                @if(str_contains(strtolower($group), 'sosial') || str_contains(strtolower($group), 'email'))
                <span class="text-xs text-slate-500">Tautan ini otomatis digunakan pada Footer Broadcast Email</span>
                @endif
            </div>
            <div class="p-6 space-y-4">
                @foreach($items as $setting)
                <div>
                    <label for="setting_{{ $setting->key }}" class="block text-xs font-semibold text-slate-700 mb-1">
                        {{ ucwords(str_replace(['_', 'url'], [' ', 'URL'], $setting->key)) }}
                    </label>

                    @if(str_contains($setting->key, 'url') || str_contains($setting->key, 'link') || str_contains($setting->key, 'whatsapp') || str_contains($setting->key, 'social') || str_contains($setting->key, 'instagram') || str_contains($setting->key, 'facebook') || str_contains($setting->key, 'threads') || str_contains($setting->key, 'tiktok'))
                        <input type="text" name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}" value="{{ $setting->value }}" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary text-sm py-2 px-3 border" placeholder="https://...">
                    @elseif(str_contains($setting->key, 'persen'))
                        <div class="relative rounded-md shadow-sm">
                            <input type="number" step="0.01" name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}" value="{{ $setting->value }}" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary text-sm py-2 px-3 border">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-slate-500 text-sm">%</span>
                            </div>
                        </div>
                    @else
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <span class="text-slate-500 text-sm">Rp</span>
                            </div>
                            <input type="number" name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}" value="{{ $setting->value }}" class="block w-full pl-10 rounded-md border-slate-300 shadow-sm focus:border-primary focus:ring-primary text-sm py-2 px-3 border">
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-primary hover:bg-primary-dark border border-transparent rounded-md font-semibold text-sm text-white shadow-sm transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
