@extends('layouts.app')

@section('title', 'Konfigurasi Sistem')

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('settings.update') }}" method="POST">
        @csrf
        @method('PATCH')

        @foreach($settings as $group => $items)
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800 uppercase tracking-wider">{{ $group }}</h3>
            </div>
            <div class="p-6 space-y-4">
                @foreach($items as $setting)
                <div>
                    <label for="setting_{{ $setting->key }}" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                    </label>
                    <div class="relative rounded-md shadow-sm">
                        @if(str_contains($setting->key, 'persen'))
                        <input type="number" step="0.01" name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}" value="{{ $setting->value }}" class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">%</span>
                        </div>
                        @else
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Rp</span>
                        </div>
                        <input type="number" name="settings[{{ $setting->key }}]" id="setting_{{ $setting->key }}" value="{{ $setting->value }}" class="block w-full pl-10 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
