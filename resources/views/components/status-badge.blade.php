@props(['type', 'value'])

@php
    $classes = match ($value) {
        'Selesai', 'Lunas' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'Proses' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'Belum Lunas' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
        default => 'bg-slate-100 text-slate-700 ring-slate-500/20',
    };

    $dot = match ($value) {
        'Selesai', 'Lunas' => 'bg-emerald-500',
        'Proses' => 'bg-amber-500',
        'Belum Lunas' => 'bg-rose-500',
        default => 'bg-slate-400',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {$classes}"]) }}>
    <span class="h-1.5 w-1.5 rounded-full {{ $dot }}"></span>
    {{ $value }}
</span>
