@props(['status'])

@php
$classes = match($status) {
    'draft' => 'bg-slate-100 text-slate-600',
    'dikirim' => 'bg-blue-50 text-blue-600',
    'dibayar', 'disetujui' => 'bg-emerald-50 text-emerald-600',
    'ditolak' => 'bg-red-50 text-red-600',
    'jatuh_tempo' => 'bg-amber-50 text-amber-600',
    default => 'bg-slate-100 text-slate-600',
};
@endphp

<span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold {{ $classes }}">
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>
