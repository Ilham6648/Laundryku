@props(['size' => 'md'])

@php
    $boxClasses = match ($size) {
        'sm' => 'w-20 h-24',
        'lg' => 'w-28 h-32 sm:w-32 sm:h-36',
        default => 'w-24 h-28',
    };

    $drumClasses = match ($size) {
        'sm' => 'w-12 h-12',
        'lg' => 'w-16 h-16 sm:w-20 sm:h-20',
        default => 'w-14 h-14',
    };
@endphp

@once
    <style>
        @keyframes laundryDrumSpin {
            0% { transform: rotate(0deg); }
            25% { transform: rotate(110deg); }
            50% { transform: rotate(220deg); }
            75% { transform: rotate(300deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes laundryWaterWave {
            0%, 100% { transform: translateY(0px) rotate(-4deg); }
            50% { transform: translateY(-2px) rotate(5deg); }
        }
        @keyframes laundryMachineVibrate {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            25% { transform: translateY(-1px) rotate(-0.6deg); }
            75% { transform: translateY(0.5px) rotate(0.6deg); }
        }
        @keyframes laundryBubbleFloat {
            0% { transform: translateY(6px) scale(0.7); opacity: 0.3; }
            50% { transform: translateY(-3px) scale(1); opacity: 0.95; }
            100% { transform: translateY(-10px) scale(0.8); opacity: 0; }
        }
        .washing-machine-body {
            animation: laundryMachineVibrate 0.55s ease-in-out infinite;
        }
        .washing-drum-spin {
            animation: laundryDrumSpin 1.35s linear infinite;
        }
        .washing-water-slosh {
            animation: laundryWaterWave 1.1s ease-in-out infinite;
        }
        .washing-bubble-1 {
            animation: laundryBubbleFloat 1.8s ease-in-out infinite;
        }
        .washing-bubble-2 {
            animation: laundryBubbleFloat 2.2s ease-in-out 0.6s infinite;
        }
        .washing-bubble-3 {
            animation: laundryBubbleFloat 1.6s ease-in-out 1.1s infinite;
        }
    </style>
@endonce

<div {{ $attributes->merge(['class' => 'relative inline-flex items-center justify-center select-none']) }} aria-label="Animasi Mesin Cuci Berputar">
    {{-- Gelembung Sabun Mengambang di Luar --}}
    <span class="washing-bubble-1 absolute -top-2 -left-2 h-3 w-3 rounded-full bg-cyan-200/80 ring-1 ring-white/90"></span>
    <span class="washing-bubble-2 absolute -top-3 right-0 h-2.5 w-2.5 rounded-full bg-sky-200/80 ring-1 ring-white/90"></span>
    <span class="washing-bubble-3 absolute top-2 -right-3 h-3.5 w-3.5 rounded-full bg-white/70 ring-1 ring-cyan-200"></span>

    {{-- Kabinet Mesin Cuci --}}
    <div class="washing-machine-body {{ $boxClasses }} rounded-2xl bg-gradient-to-b from-white via-sky-50 to-sky-100 shadow-lg ring-2 ring-white/80 flex flex-col justify-between p-2 relative overflow-hidden">
        {{-- Panel Kontrol Atas --}}
        <div class="flex items-center justify-between border-b border-sky-200/90 pb-1.5 px-1">
            {{-- Laci Deterjen --}}
            <div class="h-2 w-6 rounded-xs bg-sky-200/90 border border-sky-300"></div>
            {{-- Layar LED & Lampu Indikator --}}
            <div class="flex items-center gap-1">
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                <span class="h-2 w-2 rounded-full bg-sky-600 ring-1 ring-sky-300"></span>
            </div>
        </div>

        {{-- Pintu Kaca Tabung Mesin Cuci --}}
        <div class="flex-1 flex items-center justify-center">
            <div class="{{ $drumClasses }} rounded-full bg-slate-900 p-1.5 ring-4 ring-sky-300 shadow-inner relative overflow-hidden flex items-center justify-center">
                {{-- Isi Tabung Berputar (Pakaian & Baling-Baling) --}}
                <div class="washing-drum-spin absolute inset-1 rounded-full border-2 border-dashed border-cyan-300/60 flex items-center justify-center">
                    {{-- Potongan Pakaian Berwarna yang Sedang Dicuci --}}
                    <span class="absolute top-1 left-2 h-3.5 w-4 rounded-md bg-rose-400 rotate-12 shadow-2xs"></span>
                    <span class="absolute bottom-1.5 right-1.5 h-4 w-4 rounded-md bg-amber-400 -rotate-12 shadow-2xs"></span>
                    <span class="absolute bottom-1 left-1.5 h-3 w-4 rounded-md bg-indigo-400 rotate-45 shadow-2xs"></span>
                    <span class="absolute top-2 right-1.5 h-3 w-3 rounded-full bg-emerald-400"></span>
                </div>

                {{-- Air Sabun Bergelombang --}}
                <div class="washing-water-slosh absolute -bottom-2 -inset-x-2 h-3/5 rounded-t-full bg-gradient-to-t from-sky-500/90 via-cyan-400/80 to-cyan-200/65 backdrop-blur-[1px] border-t border-white/70"></div>

                {{-- Busa Sabun di Dalam Tabung --}}
                <span class="washing-bubble-1 absolute bottom-2 left-2 h-2 w-2 rounded-full bg-white/90"></span>
                <span class="washing-bubble-2 absolute bottom-3 right-2.5 h-2.5 w-2.5 rounded-full bg-white/85"></span>
                <span class="washing-bubble-3 absolute bottom-1.5 left-1/2 h-1.5 w-1.5 rounded-full bg-cyan-100"></span>

                {{-- Pantulan Kaca Pintu --}}
                <div class="absolute top-1 left-1.5 w-1/2 h-1/3 rounded-full bg-gradient-to-br from-white/45 to-transparent -rotate-12 pointer-events-none"></div>
            </div>
        </div>

        {{-- Kaki Bawah Mesin Cuci --}}
        <div class="h-1 w-full rounded-full bg-sky-200/80"></div>
    </div>
</div>
